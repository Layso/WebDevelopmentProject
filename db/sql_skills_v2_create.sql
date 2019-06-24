-- sql_skills_v2 generation

DROP SCHEMA IF EXISTS sql_skills_v2 ;

CREATE SCHEMA IF NOT EXISTS sql_skills_v2 
DEFAULT CHARACTER SET utf8 ;

USE sql_skills_v2 ;

CREATE TABLE IF NOT EXISTS quiz_db (
  db_name VARCHAR(45) NOT NULL,
  diagram_path VARCHAR(255) NULL DEFAULT NULL,
  creation_script_path VARCHAR(255) NULL DEFAULT NULL,
  description TEXT NULL DEFAULT NULL,
  PRIMARY KEY (db_name))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS person (
  person_id INT(11) NOT NULL AUTO_INCREMENT,
  email VARCHAR(45) NOT NULL,
  pwd VARCHAR(255) NOT NULL,
  name VARCHAR(45) NOT NULL,
  first_name VARCHAR(45) NOT NULL,
  is_trainer TINYINT(1) NOT NULL,
  token VARCHAR(45) NULL DEFAULT NULL,
  created_at DATETIME NOT NULL,
  validated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (person_id),
  UNIQUE INDEX person_email_UNIQUE (email ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS sql_quiz (
  quiz_id INT(11) NOT NULL AUTO_INCREMENT,
  author_id INT(11) NOT NULL,
  title VARCHAR(45) NOT NULL,
  is_public TINYINT(1) NOT NULL DEFAULT 0,
  db_name VARCHAR(45) NOT NULL,
  PRIMARY KEY (quiz_id),
  INDEX fk_sql_quiz_quiz_db_idx (db_name ASC),
  INDEX fk_sql_quiz_person_idx (author_id ASC),
  CONSTRAINT fk_sql_quiz_quiz_db
    FOREIGN KEY (db_name)
    REFERENCES quiz_db (db_name),
  CONSTRAINT fk_sql_quiz_person
    FOREIGN KEY (author_id)
    REFERENCES person (person_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS usergroup (
  group_id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  creator_id INT(11) NOT NULL,
  created_at DATETIME NOT NULL,
  closed_at DATETIME NULL,
  PRIMARY KEY (group_id),
  UNIQUE INDEX name_UNIQUE (name ASC),
  INDEX fk_usergroup_person_idx (creator_id ASC),
  CONSTRAINT fk_usergroup_person
    FOREIGN KEY (creator_id)
    REFERENCES person (person_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS evaluation (
  evaluation_id INT(11) NOT NULL AUTO_INCREMENT,
  group_id INT(11) NOT NULL,
  quiz_id INT(11) NOT NULL,
  scheduled_at DATETIME NOT NULL,
  ending_at DATETIME NOT NULL,
  corrected_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (evaluation_id),
  INDEX fk_exam_class_idx (group_id ASC),
  INDEX fk_evaluation_sql_quiz_idx (quiz_id ASC),
  CONSTRAINT fk_evaluation_sql_quiz
    FOREIGN KEY (quiz_id)
    REFERENCES sql_quiz (quiz_id),
  CONSTRAINT fk_evaluation_usergroup
    FOREIGN KEY (group_id)
    REFERENCES usergroup (group_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



CREATE TABLE IF NOT EXISTS group_member (
  person_id INT(11) NOT NULL,
  group_id INT(11) NOT NULL,
  validated_at DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (person_id, group_id),
  INDEX fk_group_member_usergroup_idx (group_id ASC),
  INDEX fk_group_member_person_idx (person_id ASC),
  CONSTRAINT fk_group_member_usergroup
    FOREIGN KEY (group_id)
    REFERENCES usergroup (group_id),
  CONSTRAINT fk_group_member_person
    FOREIGN KEY (person_id)
    REFERENCES person (person_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS sheet (
  trainee_id INT(20) NOT NULL,
  evaluation_id INT(11) NOT NULL,
  started_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  ended_at DATETIME NULL DEFAULT NULL COMMENT 'when the trainee terminates his sheet',
  corrected_at DATETIME NULL DEFAULT NULL COMMENT 'validated by the trainer at',
  PRIMARY KEY (trainee_id, evaluation_id),
  INDEX fk_sheet_person_idx (trainee_id ASC),
  INDEX fk_sheet_evaluation_idx (evaluation_id ASC),
  CONSTRAINT fk_sheet_evaluation
    FOREIGN KEY (evaluation_id)
    REFERENCES evaluation (evaluation_id),
  CONSTRAINT fk_sheet_person
    FOREIGN KEY (trainee_id)
    REFERENCES person (person_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS theme (
  theme_id INT(11) NOT NULL AUTO_INCREMENT,
  label VARCHAR(255) NOT NULL,
  PRIMARY KEY (theme_id),
  UNIQUE INDEX theme_label_UNIQUE (label ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS sql_question (
  question_id INT(20) NOT NULL AUTO_INCREMENT,
  db_name VARCHAR(45) NOT NULL,
  question_text VARCHAR(255) NOT NULL,
  correct_answer TEXT NOT NULL,
  correct_result TEXT NULL DEFAULT NULL,
  theme_id INT(11) NOT NULL,
  author_id INT(11) NOT NULL,
  is_public TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (question_id),
  INDEX fk_sql_question_theme_idx (theme_id ASC),
  INDEX fk_sql_question_quiz_db_idx (db_name ASC),
  INDEX fk_sql_question_person_idx (author_id ASC),
  CONSTRAINT fk_sql_question_quiz_db
    FOREIGN KEY (db_name)
    REFERENCES quiz_db (db_name),
  CONSTRAINT fk_sql_question_theme
    FOREIGN KEY (theme_id)
    REFERENCES theme (theme_id),
  CONSTRAINT fk_sql_question_person
    FOREIGN KEY (author_id)
    REFERENCES person (person_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS sheet_answer (
  question_id INT(20) NOT NULL,
  trainee_id INT(20) NOT NULL,
  evaluation_id INT(11) NOT NULL,
  answer TEXT NULL DEFAULT NULL COMMENT 'query written by the trainee',
  result TEXT NULL DEFAULT NULL,
  given_at DATETIME NULL,
  gives_correct_result TINYINT(1) NULL DEFAULT NULL COMMENT 'validated by the trainer (null if not yet validated or invalidated)',
  PRIMARY KEY (question_id, trainee_id, evaluation_id),
  INDEX fk_answer_question_idx (question_id ASC),
  INDEX fk_sql_answer_sheet_idx (evaluation_id ASC, trainee_id ASC),
  CONSTRAINT fk_answer_question
    FOREIGN KEY (question_id)
    REFERENCES sql_question (question_id),
  CONSTRAINT fk_sql_answer_sheet
    FOREIGN KEY (evaluation_id , trainee_id)
    REFERENCES sheet (evaluation_id , trainee_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS sql_quiz_question (
  question_id INT(20) NOT NULL,
  quiz_id INT(11) NOT NULL,
  rank INT(11) NOT NULL COMMENT 'rank of the question in the sql_quiz',
  PRIMARY KEY (question_id, quiz_id),
  INDEX fk_sql_quiz_question_to_quiz_idx (question_id ASC),
  INDEX fk_sql_quiz_question_to_question_idx (quiz_id ASC),
  CONSTRAINT fk_sql_quiz_question_to_quiz
    FOREIGN KEY (quiz_id)
    REFERENCES sql_quiz (quiz_id),
  CONSTRAINT fk_sql_quiz_question_to_question
    FOREIGN KEY (question_id)
    REFERENCES sql_question (question_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS training (
  training_id INT NOT NULL AUTO_INCREMENT,
  trainee_id INT(11) NOT NULL,
  quiz_id INT(11) NOT NULL,
  started_at DATETIME NOT NULL DEFAULT now(),
  ended_at DATETIME NULL,
  PRIMARY KEY (training_id),
  INDEX fk_training_person_idx (trainee_id ASC),
  INDEX fk_training_sql_quiz_idx (quiz_id ASC),
  CONSTRAINT fk_training_person
    FOREIGN KEY (trainee_id)
    REFERENCES person (person_id),
  CONSTRAINT fk_training_sql_quiz
    FOREIGN KEY (quiz_id)
    REFERENCES sql_quiz (quiz_id))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS training_answer (
  training_id INT NOT NULL,
  question_id INT NOT NULL,
  answer TEXT NULL,
  result TEXT NULL,
  PRIMARY KEY (training_id, question_id),
  INDEX fk_training_answer_training_idx (training_id ASC),
  INDEX fk_training_answer_sql_question_idx (question_id ASC),
  CONSTRAINT fk_training_answer_training
    FOREIGN KEY (training_id)
    REFERENCES training (training_id),
  CONSTRAINT fk_training_answer_sql_question
    FOREIGN KEY (question_id)
    REFERENCES sql_question (question_id))
ENGINE = InnoDB;
