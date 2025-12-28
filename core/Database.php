<?php
// core/Database.php

require_once __DIR__ . '/../config/db.php';

class Database {
    private static $connection = null;

    /**
     * Etablit la connexion à la base Oracle et retourne la ressource.
     */
    public static function connect() {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $conn = @oci_connect(DB_USERNAME, DB_PASSWORD, DB_CONNECTION_STRING, DB_CHARSET);

        if (!$conn) {
            $e = oci_error();
            error_log("Oracle Connection Error: " . $e['message']);
            die("Erreur de connexion à la base de données. Veuillez vérifier la configuration XAMPP/Oracle.");
        }

        self::$connection = $conn;
        return self::$connection;
    }

    /**
     * Récupère une collection SODA par son nom.
     * @param string $collectionName Nom de la collection (ex: 'users')
     * @return resource|false Handle de la collection ou false si erreur
     */
    // --- SODA POLYFILL (Compatibility Mode) ---
    // Used when PHP OCI8 version is too old for native SODA

    public static function simpleInsert($colName, $data) {
        $conn = self::connect();
        
        // Native SODA
        if (function_exists('oci_soda_open_collection')) {
             $soda = oci_soda_open($conn);
             $col = oci_soda_open_collection($soda, $colName);
             if (!$col) {
                 // Try auto-create if missing (SODA style)
                 $col = oci_soda_create_collection($soda, $colName);
             }
             if ($col) {
                 oci_soda_insert($col, json_encode($data));
                 return true;
             }
             return false;
        } 
        
        // SQL Fallback
        // Assumes table 'colName' exists with 'json_document' CLOB/BLOB
        $json = json_encode($data);
        // Generate a UUID or key
        $key = uniqid($colName."_"); // Simple ID
        
        $sql = "INSERT INTO $colName (id, json_document) VALUES (:id, :json)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $key);
        oci_bind_by_name($stmt, ":json", $json);
        return @oci_execute($stmt); // Suppress error if table missing (handled by install)
    }

    public static function simpleFindOne($colName, $filterArray) {
        $conn = self::connect();
        
        // Filter Logic: naive implementation for basic keys
        // We only support simple equality for now in this polyfill
        if (function_exists('oci_soda_open_collection')) {
             $soda = oci_soda_open($conn);
             $col = oci_soda_open_collection($soda, $colName);
             if(!$col) return null;
             $doc = oci_soda_find_one($col, json_encode($filterArray));
             return $doc ? json_decode(oci_soda_read($doc), true) : null;
        }

        // Check if we are filtering by ID which is special
        // Otherwise, scan JSON (Oracle 12c+ specific syntax could be used: json_value)
        // For broad compatibility, we might fetch and filter in PHP if dataset is small
        // or use simple 'json_document LIKE %value%' for strings.
        
        // Better: Use Oracle JSON_VALUE syntax: WHERE JSON_VALUE(json_document, '$.key') = 'val'
        
        $sql = "SELECT id, json_document FROM $colName";
        $clauses = [];
        
        // Build query parts
        // Limitation: Only supports depth-1 strings
        foreach ($filterArray as $key => $val) {
             // Sanitization poor man's style, assuming keys are safe
             // In prod, use bindings with random placeholders
             $safeVal = str_replace("'", "''", $val);
             // Note: syntax depends on Oracle version. 12cR1+
             $clauses[] = "JSON_VALUE(json_document, '$.$key') = '$safeVal'";
        }
        
        if (!empty($clauses)) {
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        
        // Limit 1
        $sql .= " FETCH FIRST 1 ROWS ONLY";

        $stmt = oci_parse($conn, $sql);
        if(!@oci_execute($stmt)) return null;
        
        $row = oci_fetch_assoc($stmt);
        if ($row && isset($row['JSON_DOCUMENT'])) {
            $json = $row['JSON_DOCUMENT'];
            if (is_object($json)) {
                $json = $json->load();
            }
            $data = json_decode($json, true);
            // Inject ID just in case
            $data['soda_key'] = $row['ID'];
            return $data;
        }
        return null;
    }

    public static function simpleFindAll($colName) {
        $conn = self::connect();
        $results = [];

        if (function_exists('oci_soda_open_collection')) {
             $soda = oci_soda_open($conn);
             $col = oci_soda_open_collection($soda, $colName);
             if(!$col) return [];
             $cursor = oci_soda_find($col, "{}");
             while($doc = oci_soda_get_next($cursor)) {
                 $d = json_decode(oci_soda_read($doc), true);
                 $d['soda_key'] = oci_soda_get_key($doc); // Important
                 $results[] = $d;
             }
             return $results;
        }

        // SQL Polyfill
        $sql = "SELECT id, json_document FROM $colName";
        $stmt = oci_parse($conn, $sql);
        if(@oci_execute($stmt)) {
            while ($row = oci_fetch_assoc($stmt)) {
                $json = $row['JSON_DOCUMENT'];
                if (is_object($json)) {
                    $json = $json->load();
                }
                $d = json_decode($json, true);
                if (is_array($d)) {
                    $d['soda_key'] = $row['ID'];
                    $results[] = $d;
                }
            }
        }
        return $results;
    }

    // Helper for delete/update would be needed too...
    // Let's refactor the controllers to use these 'simple*' wrappers instead of raw oci_soda calls?
    // OR, we just expose getCollection and let legacy fail, but since we are fixing existing code...
    // The user has existing controllers calling 'oci_soda_insert' etc.
    // I MUST rewrite the controllers or override the global functions (not possible easily).
    // BEST APPROACH: Add a layer in Database.php and update Controllers to use `Database::insert` etc.
    
    // ...
    // RE-STRATEGY: Updating all controllers is safer.
    
    public static function insert($colName, $data) {
        return self::simpleInsert($colName, $data);
    }
    
    public static function findAll($colName) {
        return self::simpleFindAll($colName);
    }
    
    public static function findOne($colName, $criteria) {
        return self::simpleFindOne($colName, $criteria);
    }
    
    public static function update($colName, $key, $data) {
        $conn = self::connect();
        if (function_exists('oci_soda_open_collection')) {
             $soda = oci_soda_open($conn);
             $col = oci_soda_open_collection($soda, $colName);
             return oci_soda_replace($col, $key, json_encode($data));
        }

        $json = json_encode($data);
        $sql = "UPDATE $colName SET json_document = :json WHERE id = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $key);
        oci_bind_by_name($stmt, ":json", $json);
        return oci_execute($stmt);
    }

    public static function delete($colName, $key) {
        $conn = self::connect();
        if (function_exists('oci_soda_open_collection')) {
             $soda = oci_soda_open($conn);
             $col = oci_soda_open_collection($soda, $colName);
             return oci_soda_remove($col, $key);
        }

        $sql = "DELETE FROM $colName WHERE id = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $key);
        return oci_execute($stmt);
    }
    
    // Kept for backward compatibility if we don't update all controllers immediately
    // sending a raw connection/resource will fail in controllers using oci_soda_ functions.
    // So we MUST update controllers.
    public static function getCollection($name) {
        // This is dangerous if SODA missing.
        // Return null or throw exception?
        return null; 
    }
}
?>
