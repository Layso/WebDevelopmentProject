DELIMITER §
DROP SCHEMA IF EXISTS bank §
CREATE SCHEMA IF NOT EXISTS bank DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci §
USE bank §

CREATE TABLE IF NOT EXISTS client (
  client_id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  comment VARCHAR(255),
  PRIMARY KEY (client_id),
	UNIQUE INDEX email_UNIQUE (email ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8 §


CREATE TABLE IF NOT EXISTS salesperson (
  salesperson_id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  PRIMARY KEY (salesperson_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8 §


CREATE TABLE IF NOT EXISTS account (
  account_id INT(11) NOT NULL AUTO_INCREMENT,
  balance DECIMAL(10,2) NOT NULL,
  client_id INT(11) NOT NULL,
  PRIMARY KEY (account_id),
  CONSTRAINT fk_account_client
    FOREIGN KEY (client_id)
    REFERENCES client (client_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8 §

CREATE TABLE IF NOT EXISTS portfolio (
  salesperson_id INT(11) NOT NULL,
  client_id INT(11) NOT NULL,
  set_to_at DATE NOT NULL,
  PRIMARY KEY (client_id, set_to_at),
  CONSTRAINT portfolio_salesperson
    FOREIGN KEY (salesperson_id)
    REFERENCES salesperson (salesperson_id),
  CONSTRAINT portfolio_client
    FOREIGN KEY (client_id)
    REFERENCES client (client_id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8 §




DROP PROCEDURE IF EXISTS bank_reset §

CREATE PROCEDURE bank_reset()
BEGIN
  -- Disable foreign key constraint checks
  SET FOREIGN_KEY_CHECKS = 0;
  -- Empty tables and set their auto-incrément to 1
  TRUNCATE TABLE client;
  TRUNCATE TABLE salesperson;
  TRUNCATE TABLE account;
  TRUNCATE TABLE portfolio;
  -- Enable again foreign key constraint checks
  SET FOREIGN_KEY_CHECKS = 1;

  BEGIN
    -- Catch clause
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
      -- Rollback the transaction
      ROLLBACK;
			-- Display the cause
      SHOW ERRORS;
    END;  
    START TRANSACTION;
    INSERT INTO client (client_id, name, email, comment) VALUES
    (1, 'Dupont', 'dupont@interpol.com', 'Customer distracted. 
Je dirai même plus ... I will even say more ... '),
    (2, 'Tintin', 'tintin@herge.be', NULL),
    (3, 'Haddock', 'haddock@moulinsart.fr', ' Very fond of Loch Lhomond'),
    (4, 'Castafiore', 'bianca@scala.it', 'To flatter. Do not ask her to sing!');

    INSERT INTO salesperson (salesperson_id, name) VALUES
    (1, 'Lampion'),
    (2, 'de Oliveira'),
    (3, 'Rastapopoulos');

    INSERT INTO account (account_id, balance, client_id) VALUES
    (1, '1000.00', 1),
    (2, '1500.00', 1),
    (3, '2000.00', 2),
    (4, '2500.00', 3);

    INSERT INTO portfolio (salesperson_id, client_id, set_to_at) VALUES
    (1, 1, '2005-12-23'),
    (1, 2, '2010-04-21'),
    (1, 3, '2015-04-12'),
    (2, 1, '2015-04-12');
    COMMIT;
  END;
END §

CALL bank_reset();