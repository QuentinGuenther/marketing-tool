<?php
/**
 *
 */

/**
 * Class Db_user
 */
class Db_user extends RestDB
{
    /**
     * Db_user constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public static function getAllCurrentTeams()
    {
        $sql = "SELECT teamId, team_name FROM team";

        $result = parent::get($sql);

        return $result;
    }

    public static function getTeamMembers($teamId)
    {
        $sql = "SELECT first_name, last_name, userId FROM `user` WHERE teamId = :teamId";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;
    }

    public static function insertNewTeam($teamName)
    {
        // INSERT INTO team (team_name) VALUES ('marketing-team');
        $sql = "INSERT INTO team (team_name) VALUES (:teamName)";

        $params = array(
            ':teamName' => array($teamName => PDO::PARAM_STR)
        );

        return parent::insert($sql, $params);
    }

    public static function insertNewUser($firstName, $lastName, $email, $password, $teamId, $isAdmin = 0)
    {
        //INSERT INTO `user`(first_name, last_name, email, password, teamId, isAdmin) VALUES ('Mary', 'Sue', 'email@mail.greenriver.edu', sha1('abc'), 2, 0)
        $sql = "INSERT INTO `user`(first_name, last_name, email, password, teamId, isAdmin) VALUES (:firstName, :lastName, :email, :password, :teamId, :isAdmin)";

        $params = array(
            ':firstName' => array($firstName => PDO::PARAM_STR),
            ':lastName' => array($lastName => PDO::PARAM_STR),
            ':email' => array($email => PDO::PARAM_STR),
            ':password' => array(sha1($password) => PDO::PARAM_STR),
            ':teamId' => array($teamId => PDO::PARAM_INT),
            ':isAdmin' => array($isAdmin => PDO::PARAM_INT)
        );

        return parent::insert($sql, $params);
    }

    /* Login Validation */
    /**
     * This function checks to see if a username exists in the db
     * and returns userId.
     * @param $email string, email is username
     * @return int userId
     */

    public static function getLoginUsername($email)
    {
        //SELECT userId FROM `user` WHERE email = "jshin"
        $sql = "SELECT userId FROM `user` WHERE email = :email";

        $params = array(
            ':email' => array($email => PDO::PARAM_STR)
        );

        $result = parent::get($sql, $params);

        return $result;
    }

    /**
     * This function checks if password matches the user during login.
     * @param $userId int, userId
     * @return string password
     */
    public static function getLoginPassword($userId)
    {
        $sql = "SELECT password FROM `user` WHERE userId = :userId";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;
    }

    /**
     * This function gets the team id of the user
     * @param $userId int, userId
     * @return int team Id
     */
    public static function getUserTeamId($userId)
    {
        //SELECT teamId FROM `user` WHERE userId = 14 LIMIT 1
        $sql = "SELECT teamId FROM `user` WHERE userId = :userId LIMIT 1";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result[0]['teamId'];

    }

    /**
     * This function checks if the user is Admin user.
     * @param $userId int, userId
     * @return int isAdmin = 1 for admin user, 0 to normal user
     */
    public static function getIsAdmin($userId)
    {
        $sql = "SELECT isAdmin FROM `user` WHERE userId = :userId LIMIT 1";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;

    }

    /**
     * This function get the user email
     * @param $userId int, userId
     * @return string email
     */
    public static function getEmail($userId)
    {
        $sql = "SELECT email FROM `user` WHERE userId = :userId LIMIT 1";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;

    }
}