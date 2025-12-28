-- ==========================================================
-- SCRIPT DE CONFIGURATION NOSQL - SUPTECH TRANSPORT SYSTEM
-- A exécuter dans Oracle SQL Developer
-- ==========================================================

-- 1. Activation de SODA (si nécessaire pour l'utilisateur, souvent activé par défaut)
-- BEGIN
--     DBMS_SODA_ADMIN.create_role_if_needed;
-- END;
-- /

-- 2. Création des Collections (Documents JSON)
-- Ces commandes créent des tables gérées par SODA pour stocker du JSON

DECLARE
    collection_status NUMBER;
BEGIN
    -- Collection Users (Comptes de connexion)
    collection_status := DBMS_SODA.create_collection('users');
    
    -- Collection Students (Profils étudiants détaillés)
    collection_status := DBMS_SODA.create_collection('students');
    
    -- Collection Drivers (Chauffeurs - 12 au total)
    collection_status := DBMS_SODA.create_collection('drivers');
    
    -- Collection Vehicles (Transports - 12 au total)
    collection_status := DBMS_SODA.create_collection('vehicles');
    
    -- Collection Routes (Trajets définis)
    collection_status := DBMS_SODA.create_collection('routes');
    
    -- Collection Assignments (Affectations Transport <-> Chauffeur <-> Trajet)
    collection_status := DBMS_SODA.create_collection('assignments');
    
    DBMS_OUTPUT.put_line('Collections créées avec succès.');
END;
/

-- 3. Insertion des Données Initiales (Exemples de documents JSON)
-- Vous pouvez exécuter ces blocs pour pré-remplir la base

-- INSERTION CHAUFFEURS (12 Chauffeurs)
DECLARE
    coll_drivers SODA_COLLECTION_T;
    doc_driver   SODA_DOCUMENT_T;
    status       NUMBER;
BEGIN
    coll_drivers := DBMS_SODA.open_collection('drivers');
    
    -- Chauffeur 1
    doc_driver := SODA_DOCUMENT_T(
        b_content => utl_raw.cast_to_raw('{"driver_id": "D01", "name": "Mohamed Alami", "phone": "0600000001", "status": "active"}')
    );
    status := coll_drivers.insert_one(doc_driver);
    
    -- Répéter pour les autres chauffeurs...
    -- (Simplifié pour le script, à faire via l'interface Admin de l'appli ou boucle PL/SQL)
END;
/

-- INSERTION VEHICULES (12 Véhicules dont 2 Internat)
DECLARE
    coll_vehicles SODA_COLLECTION_T;
    doc_vehicle   SODA_DOCUMENT_T;
    status        NUMBER;
BEGIN
    coll_vehicles := DBMS_SODA.open_collection('vehicles');
    
    -- Bus Internat 1
    doc_vehicle := SODA_DOCUMENT_T(
        b_content => utl_raw.cast_to_raw('{"vehicle_id": "V01", "matricule": "1234-A-1", "capacity": 50, "type": "Bus", "is_internat": true}')
    );
    status := coll_vehicles.insert_one(doc_vehicle);
    
    -- Transport Externe 1
    doc_vehicle := SODA_DOCUMENT_T(
        b_content => utl_raw.cast_to_raw('{"vehicle_id": "V03", "matricule": "9999-B-26", "capacity": 20, "type": "Minibus", "is_internat": false}')
    );
    status := coll_vehicles.insert_one(doc_vehicle);
END;
/

-- INSERTION TRAJETS (Routes Officielles)
DECLARE
    coll_routes SODA_COLLECTION_T;
    doc_route   SODA_DOCUMENT_T;
    status      NUMBER;
BEGIN
    coll_routes := DBMS_SODA.open_collection('routes');
    
    -- Trajet Mohamed VI
    doc_route := SODA_DOCUMENT_T(
        b_content => utl_raw.cast_to_raw('{"route_id": "R_MOHAMED_VI", "name": "Mohamed VI", "stops": ["Riad Salam", "Majorelle", "Taxi Ait Taki", "Lycée Ibn Khaldoun"], "departure_time_morning": "07:30"}')
    );
    status := coll_routes.insert_one(doc_route);
    
    -- Trajet Gare
    doc_route := SODA_DOCUMENT_T(
        b_content => utl_raw.cast_to_raw('{"route_id": "R_GARE", "name": "Gare", "stops": ["Gare Principale", "Centre Ville"], "departure_time_morning": "07:45"}')
    );
    status := coll_routes.insert_one(doc_route);
END;
/

-- INSERTION UTILISATEUR ADMIN PAR DEFAUT
-- Mot de passe : "admin123" (Hashé en production, ici texte clair pour l'exemple JSON, mais hashé par PHP avant insertion normalement)
DECLARE
    coll_users SODA_COLLECTION_T;
    doc_user   SODA_DOCUMENT_T;
    status     NUMBER;
BEGIN
    coll_users := DBMS_SODA.open_collection('users');
    
    -- Admin
    -- Note: Le mot de passe devra être hashé via password_hash() en PHP.
    -- Ceci est juste un placeholder.
    doc_user := SODA_DOCUMENT_T(
        b_content => utl_raw.cast_to_raw('{"username": "admin@suptech.ma", "role": "admin", "password_hash": "$2y$10$YourHashedPasswordHere"}')
    );
    status := coll_users.insert_one(doc_user);
END;
/

COMMIT;
