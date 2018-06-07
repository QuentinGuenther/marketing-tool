<?php
/**
 * This class contains functions for retrieving and inserting user and team information.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Team Agility
 *
 */

/**
 * This class contains functions for retrieving and inserting user and team information.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Team Agility
 *
 */
class Db_user extends RestDB
{
    /**
     * Instantiates a connection with the database.
     * Db_user constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This function retrieves all teams from the database.
     * @return array An array containing each team name and associated team id.
     */
    public static function getAllCurrentTeams()
    {
        $sql = "SELECT teamId, team_name FROM team";

        $result = parent::get($sql);

        return $result;
    }

    /**
     * This function retrieves all team members associated with a given team id
     * @param $teamId int team id
     * @return array Array containing team member information for a given team.
     */
    public static function getTeamMembers($teamId)
    {
        $sql = "SELECT first_name, last_name, userId FROM `user` WHERE teamId = :teamId";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;
    }

    /**
     * This function inserts a new team into the database.
     * @param $teamName String The team name for the new team.
     * @return int The ID of the last inserted row in database.
     */
    public static function insertNewTeam($teamName)
    {
        // INSERT INTO team (team_name) VALUES ('marketing-team');
        $sql = "INSERT INTO team (team_name) VALUES (:teamName)";

        $params = array(
            ':teamName' => array($teamName => PDO::PARAM_STR)
        );

        return parent::insert($sql, $params);
    }

    /**
     * This function inserts a new user into the database.
     * @param $firstName User's first name
     * @param $lastName User's last name
     * @param $email User's email
     * @param $password User's password
     * @param $teamId User's teamId
     * @param int $isAdmin Admin privilege level. 0 = normal user, 1 = admin user
     * @return int The ID of the last inserted row in database.
     */
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
     * @return array userId
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
     * @return array password
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
     * @return array isAdmin = 1 for admin user, 0 to normal user
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
     * @return array email
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

    /**
     * This function retrieves all student emails from the database.
     * @return array An array containing all student emails
     */
    public static function getAllStudentEmails()
    {
        $sql = "SELECT email FROM user";

        $result = parent::get($sql);

        return $result;
    }

    /**
     * This function retrieves the team name for a single team given a team id
     * @param $teamId int A user's team id
     * @return String team name
     */
    public static function getTeamName($teamId)
    {
        $sql = "SELECT team_name FROM team WHERE teamId = :teamId LIMIT 1";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result[0]['team_name'];

    }


    /**
     * This function retrieves all teams from the database excepting the one that user already belongs
     * @param $teamId current teamId of the user
     * @return array An array containing each team name and associated team id
     */
    public static function getOtherTeams($teamId)
    {
        $sql = "SELECT teamId, team_name FROM team WHERE teamId <> :teamId";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT)
        );

        $result = parent::get($sql,$params);

        return $result;
    }

    /**
     * This function retrieves if the sql was success
     * @param $teamId int A user's team id
     * @param $userId int user's Id
     * @return boolean success
     */
    public static function updateTeam($teamId, $userId)
    {
        //UPDATE user SET teamId = 3 WHERE userId= 1
        $sql = "UPDATE user SET teamId = :teamId WHERE userId = :userId ";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT),
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::update($sql, $params);

        return $result;

    }

    /**
     * Remove a team from the db
     * @param $teamId team Id to delete
     * @return bool true if successful, false otherwise
     */
    public function removeTeam($teamId)
    {
        $sql = "DELETE FROM team WHERE teamId = :teamId";

        $params = array(
            'teamId' => array($teamId=>PDO::PARAM_INT)
        );

        $result = parent::update($sql, $params);

        return $result;
    }

    /**
     * Remove the users from the team to delete
     * @param $teamId team to delete
     * @return bool true if successful, false otherwise
     */
    public function removeUser($teamId)
    {
        $sql = "DELETE FROM user WHERE teamId = :teamId";

        $params = array(
            'teamId' => array($teamId=>PDO::PARAM_INT)
        );

        $result = parent::update($sql, $params);

        return $result;
    }

    /**
        * This function gets the user name from a user id.
        * @param $userId int user id
        * @return string user first and last name
    */
    public static function getUserName($userId)
    {
        $sql = "SELECT first_name, last_name FROM `user` WHERE userId = :userId LIMIT 1";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result[0]['first_name']." ".$result[0]['last_name'];
    }

    /**
     * This function retrieves the value 1 or 0, depending if the user already changed teams
     *
     * @param $userId int user's Id
     * @return int hasChangedTeam value
     */
    public static function getHasChangedTeam($userId)
    {
        $sql = "SELECT hasChangedTeam FROM user WHERE userId = :userId";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result[0] ['hasChangedTeam'];
    }

    /**
     * This function retrieves if the sql was success
     * Allows user to change team just one time
     * @param $userId int user's Id
     * @return boolean success
     */
    public static function hasChangeTeamUpdate($userId)
    {

        $sql = "UPDATE user SET hasChangedTeam = 1 WHERE userId = :userId ";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        $result = parent::update($sql, $params);

        return $result;

    }

}