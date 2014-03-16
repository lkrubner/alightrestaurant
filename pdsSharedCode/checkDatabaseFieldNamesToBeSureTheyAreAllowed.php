<?php



function checkDatabaseFieldNamesToBeSureTheyAreAllowed($name=false) {
	// 01-22-07 - a common error is for people using the scaffolding to put in a word
	// that is actually reserved by MySql. An example which I saw on the CACVB site was
	// this: 
	//
	//	mastercard
	//	visa
	//	discover
	//	check
	//	cash
	//
	// It is an easy mistake to make, but the word "check" is actually reserved by MySql, and
	// so the above, when I tried to use "check" as a field name in a MySql database, caused
	// an error. So I'm adding this check to the scaffolding system. Below is a list of all
	// reserved keywords which I got off the MySql site. We will check to see if $name
	// equals any of these words. 

	global $controller; 

	if ($name) {		
		$arrayOfReservedMysqlKeywords = array();
		
		$arrayOfReservedMysqlKeywords[] = "ADD";
		$arrayOfReservedMysqlKeywords[] = "ALL";
		$arrayOfReservedMysqlKeywords[] = "ALTER";
		$arrayOfReservedMysqlKeywords[] = "ANALYZE";
		$arrayOfReservedMysqlKeywords[] = "AND";
		$arrayOfReservedMysqlKeywords[] = "AS";
		$arrayOfReservedMysqlKeywords[] = "ASC";
		$arrayOfReservedMysqlKeywords[] = "ASENSITIVE";
		$arrayOfReservedMysqlKeywords[] = "BEFORE";
		$arrayOfReservedMysqlKeywords[] = "BETWEEN";
		$arrayOfReservedMysqlKeywords[] = "BIGINT";
		$arrayOfReservedMysqlKeywords[] = "BINARY";
		$arrayOfReservedMysqlKeywords[] = "BLOB";
		$arrayOfReservedMysqlKeywords[] = "BOTH";
		$arrayOfReservedMysqlKeywords[] = "BY";
		$arrayOfReservedMysqlKeywords[] = "CALL";
		$arrayOfReservedMysqlKeywords[] = "CASCADE";
		$arrayOfReservedMysqlKeywords[] = "CASE";
		$arrayOfReservedMysqlKeywords[] = "CHANGE";
		$arrayOfReservedMysqlKeywords[] = "CHAR";
		$arrayOfReservedMysqlKeywords[] = "CHARACTER";
		$arrayOfReservedMysqlKeywords[] = "CHECK";
		$arrayOfReservedMysqlKeywords[] = "COLLATE";
		$arrayOfReservedMysqlKeywords[] = "COLUMN";
		$arrayOfReservedMysqlKeywords[] = "CONDITION";
		$arrayOfReservedMysqlKeywords[] = "CONNECTION";
		$arrayOfReservedMysqlKeywords[] = "CONSTRAINT";
		$arrayOfReservedMysqlKeywords[] = "CONTINUE";
		$arrayOfReservedMysqlKeywords[] = "CONVERT";
		$arrayOfReservedMysqlKeywords[] = "CREATE";
		$arrayOfReservedMysqlKeywords[] = "CROSS";
		$arrayOfReservedMysqlKeywords[] = "CURRENT_DATE";
		$arrayOfReservedMysqlKeywords[] = "CURRENT_TIME";
		$arrayOfReservedMysqlKeywords[] = "CURRENT_TIMESTAMP";
		$arrayOfReservedMysqlKeywords[] = "CURRENT_USER";
		$arrayOfReservedMysqlKeywords[] = "CURSOR";
		$arrayOfReservedMysqlKeywords[] = "DATABASE";
		$arrayOfReservedMysqlKeywords[] = "DATABASES";
		$arrayOfReservedMysqlKeywords[] = "DAY_HOUR";
		$arrayOfReservedMysqlKeywords[] = "DAY_MICROSECOND";
		$arrayOfReservedMysqlKeywords[] = "DAY_MINUTE";
		$arrayOfReservedMysqlKeywords[] = "DAY_SECOND";
		$arrayOfReservedMysqlKeywords[] = "DEC";
		$arrayOfReservedMysqlKeywords[] = "DECIMAL";
		$arrayOfReservedMysqlKeywords[] = "DECLARE";
		$arrayOfReservedMysqlKeywords[] = "DEFAULT";
		$arrayOfReservedMysqlKeywords[] = "DELAYED";
		$arrayOfReservedMysqlKeywords[] = "DELETE";
		$arrayOfReservedMysqlKeywords[] = "DESC";
		$arrayOfReservedMysqlKeywords[] = "DESCRIBE";
		$arrayOfReservedMysqlKeywords[] = "DETERMINISTIC";
		$arrayOfReservedMysqlKeywords[] = "DISTINCT";
		$arrayOfReservedMysqlKeywords[] = "DISTINCTROW";
		$arrayOfReservedMysqlKeywords[] = "DIV";
		$arrayOfReservedMysqlKeywords[] = "DOUBLE";
		$arrayOfReservedMysqlKeywords[] = "DROP";
		$arrayOfReservedMysqlKeywords[] = "DUAL";
		$arrayOfReservedMysqlKeywords[] = "EACH";
		$arrayOfReservedMysqlKeywords[] = "ELSE";
		$arrayOfReservedMysqlKeywords[] = "ELSEIF";
		$arrayOfReservedMysqlKeywords[] = "ENCLOSED";
		$arrayOfReservedMysqlKeywords[] = "ESCAPED";
		$arrayOfReservedMysqlKeywords[] = "EXISTS";
		$arrayOfReservedMysqlKeywords[] = "EXIT";
		$arrayOfReservedMysqlKeywords[] = "EXPLAIN";
		$arrayOfReservedMysqlKeywords[] = "FALSE";
		$arrayOfReservedMysqlKeywords[] = "FETCH";
		$arrayOfReservedMysqlKeywords[] = "FLOAT";
		$arrayOfReservedMysqlKeywords[] = "FLOAT4";
		$arrayOfReservedMysqlKeywords[] = "FLOAT8";
		$arrayOfReservedMysqlKeywords[] = "FOR";
		$arrayOfReservedMysqlKeywords[] = "FORCE";
		$arrayOfReservedMysqlKeywords[] = "FOREIGN";
		$arrayOfReservedMysqlKeywords[] = "FROM";
		$arrayOfReservedMysqlKeywords[] = "FULLTEXT";
		$arrayOfReservedMysqlKeywords[] = "GRANT";
		$arrayOfReservedMysqlKeywords[] = "GROUP";
		$arrayOfReservedMysqlKeywords[] = "HAVING";
		$arrayOfReservedMysqlKeywords[] = "HIGH_PRIORITY";
		$arrayOfReservedMysqlKeywords[] = "HOUR_MICROSECOND";
		$arrayOfReservedMysqlKeywords[] = "HOUR_MINUTE";
		$arrayOfReservedMysqlKeywords[] = "HOUR_SECOND";
		$arrayOfReservedMysqlKeywords[] = "IF";
		$arrayOfReservedMysqlKeywords[] = "IGNORE";
		$arrayOfReservedMysqlKeywords[] = "IN";
		$arrayOfReservedMysqlKeywords[] = "INDEX";
		$arrayOfReservedMysqlKeywords[] = "INFILE";
		$arrayOfReservedMysqlKeywords[] = "INNER";
		$arrayOfReservedMysqlKeywords[] = "INOUT";
		$arrayOfReservedMysqlKeywords[] = "INSENSITIVE";
		$arrayOfReservedMysqlKeywords[] = "INSERT";
		$arrayOfReservedMysqlKeywords[] = "INT";
		$arrayOfReservedMysqlKeywords[] = "INT1";
		$arrayOfReservedMysqlKeywords[] = "INT2";
		$arrayOfReservedMysqlKeywords[] = "INT3";
		$arrayOfReservedMysqlKeywords[] = "INT4";
		$arrayOfReservedMysqlKeywords[] = "INT8";
		$arrayOfReservedMysqlKeywords[] = "INTEGER";
		$arrayOfReservedMysqlKeywords[] = "INTERVAL";
		$arrayOfReservedMysqlKeywords[] = "INTO";
		$arrayOfReservedMysqlKeywords[] = "IS";
		$arrayOfReservedMysqlKeywords[] = "ITERATE";
		$arrayOfReservedMysqlKeywords[] = "JOIN";
		$arrayOfReservedMysqlKeywords[] = "KEY";
		$arrayOfReservedMysqlKeywords[] = "KEYS";
		$arrayOfReservedMysqlKeywords[] = "KILL";
		$arrayOfReservedMysqlKeywords[] = "LEADING";
		$arrayOfReservedMysqlKeywords[] = "LEAVE";
		$arrayOfReservedMysqlKeywords[] = "LEFT";
		$arrayOfReservedMysqlKeywords[] = "LIKE";
		$arrayOfReservedMysqlKeywords[] = "LIMIT";
		$arrayOfReservedMysqlKeywords[] = "LINES";
		$arrayOfReservedMysqlKeywords[] = "LOAD";
		$arrayOfReservedMysqlKeywords[] = "LOCALTIME";
		$arrayOfReservedMysqlKeywords[] = "LOCALTIMESTAMP";
		$arrayOfReservedMysqlKeywords[] = "LOCK";
		$arrayOfReservedMysqlKeywords[] = "LONG";
		$arrayOfReservedMysqlKeywords[] = "LONGBLOB";
		$arrayOfReservedMysqlKeywords[] = "LONGTEXT";
		$arrayOfReservedMysqlKeywords[] = "LOOP";
		$arrayOfReservedMysqlKeywords[] = "LOW_PRIORITY";
		$arrayOfReservedMysqlKeywords[] = "MATCH";
		$arrayOfReservedMysqlKeywords[] = "MEDIUMBLOB";
		$arrayOfReservedMysqlKeywords[] = "MEDIUMINT";
		$arrayOfReservedMysqlKeywords[] = "MEDIUMTEXT";
		$arrayOfReservedMysqlKeywords[] = "MIDDLEINT";
		$arrayOfReservedMysqlKeywords[] = "MINUTE_MICROSECOND";
		$arrayOfReservedMysqlKeywords[] = "MINUTE_SECOND";
		$arrayOfReservedMysqlKeywords[] = "MOD";
		$arrayOfReservedMysqlKeywords[] = "MODIFIES";
		$arrayOfReservedMysqlKeywords[] = "NATURAL";
		$arrayOfReservedMysqlKeywords[] = "NOT";
		$arrayOfReservedMysqlKeywords[] = "NO_WRITE_TO_BINLOG";
		$arrayOfReservedMysqlKeywords[] = "NULL";
		$arrayOfReservedMysqlKeywords[] = "NUMERIC";
		$arrayOfReservedMysqlKeywords[] = "ON";
		$arrayOfReservedMysqlKeywords[] = "OPTIMIZE";
		$arrayOfReservedMysqlKeywords[] = "OPTION";
		$arrayOfReservedMysqlKeywords[] = "OPTIONALLY";
		$arrayOfReservedMysqlKeywords[] = "OR";
		$arrayOfReservedMysqlKeywords[] = "ORDER";
		$arrayOfReservedMysqlKeywords[] = "OUT";
		$arrayOfReservedMysqlKeywords[] = "OUTER";
		$arrayOfReservedMysqlKeywords[] = "OUTFILE";
		$arrayOfReservedMysqlKeywords[] = "PRECISION";
		$arrayOfReservedMysqlKeywords[] = "PRIMARY";
		$arrayOfReservedMysqlKeywords[] = "PROCEDURE";
		$arrayOfReservedMysqlKeywords[] = "PURGE";
		$arrayOfReservedMysqlKeywords[] = "RAID0";
		$arrayOfReservedMysqlKeywords[] = "READ";
		$arrayOfReservedMysqlKeywords[] = "READS";
		$arrayOfReservedMysqlKeywords[] = "REAL";
		$arrayOfReservedMysqlKeywords[] = "REFERENCES";
		$arrayOfReservedMysqlKeywords[] = "REGEXP";
		$arrayOfReservedMysqlKeywords[] = "RELEASE";
		$arrayOfReservedMysqlKeywords[] = "RENAME";
		$arrayOfReservedMysqlKeywords[] = "REPEAT";
		$arrayOfReservedMysqlKeywords[] = "REPLACE";
		$arrayOfReservedMysqlKeywords[] = "REQUIRE";
		$arrayOfReservedMysqlKeywords[] = "RESTRICT";
		$arrayOfReservedMysqlKeywords[] = "RETURN";
		$arrayOfReservedMysqlKeywords[] = "REVOKE";
		$arrayOfReservedMysqlKeywords[] = "RIGHT";
		$arrayOfReservedMysqlKeywords[] = "RLIKE";
		$arrayOfReservedMysqlKeywords[] = "SCHEMA";
		$arrayOfReservedMysqlKeywords[] = "SCHEMAS";
		$arrayOfReservedMysqlKeywords[] = "SECOND_MICROSECOND";
		$arrayOfReservedMysqlKeywords[] = "SELECT";
		$arrayOfReservedMysqlKeywords[] = "SENSITIVE";
		$arrayOfReservedMysqlKeywords[] = "SEPARATOR";
		$arrayOfReservedMysqlKeywords[] = "SET";
		$arrayOfReservedMysqlKeywords[] = "SHOW";
		$arrayOfReservedMysqlKeywords[] = "SMALLINT";
		$arrayOfReservedMysqlKeywords[] = "SONAME";
		$arrayOfReservedMysqlKeywords[] = "SPATIAL";
		$arrayOfReservedMysqlKeywords[] = "SPECIFIC";
		$arrayOfReservedMysqlKeywords[] = "SQL";
		$arrayOfReservedMysqlKeywords[] = "SQLEXCEPTION";
		$arrayOfReservedMysqlKeywords[] = "SQLSTATE";
		$arrayOfReservedMysqlKeywords[] = "SQLWARNING";
		$arrayOfReservedMysqlKeywords[] = "SQL_BIG_RESULT";
		$arrayOfReservedMysqlKeywords[] = "SQL_CALC_FOUND_ROWS";
		$arrayOfReservedMysqlKeywords[] = "SQL_SMALL_RESULT";
		$arrayOfReservedMysqlKeywords[] = "SSL";
		$arrayOfReservedMysqlKeywords[] = "STARTING";
		$arrayOfReservedMysqlKeywords[] = "STRAIGHT_JOIN";
		$arrayOfReservedMysqlKeywords[] = "TABLE";
		$arrayOfReservedMysqlKeywords[] = "TERMINATED";
		$arrayOfReservedMysqlKeywords[] = "THEN";
		$arrayOfReservedMysqlKeywords[] = "TINYBLOB";
		$arrayOfReservedMysqlKeywords[] = "TINYINT";
		$arrayOfReservedMysqlKeywords[] = "TINYTEXT";
		$arrayOfReservedMysqlKeywords[] = "TO";
		$arrayOfReservedMysqlKeywords[] = "TRAILING";
		$arrayOfReservedMysqlKeywords[] = "TRIGGER";
		$arrayOfReservedMysqlKeywords[] = "TRUE";
		$arrayOfReservedMysqlKeywords[] = "UNDO";
		$arrayOfReservedMysqlKeywords[] = "UNION";
		$arrayOfReservedMysqlKeywords[] = "UNIQUE";
		$arrayOfReservedMysqlKeywords[] = "UNLOCK";
		$arrayOfReservedMysqlKeywords[] = "UNSIGNED";
		$arrayOfReservedMysqlKeywords[] = "UPDATE";
		$arrayOfReservedMysqlKeywords[] = "USAGE";
		$arrayOfReservedMysqlKeywords[] = "USE";
		$arrayOfReservedMysqlKeywords[] = "USING";
		$arrayOfReservedMysqlKeywords[] = "UTC_DATE";
		$arrayOfReservedMysqlKeywords[] = "UTC_TIME";
		$arrayOfReservedMysqlKeywords[] = "UTC_TIMESTAMP";
		$arrayOfReservedMysqlKeywords[] = "VALUES";
		$arrayOfReservedMysqlKeywords[] = "VARBINARY";
		$arrayOfReservedMysqlKeywords[] = "VARCHAR";
		$arrayOfReservedMysqlKeywords[] = "VARCHARACTER";
		$arrayOfReservedMysqlKeywords[] = "VARYING";
		$arrayOfReservedMysqlKeywords[] = "WHEN";
		$arrayOfReservedMysqlKeywords[] = "WHERE";
		$arrayOfReservedMysqlKeywords[] = "WHILE";
		$arrayOfReservedMysqlKeywords[] = "WITH";
		$arrayOfReservedMysqlKeywords[] = "WRITE";
		$arrayOfReservedMysqlKeywords[] = "X509";
		$arrayOfReservedMysqlKeywords[] = "XOR";
		$arrayOfReservedMysqlKeywords[] = "YEAR_MONTH";
		$arrayOfReservedMysqlKeywords[] = "ZEROFILL";
		$arrayOfReservedMysqlKeywords[] = "ASENSITIVE";
		$arrayOfReservedMysqlKeywords[] = "CALL";
		$arrayOfReservedMysqlKeywords[] = "CONDITION";
		$arrayOfReservedMysqlKeywords[] = "CONNECTION";
		$arrayOfReservedMysqlKeywords[] = "CONTINUE";
		$arrayOfReservedMysqlKeywords[] = "CURSOR";
		$arrayOfReservedMysqlKeywords[] = "DECLARE";
		$arrayOfReservedMysqlKeywords[] = "DETERMINISTIC";
		$arrayOfReservedMysqlKeywords[] = "EACH";
		$arrayOfReservedMysqlKeywords[] = "ELSEIF";
		$arrayOfReservedMysqlKeywords[] = "EXIT";
		$arrayOfReservedMysqlKeywords[] = "FETCH";
		$arrayOfReservedMysqlKeywords[] = "INOUT";
		$arrayOfReservedMysqlKeywords[] = "INSENSITIVE";
		$arrayOfReservedMysqlKeywords[] = "ITERATE";
		$arrayOfReservedMysqlKeywords[] = "LEAVE";
		$arrayOfReservedMysqlKeywords[] = "LOOP";
		$arrayOfReservedMysqlKeywords[] = "MODIFIES";
		$arrayOfReservedMysqlKeywords[] = "OUT";
		$arrayOfReservedMysqlKeywords[] = "READS";
		$arrayOfReservedMysqlKeywords[] = "RELEASE";
		$arrayOfReservedMysqlKeywords[] = "REPEAT";
		$arrayOfReservedMysqlKeywords[] = "RETURN";
		$arrayOfReservedMysqlKeywords[] = "SCHEMA";
		$arrayOfReservedMysqlKeywords[] = "SCHEMAS";
		$arrayOfReservedMysqlKeywords[] = "SENSITIVE";
		$arrayOfReservedMysqlKeywords[] = "SPECIFIC";
		$arrayOfReservedMysqlKeywords[] = "SQL";
		$arrayOfReservedMysqlKeywords[] = "SQLEXCEPTION";
		$arrayOfReservedMysqlKeywords[] = "SQLSTATE";
		$arrayOfReservedMysqlKeywords[] = "SQLWARNING";
		$arrayOfReservedMysqlKeywords[] = "TRIGGER";
		$arrayOfReservedMysqlKeywords[] = "UNDO";
		$arrayOfReservedMysqlKeywords[] = "WHILE";

			
		$nameToCheck = strtoupper($name); 

		// 01-22-07 - if the name is not in the array, then the name is safe, so we return true. 
		if (in_array($nameToCheck, $arrayOfReservedMysqlKeywords)) {
			return false; 
		} else {
			return true; 
		}
	} else {
		$controller->error("In checkDatabaseFieldNamesToBeSureTheyAreAllowed() we were not given a field name to check. The field name should be the first parameter of this function."); 	
	}
}



?>