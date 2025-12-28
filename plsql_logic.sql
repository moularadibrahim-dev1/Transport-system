-- ==========================================================
-- LOGIQUE MÉTIER PL/SQL - SUPTECH TRANSPORT
-- ==========================================================

-- 1. Procédure Stockée : AJOUT_VEHICULE
-- Cette procédure prend des paramètres relationnels et crée le document JSON
-- Avantage : Centralise la logique de validation côté base de données.
CREATE OR REPLACE PROCEDURE AJOUT_VEHICULE (
    p_matricule IN VARCHAR2, 
    p_capacity  IN NUMBER, 
    p_type      IN VARCHAR2,
    p_internat  IN NUMBER -- 0 ou 1
) AS
    v_collection DBMS_SODA.SODA_COLLECTION_T;
    v_document   DBMS_SODA.SODA_DOCUMENT_T;
    v_json       VARCHAR2(4000);
    v_id         VARCHAR2(50);
    v_status     NUMBER;
BEGIN
    -- Génération ID unique
    v_id := 'V' || TO_CHAR(SYSTIMESTAMP, 'YYYYMMDDHH24MISS');
    
    -- Construction du JSON
    v_json := '{
        "vehicle_id": "' || v_id || '", 
        "matricule": "' || p_matricule || '", 
        "capacity": ' || p_capacity || ', 
        "type": "' || p_type || '", 
        "is_internat": ' || CASE WHEN p_internat = 1 THEN 'true' ELSE 'false' END || '
    }';

    -- Insertion via SODA
    v_collection := DBMS_SODA.open_collection('vehicles');
    
    -- Si la collection n'existe pas, la créer (sécurité)
    IF v_collection IS NULL THEN
        v_status := DBMS_SODA.create_collection('vehicles');
        v_collection := DBMS_SODA.open_collection('vehicles');
    END IF;

    v_document := DBMS_SODA.SODA_DOCUMENT_T(
        b_content => UTL_RAW.cast_to_raw(v_json)
    );
    v_status := v_collection.insert_one(v_document);
    
    COMMIT;
    DBMS_OUTPUT.put_line('Véhicule ' || p_matricule || ' ajouté avec succès.');
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        DBMS_OUTPUT.put_line('Erreur: ' || SQLERRM);
END;
/

-- 2. Vue Relationnelle sur Données JSON (Pour le Reporting)
-- Transforme les documents JSON de la collection 'students' en table virtuelle
-- Utile pour connecter des outils de BI (PowerBI, Excel) ou faire des requêtes SQL classiques
CREATE OR REPLACE VIEW V_LISTE_ETUDIANTS AS
SELECT 
    jt.id,
    jt.nom,
    jt.email,
    jt.trajet_prefere
FROM vehicles_collection v, -- Note: le nom de table interne est souvent le nom de la collection
     JSON_TABLE(v.json_document, '$' 
        COLUMNS (
            id PATH '$.student_id',
            nom PATH '$.name',
            email PATH '$.email',
            trajet_prefere PATH '$.route_id'
        )
     ) jt;
/

-- 3. Procédure Complexe : AFFECTATION_INTELLIGENTE
-- Affecte un chauffeur à un trajet, mais vérifie d'abord s'il est libre
CREATE OR REPLACE PROCEDURE AFFECTER_CHAUFFEUR (
    p_driver_id IN VARCHAR2,
    p_route_id  IN VARCHAR2
) AS
    v_count NUMBER;
    v_coll_assign DBMS_SODA.SODA_COLLECTION_T;
    v_doc         DBMS_SODA.SODA_DOCUMENT_T;
BEGIN
    -- Vérification : Le chauffeur est-il déjà affecté aujourd'hui ?
    -- On utilise SQL/JSON pour requêter la collection 'assignments'
    SELECT COUNT(*)
    INTO v_count
    FROM assignments_tbl a -- Nom de table interne (supposé assignments)
    WHERE JSON_VALUE(a.json_document, '$.driver_id') = p_driver_id
    AND JSON_VALUE(a.json_document, '$.date') = TO_CHAR(SYSDATE, 'YYYY-MM-DD');

    IF v_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Ce chauffeur est déjà affecté aujourd''hui !');
    END IF;

    -- Si Libre, on crée l'affectation
    v_coll_assign := DBMS_SODA.open_collection('assignments');
    
    v_doc := DBMS_SODA.SODA_DOCUMENT_T(
        b_content => UTL_RAW.cast_to_raw(
            '{"driver_id": "' || p_driver_id || '", "route_id": "' || p_route_id || '", "date": "' || TO_CHAR(SYSDATE, 'YYYY-MM-DD') || '"}'
        )
    );
    
    v_count := v_coll_assign.insert_one(v_doc);
    COMMIT;
END;
/
