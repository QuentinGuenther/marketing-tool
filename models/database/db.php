<?php
	/* 
	 * This config file should contain the following:
	 *	define( "DB_DSN", "data_source_name");
	 *	define( "DB_USERNAME", "username");
	 *	define( "DB_PASSWORD", "password");
	 * Since this file contains sensitive information,
	 * it should be stored outside of public_html.
	 */
    require_once ('/home/jshingre/marketing_config.php');
    
	/**
	 * This class contains generic functions for REST functionallity.
	 * with the database.
	 * @author Quentin Guenther
	 * @copyright 2018
	 */

	abstract class RestDB
	{
		/**
		 * Instantiate a new db connection.
		 * @return PDO instance.
		 */
		public function __construct()
		{	
			try {
				global $dbh;
			    $dbh = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
			    return $dbh;
			}
			catch(PDOException $e) {
	        	echo $e->getMessage();
	        	return;
	    	}
		}
		
		/**
		 * This function gets row(s) from the database.
		 * @param string $sql The SQL statement to run.
		 * @param array $params A 3 dimentional array containing PDO parameters,
		 * 		varuable/value attached to the parameter and the PDO data type of the parameter.
		 * @return An array indexed by column name as returned in your result set.
		 */
		protected static function get($sql, $params = array())
		{
			global $dbh;
			$statement = $dbh->prepare($sql);
			foreach($params as $parameter => $value) {
				foreach($value as $variable => $dataType) {
					$statement->bindValue($parameter, $variable, $dataType);
				}
			}
			$success = $statement->execute();
	    	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		
		/**
		 * This function performs an insert to the database.
		 * @param string $sql The SQL statement to run.
		 * @param array $params A 3 dimentional array containing PDO parameters,
		 * 		varuable/value attached to the parameter and the PDO data type of the parameter.
		 * @param boolean $returnID Flag to return inserted ID or success. Default is true.
		 * @return int The ID of the last inserted row or sequence value.
		 */
		protected static function insert($sql, $params, $returnID = true)
		{
			global $dbh;
			$statement = $dbh->prepare($sql);
			foreach($params as $parameter => $value) {
				foreach($value as $variable => $dataType) {
					$statement->bindValue($parameter, $variable, $dataType);
				}
			}
			$success = $statement->execute();
			if($returnID && $success == true)
				return $dbh->lastInsertId();
			return $success;
		}
		
		/**
		 * This function performs an update to the database.
		 * It is an alias of insert(), but for readability should 
		 * be used for update queries.
		 * @param $sql The SQL statement to run.
		 * @param $params A 3 dimentional array containing PDO parameters,
		 * 		varuable/value attached to the parameter and the PDO data type of the parameter.
		 * @return boolean Success.
		 */
	/*	protected static function update($sql, $params)
		{
			return self::insert($sql, $params, false);			
		}
		
		/**
		 * This function performs a delete to the database.
		 * @param $sql The SQL statement to run.
		 * @return boolean Success.
		 */
		/*protected static function delete($sql, $params)
		{
			global $dbh;
			$statement = $dbh->prepare($sql);
			foreach($params as $parameter => $value) {
				foreach($value as $variable => $dataType) {
					$statement->bindValue($parameter, $variable, $dataType);
				}
			}
			$success = $statement->execute();
			return $success;
		}*/
	}
