/* Name and email of all clients */
SELECT name, email
FROM client;

/* Customer assignment (to a salesperson) dates */
SELECT set_to_at
FROM portfolio;

/* Customer assignment (to a salesperson) dates, without duplicate */
SELECT DISTINCT set_to_at
FROM portfolio;

/* Length of client email (string function) */
SELECT email, length(email)
FROM client;

/* Name of clients followed by => email */
SELECT concat(name, ' => ', email)
FROM client;

SELECT concat_ws(' => ', name, email) AS client 
FROM client;

/* Client name followed by comment in parenthesis (use IFNULL) */
SELECT ifnull(concat(name, ' (', comment, ')'), name)
from client;

/* Assignment dates, without duplicate (use DISTINCT) */
SELECT DISTINCT set_to_at
FROM portfolio;

/* Assignment dates of customers to salesperson, and number of days 
and months since this assignment */
SELECT set_to_at, sysdate(), 
datediff(sysdate(), set_to_at) AS days,
timestampdiff(MONTH, set_to_at, sysdate()) as months
FROM portfolio;

/* Assignment dates, in the form dd/mm/yyyy  */
SELECT date_format(set_to_at, '%d/%m/%y') as date
FROM portfolio;

/* Number of customers, and number of comments */
SELECT count(*) AS clients_number, count(comment) AS nb_comments
FROM client;

/* Number of accounts, and different clients in the account table */
SELECT count(client_id) as accounts_number, count(distinct client_id) as clients_number
from account;

/* Total amount stored in the bank (sum of balances) */
SELECT SUM(balance) AS sum_of_all
FROM account;

/* Minimum, average, maximum and standard deviation of amounts, 
rounded to two decimal places */
SELECT 
  MIN(balance) AS minimum, 
  FORMAT(AVG(balance), 2) AS average, 
  MAX(balance) AS maximum, 
  FORMAT(STDDEV(balance), 2) AS std_dev
FROM account;

/* Number of customers per salesperson (group by) */
SELECT salesperson_id, count(client_id) AS clients_number
FROM portfolio
GROUP BY salesperson_id;

/* Portfolio sorted by salesperson number and customer number */
SELECT *
FROM portfolio
ORDER BY salesperson_id, client_id;

/* Account number, customer number and balance of accounts whose balance 
is at least equal to 2000 € */
SELECT account_id, client_id, balance
FROM account
WHERE balance >= 2000;

/* Same for balance accounts between 1500 and 2000 (bounds included) */
SELECT account_id, client_id, balance
FROM account
WHERE balance BETWEEN 1500 AND 2000;

/* Customers whose names begin with D, whether in uppercase or lowercase */
SELECT *
FROM client
WHERE lower(name) LIKE 'd%';

/* Customers whose names contain D, uppercase or lowercase */
SELECT *
FROM client
WHERE lower(name) LIKE '%d%';

/* Customers without comment (IS NULL) */
SELECT *
FROM client
WHERE comment IS NULL;

/* Accounts whose customer number is in list 1, 3 (IN) */
SELECT *
FROM account
WHERE client_id in (1, 3);

/* Clients of names beginning with T or H */
SELECT *
FROM client
WHERE substr(name, 1, 1) IN ('T', 'H');


/* -----------------------------------------------------------------
Natural and nested joins
----------------------------------------------------------------- */
/* Accounts with account number, customer name and balance */
SELECT account_id, name AS name_client, balance
FROM 
	account 
		INNER JOIN 
	client ON account.client_id = client.client_id;

/* Name and email of customers, with the number (id) of salespersons having 
managed their accounts, and their assignment date */
SELECT c.name, c.email, p.salesperson_id, set_to_at
FROM 
	client c 
		INNER JOIN 
	portfolio p ON c.client_id = p.client_id
ORDER BY name;

/* Names of salespersons and their associated clients, and assignment date */
SELECT s.name AS name_salesperson, cl.name AS name_client, set_to_at
FROM 
	salesperson s 
		INNER JOIN
	portfolio p ON s.salesperson_id = p.salesperson_id
		INNER JOIN 
	client cl ON p.client_id = cl.client_id
ORDER BY name_salesperson, name_client;


/* Number and assignment date of the salespersons dealing with the customer Dupont */
SELECT salesperson_id, set_to_at
FROM portfolio p INNER JOIN client c ON p.client_id = c.client_id
WHERE name = 'Dupont';

/* Same with nested query with 1 value */
SELECT salesperson_id, set_to_at
FROM portfolio
WHERE client_id =
(
  SELECT client_id
  FROM client
  WHERE name = 'Dupont'
);  

/* Account information of the client named Dupont (nested query 1 value) */
SELECT *
FROM account
WHERE client_id =
(
  SELECT client_id
  FROM client
  WHERE name = 'Dupont'
);  

/* Salesperson name, assignment date and name of their clients having no comment */
SELECT s.name, p.set_to_at, c.name
FROM 
	client c 
		INNER JOIN 
	portfolio p ON c.client_id = p.client_id
		INNER JOIN 
	salesperson s ON p.salesperson_id = s.salesperson_id
WHERE c.comment IS NULL;

/* Client number, name and email of customers managed by salesperson number 1 */
SELECT c.client_id, email
FROM 
	client c 
		INNER JOIN
	portfolio p ON c.client_id = p.client_id
WHERE salesperson_id = 1;

/* Same with nested query with n values */
SELECT * FROM client
WHERE client_id IN
(
  SELECT client_id
  FROM portfolio
  WHERE salesperson_id = 1
);

/* Information of customers managed by the salesperson 
named Lampion (nested query n values) */
SELECT * 
FROM client
WHERE client_id IN
(
  SELECT client_id
  FROM portfolio
  WHERE salesperson_id =
  (
    SELECT salesperson_id
    FROM salesperson
    WHERE name='Lampion'
  )
);

/* Data of the salesmen who managed the client 1 (nested query with n values) */
SELECT *
FROM salesperson
WHERE salesperson_id IN
(
  SELECT salesperson_id
  FROM portfolio
  WHERE client_id = 1
);

/* Accounts with a balance at least equal to the average balance (nested 1 value) */
SELECT *
FROM account
WHERE balance >=
(
  SELECT AVG(balance)
  FROM account
);



/* Balance and salesperson number of the Dupont client accounts (nested with 1 value) */
SELECT account_id, balance, salesperson_id
FROM 
  account c 
		INNER JOIN 
	portfolio p ON c.client_id = p.client_id
WHERE c.client_id = 
(
	SELECT client_id
	FROM client
	WHERE name='Dupont'
);

/* Number and assignment date of the salespersons who managed Dupont (nested 1 value) */
SELECT salesperson_id, set_to_at
FROM portfolio
WHERE client_id =
(
  SELECT client_id
  FROM client
  WHERE name = 'Dupont'
);  

/* Count of clients having same salesperson and assignment date as Dupont (nested with several columns) */
SELECT DISTINCT client_id
FROM portfolio
WHERE (salesperson_id, set_to_at) IN
(
  SELECT salesperson_id, set_to_at
  FROM portfolio
  WHERE client_id =
  (
    SELECT client_id
    FROM client
    WHERE name = 'Dupont'
  )
);

/* Customers with their largest balance account (nested with multiple columns) */
SELECT cl.client_id, name, email, balance
FROM 
  client cl 
    INNER JOIN 
  account a ON cl.client_id=a.client_id
WHERE (cl.client_id, balance) IN
(
  SELECT cl.client_id, MAX(balance)
  FROM 
		client cl 
			INNER JOIN 
		account c ON cl.client_id=c.client_id
  GROUP BY cl.client_id
);

/* Customers with the number of their last assigned salesperson 
(nested with multiple columns) */
SELECT *
FROM 
	client cl 
		INNER JOIN 
	account c ON cl.client_id = c.client_id
WHERE (c.client_id, balance) IN
(
	SELECT client_id, MAX(balance)
	FROM account
	GROUP BY client_id
);

/* Customers with the salesperson number of their last salesperson assigned */
SELECT *
FROM 
	client c 
    INNER JOIN 
  portfolio p	ON c.client_id = p.client_id
WHERE (p.client_id, set_to_at) IN
(
	SELECT client_id, MAX(set_to_at)
	FROM portfolio
	GROUP BY client_id
);

/* Customers with the name of their last salesperson assigned  */
SELECT 
	c.client_id, c.name AS name_client, email, comment, 
	s.salesperson_id, s.name AS name_salesperson, set_to_at
FROM 
	client c 
		INNER JOIN 
	portfolio p	ON c.client_id = p.client_id
		INNER JOIN
	salesperson s ON p.salesperson_id = s.salesperson_id
WHERE (p.client_id, set_to_at) IN
(
	SELECT client_id, MAX(set_to_at)
	FROM portfolio
	GROUP BY client_id
);


/* Number and name of the salespersons, with the number of customer they have managed */
SELECT s.salesperson_id, s.name, p.client_id
FROM 
  portfolio p
		INNER JOIN 
	salesperson s ON p.salesperson_id = s.salesperson_id;

/* Customer number, name and total balance (all accounts combined) 
of customers (GROUP BY) */
SELECT name, SUM(balance) AS balance_total
FROM 
  account c 
		INNER JOIN 
	client cl ON c.client_id = cl.client_id
GROUP BY name;

/* Minimum, average and maximum balance of accounts per customer (GROUP BY) */
SELECT 
  MIN(balance_cumule) AS total_minimum, 
  AVG(balance_cumule) AS total_moyen,
  MAX(balance_cumule) AS total_maximum
FROM (
  SELECT client_id, SUM(balance) AS balance_cumule
  FROM account
  GROUP BY client_id
) t1;

/* Number, name and total balance of customers with total balance at least 
equal to the average of total balance by cistomer
 */
SELECT name, SUM(balance) AS balance_total
FROM 
  account a 
		INNER JOIN 
	client cl ON a.client_id = cl.client_id
GROUP BY name
HAVING balance_total >=
(
	SELECT AVG(balance_cumule)
	FROM (
		SELECT SUM(balance) AS balance_cumule
		FROM account
		GROUP BY client_id
	) t1
);

/* Number and name of the salespersons, with the number of customers 
they have managed (OUTER JOIN) */
SELECT s.salesperson_id, name, COUNT(client_id) AS clients_number
FROM
	salesperson s
		LEFT OUTER JOIN
	portfolio p ON s.salesperson_id = p.salesperson_id
GROUP BY s.salesperson_id, name;

/* Customer number, name, number of accounts and total balance (OUTER JOIN) */
SELECT cl.client_id, name, COUNT(account_id) AS accounts_number, IFNULL(SUM(balance), 0) AS balance_total
FROM
	client cl
		LEFT OUTER JOIN 
	account a ON cl.client_id = a.client_id
GROUP BY cl.client_id, name;

/* Salespersons without customer (with NOT EXISTS) */
SELECT *
FROM salesperson s
WHERE NOT EXISTS
(
	SELECT salesperson_id
	FROM portfolio p
	WHERE p.salesperson_id = s.salesperson_id
);

/* Salespersons who managed all assigned clients (with aggregation) */
SELECT s.salesperson_id, name
FROM 
	salesperson s 
		INNER JOIN 
	portfolio p ON s.salesperson_id=p.salesperson_id
GROUP BY s.salesperson_id, name
HAVING COUNT(client_id) =
(
  SELECT COUNT(DISTINCT client_id)
  FROM portfolio
 );
 
/* Salespersons having managed all the clients assigned (with NOT EXISTS) 
= salespersons for which there is no assigned customer for which there is 
no association with this salesperson
 */
SELECT salesperson_id, name
FROM salesperson s
WHERE NOT EXISTS
(
	SELECT client_id
	FROM portfolio p
	WHERE NOT EXISTS
	(
		SELECT salesperson_id
		FROM portfolio p1
		WHERE 
			p1.client_id = p.client_id
			AND p1.salesperson_id = s.salesperson_id
	)
);
