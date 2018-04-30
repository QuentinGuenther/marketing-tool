<?php
/**
 * This class contains generic functions to prepare, bind, and execute PDO statements
 * for interacting with the database.
 *
 * PHP version 5.3
 * @author Quentin Guenther
 * @since 1.0
 * @version 1.1
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 */

/**
 * This config file should contain the following:
 *	define( "DB_DSN", "data_source_name");
 *	define( "DB_USERNAME", "username");
 *	define( "DB_PASSWORD", "password");
 * Since this file contains sensitive information,
 * it should be stored outside of public_html.
 */
require_once "/home/jshingre/marketing_config.php";

/**
 * This class contains generic functions for REST functionality.
 * with the database.
 *
 * PHP version 5.3
 * @author Quentin Guenther
 * @since 1.0
 * @version 1.1
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 */
abstract class RestDB
{
    private static $_dbh;

    /**
     * Instantiate a new db connection.
     * @return PDO instance.
     */
    public function __construct()
    {
        self::_connect();
        return self::$_dbh;
    }

    /**
     * Establishes a connection with the database using PDO and assigns connection to private variable.
     */
    private static function _connect()
    {
        try {
            self::$_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $e) {
            echo "Failure to connect to database. ".$e->getMessage();
        }
    }

    /**
     * This function gets row(s) from the database.
     * @param string $sql The SQL statement to run.
     * @param array $params A 3-dimensional array containing PDO parameters,
     * 		variable/value attached to the parameter and the PDO data type of the parameter.
     * @return array indexed by column name as returned in your result set.
     */
    protected static function get($sql, $params = array())
    {
        $statement = self::$_dbh->prepare($sql);
        foreach ($params as $parameter => $value) {
            foreach ($value as $variable => $dataType) {
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
     * @param array $params A 3-dimensional array containing PDO parameters,
     * 		variable/value attached to the parameter and the PDO data type of the parameter.
     * @param boolean $returnID Flag to return inserted ID or success. Default is true.
     * @return int The ID of the last inserted row or sequence value.
     */
    protected static function insert($sql, $params, $returnID = true)
    {
        $statement = self::$_dbh->prepare($sql);
        foreach ($params as $parameter => $value) {
            foreach ($value as $variable => $dataType) {
                $statement->bindValue($parameter, $variable, $dataType);
            }
        }
        $success = $statement->execute();
        if ($returnID && $success == true) {
            return self::$_dbh->lastInsertId();
        }
        return $success;
    }

    /**
     * This function performs an update to the database.
     * It is an alias of insert(), but for readability should
     * be used for update queries.
     * @param $sql The SQL statement to run.
     * @param $params A 3-dimensional array containing PDO parameters,
     * 		varuable/value attached to the parameter and the PDO data type of the parameter.
     * @return boolean Success.
     */
    protected static function update($sql, $params)
    {
        return self::insert($sql, $params, false);
    }

    /**
     * This function performs a delete to the database.
     * @param $sql The SQL statement to run.
     * @return boolean Success.
     */
    protected static function delete($sql, $params)
    {
        $statement = self::$_dbh->prepare($sql);
        foreach ($params as $parameter => $value) {
            foreach ($value as $variable => $dataType) {
                $statement->bindValue($parameter, $variable, $dataType);
            }
        }
        $success = $statement->execute();
        return $success;
    }
}