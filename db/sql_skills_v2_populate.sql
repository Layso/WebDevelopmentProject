DELIMITER $$
CREATE DEFINER=root@localhost PROCEDURE sql_skills_v2_reset()
BEGIN
   -- Désactiver les contraintes d'intégrité référentielle pour les TRUNCATE
	SET FOREIGN_KEY_CHECKS = 0;
	TRUNCATE TABLE evaluation;
	TRUNCATE TABLE usergroup;
	TRUNCATE TABLE group_member;
	TRUNCATE TABLE sheet;
	TRUNCATE TABLE sheet_answer;
	TRUNCATE TABLE sql_question;
	TRUNCATE TABLE sql_quiz;
	TRUNCATE TABLE sql_quiz_question;
	TRUNCATE TABLE theme;
	
	TRUNCATE TABLE training;
   TRUNCATE TABLE training_answer;
	TRUNCATE TABLE person;
	TRUNCATE TABLE quiz_db;
   -- Rétablir les contraintes d'intégrité référentielle, pour garantir
   -- d'insérer des données cohérentes
	SET FOREIGN_KEY_CHECKS = 1;

	BEGIN
		DECLARE EXIT HANDLER FOR SQLEXCEPTION
		BEGIN
			SHOW ERRORS;
			ROLLBACK;
		END;
		START TRANSACTION;
			INSERT INTO theme (theme_id, label) VALUES 
			(1, 'Requêtes simples'),
			(2, 'Restrictions'),
			(3, 'Jointures'),
			(4, 'Requêtes imbriquées 1 valeur'),
			(5, 'Requêtes imbriquées n valeurs'),
			(6, 'Having'),
			(7, 'Agrégats'),
			(8, 'Divers');
		
		INSERT INTO person (person_id, email, pwd, name, first_name, created_at, validated_at, is_trainer) VALUES 
				(1, 'etudiant1@gmail.com', 'azerty', 'Sherlock', 'Holmes', NOW() - INTERVAL 3 DAY, NOW() - INTERVAL 2 DAY - INTERVAL 2 HOUR, 0),
				(2, 'etudiant2@gmail.com', 'azerty', 'Obama', 'Barack', NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 1 DAY - INTERVAL 2 HOUR, 0),
				(3, 'etudiant3@gmail.com', 'azerty', 'Curie', 'Marie', NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 2 HOUR, 0),
				(4, 'etudiant4@gmail.com', 'azerty', 'Curie', 'Pierre', NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 4 DAY - INTERVAL 9 HOUR, 0),
				(5, 'etudiant5@gmail.com', 'azerty', 'Moquet', 'Guy', NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 3 DAY - INTERVAL 12 HOUR, 0),
				(6, 'formateur1@gmail.com', 'azerty', 'Apollinaire', 'Guillaume', NOW() - INTERVAL 7 DAY, NOW() - INTERVAL 6 DAY - INTERVAL 22 HOUR, 1),
				(7, 'formateur2@gmail.com', 'azerty', 'Zola', 'Emile',  NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 3 DAY - INTERVAL 22 HOUR, 1),
				(8, 'formateur3@gmail.com', 'azerty', 'Bert', 'Paul',  NOW() - INTERVAL 4 DAY, NOW() - INTERVAL 3 DAY - INTERVAL 22 HOUR, 1);

		INSERT INTO usergroup (group_id, name, created_at, creator_id) VALUES 
				(1, 'Concepteur Développeur Informatique', now(), 6),
				-- (2, 'Tout le monde', now(), 6),
				(2, 'Assistant à Maîtrise d\'Ouvrage', now(), 7),
				(3, 'Big Data Analyst', now(), 7);
				
		INSERT INTO group_member (person_id, group_id, validated_at) VALUES 
				(1, 2, NULL),
				(1, 3, NOW()),
				(2, 2, NULL),
				(2, 3, NOW()),
				(3, 2, NOW()),
				(3, 3, NOW()),
				(4, 3, NOW()),
				(5, 3, NOW());
				
		INSERT INTO quiz_db ( db_name, diagram_path, creation_script_path, description) VALUES
				('banque', 'banque.png', NULL, 'Ceci est un description exacte de la base de données banque'),
				('avions', 'avions.png', NULL, 'Trust nobody not even yourself');
		INSERT INTO sql_quiz (quiz_id, author_id, title, is_public, db_name) VALUES 
				(1, 6, 'Quiz Banque', 1, 'banque'),
			  (2, 7, 'Quiz Avion', 0, 'avions'),
				(3, 6, 'Quiz privé', 1, 'banque');
				
				
		INSERT INTO sql_question (question_id, db_name, question_text, correct_answer, theme_id, author_id, is_public, correct_result) VALUES 
				(1, 'banque', 'Nom et email de tous les clients', 'SELECT nom, email FROM client;', 1, 6, 0, '[Dupont, dupont@interpol.com]\n[Tintin, tintin@herge.be]\n[Haddock, haddock@moulinsart.fr]\n[Castafiore, bianca@scala.it]'),
				(2, 'banque', 'Dates d\'attribution des clients aux commerciaux', 'SELECT date_attribution FROM portefeuille;', 1, 6, 0, '2005-12-23\n2010-04-21\n2015-04-12\n2015-04-12'),
				(3, 'banque', 'Date d\'attribution sans doublon' ,'SELECT DISTINCT date_attribution FROM portefeuille;' ,1, 6, 1, '2005-12-23\n2010-04-21\n2015-04-12'),
				(4, 'banque', 'Longueur du mail des clients (fonction chaine)', 'SELECT email, length(email) FROM client;', 1, 6, 1, '[dupont@interpol.com, 19]\n[tintin@herge.be, 15]\n[haddock@moulinsart.fr, 21]\n[bianca@scala.it, 15]'),
				(5, 'banque', 'Nom du client suivi du commentaire entre parenthèses (utiliser IFNULL)', 'SELECT concat(nom, ifnull(concat(\' (\', commentaire, \')\'), \'\')) FROM client', 1, 6, 1, 'Dupont (Client distrait. Je dirai même plus ...)\nTintin\nHaddock (Grand amateur de Loch Lhomond)\nCastafiore (A flatter. Ne surtout pas faire chanter)'),
				(6, 'banque', 'Solde minimal, moyen, maximal, et écart-type, arrondis à 2 décimales', 'SELECT MIN(solde) AS minimum, FORMAT(AVG(solde), 2) AS moyenne, MAX(solde) AS maximum, FORMAT(STDDEV(solde), 2) AS ecart_type FROM compte;', 7, 6, 1, '[300.00, 1,612.50, 4000.00, 1,221.49]'),
				(7, 'banque', 'Comptes avec n°, nom du client et solde', 'SELECT no_compte, nom AS nom_client, solde FROM compte INNER JOIN client ON compte.no_client = client.no_client;', 3, 6, 1, '[1, Dupont, 1250.00]\n[2, Dupont, 1250.00]\n[3, Tintin, 2590.00]\n[4, Haddock, 2500.00]\n[6, Dupont, 340.00]\n[7, Dupont, 4000.00]\n[8, Dupont, 300.00]\n[9, Dupont, 670.00]'),
				(8, 'banque', 'Comptes ayant un solde au moins égal au solde moyen (imbriquée 1 valeur)', 'SELECT * FROM compte WHERE solde >= (SELECT AVG(solde) FROM compte);', 4, 6, 1, '[3, 2590.00, 2]\n[4, 2500.00, 3]\n[7, 4000.00, 1]'),
				(9, 'avions', 'Date et heure des vols (année sur 4 positions, et heures sur 24 valeurs, pas sur 12)', 'SELECT date_depart, date_format(date_depart, \'%d-%m-%Y\') AS jour,	date_format(date_depart, \'%H:%i:%s\') AS heure FROM vol;', 1, 7, 0, ''),
				(10, 'avions', 'Date et heure des vols, avec le nombre de vols à cette date/heure (GROUP BY)', 'SELECT date_format(date_depart, \'%d-%m-%Y\') AS jour, date_format(date_depart, \'%H:%i:%s\') AS heure, COUNT(*) AS nb_vols FROM vol GROUP BY jour, heure;', 7, 7, 0, ''),
				(11, 'avions', 'Durée minimale, moyenne, maximale, et écart-type, arrondis à 2 décimales,des vols', 'SELECT MIN(TIMEDIFF(date_arrivee, date_depart)) AS minimum, TIME(AVG(TIMESTAMPDIFF(MINUTE, date_arrivee, date_depart))) AS moyenne, MAX(TIMEDIFF(date_arrivee, date_depart)) AS maximum, TIME(STDDEV(TIMESTAMPDIFF(MINUTE, date_arrivee, date_depart))) AS ecart_type FROM vol;', 7, 7, 1, ''),
				(12, 'avions', 'Avions au départ de ORY ou CDG (imbriquée n valeurs)', 'SELECT * FROM avion WHERE id_avion IN (SELECT id_avion FROM vol WHERE id_aeroport_depart IN (\'CDG\', \'ORY\'));', 4, 7, 1, ''),
				(13, 'avions', 'Nombre d\'heures de vol par pilote, avec id et nom (SUM et GROUP BY)', 'SELECT p.id_pilote, nom, ROUND(SUM(TIMESTAMPDIFF(MINUTE, date_depart, date_arrivee))/60) AS nb_heures FROM pilote p INNER JOIN vol ON p.id_pilote = vol.id_pilote GROUP BY p.id_pilote, nom;', 7, 7, 1, ''),
				(14, 'avions', 'Nom et n° des pilotes ayant piloté au moins 2 avions (DISTINCT et HAVING)', 'SELECT p.id_pilote, nom, COUNT(DISTINCT id_avion) AS nb_avions FROM pilote p INNER JOIN vol ON p.id_pilote = vol.id_pilote GROUP BY p.id_pilote, nom HAVING nb_avions >= 2;', 6, 7, 1, ''),
				(15, 'avions', 'Pilotes sans vol (avec HAVING)', 'SELECT p.id_pilote, nom, COUNT(id_avion) AS nb_vols FROM pilote p LEFT OUTER JOIN vol v ON p.id_pilote = v.id_pilote GROUP BY p.id_pilote, nom HAVING nb_vols = 0;', 6, 7, 1, ''),
				(16, 'avions', 'Avions volant le 12/04/2015 (imbriquée n valeurs)', 'SELECT * FROM avion WHERE id_avion IN (SELECT id_avion FROM vol WHERE date(date_depart) = \'2015-04-12\');', 5, 7, 1, '');
				
				INSERT INTO sql_quiz_question(question_id, quiz_id, rank) VALUES 
				(1, 1, 1),
				(2, 1, 2),
				(3, 1, 3),
				(4, 1, 4),
				(5, 1, 5),
				(6, 1, 6),
				(7, 1, 7),
				(8, 1, 8),
				(9, 2, 1),
				(10, 2, 2),
				(11, 2, 3),
				(12, 2, 4),
				(13, 2, 5),
				(14, 2, 6),
				(15, 2, 7),
				(16, 2, 8);
				
				
				INSERT INTO evaluation (evaluation_id, group_id, scheduled_at, ending_at, corrected_at, quiz_id) VALUES 
				(1,  1, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 1 DAY, NOW() - INTERVAL 1 DAY, 1),
				(2, 1, NOW() - INTERVAL 2 DAY, NOW() + INTERVAL 2 DAY, NULL, 1),
				(5, 2, NOW() + INTERVAL 3 DAY, NOW() + INTERVAL 12 DAY, NULL, 1);
				
				
				
				
				
				INSERT INTO training (training_id, quiz_id, trainee_id, started_at,ended_at) VALUES 
				(1, 1, 1, NOW() - INTERVAL 3 DAY,null),
				(2, 2, 2, NOW() - INTERVAL 5 DAY,null),
				(3, 1, 3, NOW() - INTERVAL 7 DAY,now()),
				(4, 1, 4, NOW() - INTERVAL 1 DAY,now()),
				(5, 2, 1, NOW(),null);
			
		COMMIT;
	END;
END $$