<?php
/**
 * This class contains functions for retrieving and inserting post information.
 *
 * PHP version 5.3
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Team Agility
 *
 */

/**
 * This class contains functions for retrieving and inserting post information.
 *
 * PHP version 5.3
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 * @copyright 2018 Team Agility
 */
class Db_post extends RestDB
{
    /**
     * Instantiates a connection with the database.
     * Db_post constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This functions inserts original post into the database.
     * @param $title String The title of the project post.
     * @param $content String The quill json object containing text, formatting, and images.
     * @param $teamId int The unique id number associated with a specific team.
     * @return int The ID of the last inserted row or sequence value.
     */
    public static function insertPost($title, $content, $userId, $teamId)
    {
        $sql = "INSERT INTO post(title, content, userId, teamId, isActive) VALUES (:title, :content, :userId, :teamId, 1)";

        $params = array(
            ':title' => array($title => PDO::PARAM_STR),
            ':content' => array($content => PDO::PARAM_STR),
            ':userId' => array($userId => PDO::PARAM_STR),
            ':teamId' => array($teamId => PDO::PARAM_STR)
        );

        return parent::insert($sql, $params);
    }

    public static function insertPostVersion($title, $content, $userId, $teamId, $parentId)
    {
        //$parentId = self::getParentId();

        $sql = "INSERT INTO post(title, content, userId, teamId, isActive, parent_id) VALUES (:title, :content, :userId, :teamId, 1, :parentId)";

        $params = array(
            ':title' => array($title => PDO::PARAM_STR),
            ':content' => array($content => PDO::PARAM_STR),
            ':userId' => array($userId => PDO::PARAM_STR),
            ':teamId' => array($teamId => PDO::PARAM_STR),
            ':parentId' => array($parentId =>PDO::PARAM_INT)
        );

        return parent::insert($sql, $params);
    }


    /**
     * When original post is inserted, need to change parent id to itself
     * once postId is created on db.
     * @param $postId int post id
     * @return bool
     */
    public static function updateParentId($parentId, $postId)
    {
        $sql = "UPDATE post SET parent_id = :parentId WHERE postId = :postId";

        $params = array (
            ':parentId' => array($parentId => PDO::PARAM_INT),
            ':postId' => array($postId => PDO::PARAM_INT)
        );

        return parent::update($sql, $params);
    }

    public static function changeActiveStatus($postId, $active = 0)
    {
        //UPDATE table_name SET column1 = value1, column2 = value2, WHERE condition;

        $sql = "UPDATE post SET isActive = :active WHERE postId = :postId ";

        $params = array(
            ':postId' => array($postId => PDO::PARAM_STR),
            ':active' => array($active => PDO::PARAM_INT)
        );

        return parent::update($sql, $params);
    }

    public static function getParentId($postId)
    {
        $sql = "SELECT parent_id FROM post WHERE postId = :postId";

        $params = array(
            ':postId' => array($postId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        $parentId = $result[0]['parent_id'];

        return $parentId;
    }


    /**
     * This function retrieves a single post from the database.
     * @param $postId int The unique id number associated with a specific post.
     * @return array A row from the database containing all post information; the title and the content.
     */
    public static function getPost($postId)
    {
        $sql = "SELECT title, content, teamId FROM post WHERE postId = :postId";

        $params = array(
            ':postId' => array($postId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        $result = $result[0];

        return $result;

    }

    /**
     * This function retrieves all posts from a specific team from the database.
     * @param $teamId int The unique id number associated with a specific team.
     * @return array All rows from the database for a team containing al post information;
     * the title, content, and postId.
     */
    public static function getAllPosts($teamId)
    {
        $sql = "SELECT postId, title, content, isActive FROM post WHERE teamId = :teamId ORDER BY postId DESC";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;

    }

    /**
     * This function retrieves all versions of a post.
     * First sql query retrieves parent id of the post
     * to query for all versions based on original post.
     * @param $postId int post id
     * @return array All rows from db for versions of original post
     */
    public static function getAllPostVersions($postId)
    {
        $parentId = self::getParentId($postId);

        $sql = "SELECT postId, content, date_created, userId, isActive FROM post WHERE parent_id = :parentId ORDER BY date_created DESC";

        $params = array(
            ':parentId' => array($parentId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;
    }

    /**
     * This function allows a user to vote for a post.
     * @param $userId int user id
     * @param $postId int post id
     * @return int
     */
    public static function addVote($userId, $postId)
    {
        // get the parent id
        $parentId = self::getParentId($postId);

        $sql = "INSERT INTO postVotes(userId, parent_Id) VALUES (:userId, :parentId)";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT),
            ':parentId' => array($parentId => PDO::PARAM_INT)
        );

        return parent::insert($sql, $params);
    }

    /**
     * This function will select all entries in the votePosts table with associated
     * userId and return a count. This is the number of votes a student has made.
     * @param $userId
     * @return int
     */
    public static function getUserVoteCount($userId)
    {
        $sql = "SELECT parent_id FROM postVotes WHERE userId = :userId";
        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        return count(parent::get($sql, $params));

    }

    /**
     * This function checks if a user has already voted for a specific post.
     * @param $userId
     * @param $postId
     * @return array
     */
    public static function getUserVote($userId, $postId)
    {
        // get parent id
        $parentId = self::getParentId($postId);

        $sql = "SELECT parent_id FROM postVotes WHERE userId = :userId AND parent_id = :parent_id";
        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT),
            ':parent_id' => array($parentId => PDO::PARAM_INT)
        );

        return parent::get($sql, $params);
    }

    /**
     * This function retrieves the total number of votes for a given post.
     * @param $postId
     * @return int
     */
    public static function getAllVotesForPost($postId)
    {
        // get parent id
        $parentId = self::getParentId($postId);

        $sql = "SELECT userId FROM postVotes WHERE parent_id = :parentId";
        $params = array(
            ':parentId' => array($parentId => PDO::PARAM_INT)
        );

        return count(parent::get($sql, $params));
    }

    /**
     * Delete the all posts from the team Id passed
     * @param $teamId team to delete
     * @return bool true if successful, false otherwise
     */
    public function removePosts($teamId)
    {
        $sql = "DELETE FROM post WHERE teamId =:teamId";

        $params = array(
            'teamId' => array($teamId=>PDO::PARAM_INT)
        );

        $result = parent::update($sql, $params);

        return $result;
    }

    /**
     * Delete all the votes from the users that belong to the team Id passed
     * @param $teamId the team to remove
     * @return bool true if successful, false otherwise
     */
    public function removeVotes($teamId)
    {
        $sql = "DELETE FROM postVotes WHERE userId IN (SELECT userId FROM user WHERE teamId =:teamId)";


        $params = array(
            'teamId' => array($teamId=>PDO::PARAM_INT)
        );

        $result = parent::update($sql, $params);

        return $result;

    }
}