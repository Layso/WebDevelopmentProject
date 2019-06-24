DELIMITER $$
DROP TRIGGER IF EXISTS evaluation_after_insert $$
CREATE TRIGGER evaluation_after_insert
AFTER INSERT ON evaluation
FOR EACH ROW
BEGIN
  DECLARE v_person INT;
  DECLARE termine BOOLEAN DEFAULT FALSE;
  DECLARE person_list CURSOR FOR 
    SELECT person_id 
    FROM group_member 
    WHERE group_id = NEW.group_id and validated_at IS NOT NULL;
  OPEN person_list;
  BEGIN
    DECLARE EXIT HANDLER FOR NOT FOUND SET termine = TRUE;
    REPEAT
      FETCH person_list INTO v_person;
      INSERT INTO sheet(trainee_id, evaluation_id)	
      VALUES (v_person, NEW.evaluation_id);
    UNTIL termine END REPEAT;
  END;
  CLOSE person_list;
END$$

DROP TRIGGER IF EXISTS group_member_after_insert $$
CREATE TRIGGER group_member_after_insert
AFTER INSERT ON group_member
FOR EACH ROW
BEGIN
	DECLARE v_training INT;
	DECLARE termine BOOLEAN DEFAULT FALSE;
	DECLARE eval_list CURSOR FOR SELECT evaluation_id FROM evaluation WHERE group_id = NEW.group_id;
	OPEN eval_list;
	BEGIN
		DECLARE EXIT HANDLER FOR NOT FOUND SET termine = TRUE;
		REPEAT
			FETCH eval_list INTO v_training;
			INSERT INTO sheet (trainee_id, evaluation_id) VALUES (NEW.person_id, v_training);
		UNTIL termine END REPEAT;
	END;
	CLOSE eval_list;
END$$

DROP TRIGGER IF EXISTS sheet_after_insert $$
CREATE TRIGGER sheet_after_insert
AFTER INSERT ON sheet
FOR EACH ROW
BEGIN
  INSERT INTO sheet_answer(question_id, trainee_id, evaluation_id)
  SELECT question_id, NEW.trainee_id, NEW.evaluation_id
  FROM sql_quiz_question
  WHERE quiz_id IN
  (
    SELECT quiz_id
    FROM evaluation
    WHERE evaluation_id = NEW.evaluation_id
  ); 
END$$

DROP TRIGGER IF EXISTS before_insert_question_in_quiz $$
CREATE TRIGGER before_insert_question_in_quiz
AFTER INSERT ON sql_quiz_question
FOR EACH ROW
BEGIN
  DECLARE name1 varchar(45);
  DECLARE name2 varchar(45);
  SELECT quiz_db.db_name INTO name1 
  FROM 
    quiz_db 
      INNER JOIN 
    sql_question AS sq ON quiz_db.db_name = sq.db_name 
  WHERE sq.question_id = NEW.question_id;
	SELECT quiz_db.db_name INTO name2 
  FROM 
    quiz_db
      INNER JOIN 
    sql_quiz AS sq ON quiz_db.db_name = sq.db_name 
    WHERE sq.quiz_id = NEW.quiz_id;
  IF (name1 != name2) THEN
    INSERT INTO sql_quiz_question (question_id, quiz_id, rank)
    VALUES(NEW.question_id, NEW.quiz_id, NEW.rank);
  END IF;
END $$


DROP TRIGGER IF EXISTS training_after_insert $$
CREATE TRIGGER training_after_insert
AFTER INSERT ON training
FOR EACH ROW
BEGIN
  INSERT INTO training_answer(question_id, training_id)
  SELECT question_id, NEW.training_id
  FROM sql_quiz_question
  WHERE quiz_id IN
  (
    SELECT quiz_id
    FROM training
    WHERE training_id = NEW.training_id
  ); 
END$$

DROP USER  sql_skills_v2_user@'localhost'  $$
CREATE USER IF NOT EXISTS sql_skills_v2_user@'localhost' IDENTIFIED BY 'sql_skills_v2_pwd' $$
GRANT ALL ON sql_skills_v2.* TO sql_skills_v2_user@localhost $$




CALL sql_skills_v2_reset() $$	