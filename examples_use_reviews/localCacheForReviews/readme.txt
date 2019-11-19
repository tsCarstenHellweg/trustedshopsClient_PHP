in order to run the cron-job you need to do a few steps first

1) CREATE THE table!

Please run these sql-commands on the database. Please note: I use mysql/MariaDB dialect
if you use a different rdbms, please modify accordingly

DROP TABLE IF EXISTS trustedShopsReviewCache;
CREATE TABLE trustedShopsReviewCache
(
   email VARCHAR(255),
   orderRef VARCHAR(255),
   PRIMARY KEY( email, orderRef )
);

CREATE INDEX _trustedShopsReviewCache_emailIndex ON trustedShopsReviewCache(email);
CREATE INDEX _trustedShopsReviewCache_orderIndex ON trustedShopsReviewCache(orderRef);


2) MODIFY THE php-file!
  a) delete my if(file_exists) part for the config.
  b) uncomment the function getTrustedShopsConfig(), that is commented out and fill out the values
  c) do the actual inserts on your database(modify the sql statement, if you use a different rdbms)

3) after all is set and done, run the cronJob once to fill the table

4) you can now determine, if a user has not reviewed an order by executing this command
   (again, i dont know the exact name and structure of the "myOrders" table, so you may need to modify that)
SELECT *
FROM myOrders o
WHERE NOT EXISTS
(
 SELECT 1
 FROM trustedShopsReviewCache tsCache
 WHERE tsCache.email = o.email
   AND tsCache.orderRef = o.orderRef
);
