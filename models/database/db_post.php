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
     * This functions inserts a single post into the database.
     * @param $title String The title of the project post.
     * @param $content String The quill json object containing text, formatting, and images.
     * @param $teamId int The unique id number associated with a specific team.
     * @return int The ID of the last inserted row or sequence value.
     */
    public static function insertPost($title, $content, $userId, $teamId)
    {
        $sql = "INSERT INTO post(title, content, userId, teamId) VALUES (:title, :content, :userId, :teamId)";

        $params = array(
            ':title' => array($title => PDO::PARAM_STR),
            ':content' => array($content => PDO::PARAM_STR),
            ':userId' => array($userId => PDO::PARAM_STR),
            ':teamId' => array($teamId => PDO::PARAM_STR)
        );

        return parent::insert($sql, $params);
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
        $sql = "SELECT postId, title, content FROM post WHERE teamId = :teamId ORDER BY postId DESC";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;

    }

    /**
     * This function retrieves all versions of a post.
     * @param $postId int original post aka parent id
     * @return array All rows from db for versions of original post
     */
    public static function getAllPostVersions($postId)
    {
        $sql = "SELECT content, date_created, userId FROM post WHERE parent_id = :parentId ORDER BY date_created DESC";

        $params = array(
            ':parentId' => array($postId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;
    }

    public static function addVote($userId, $postId)
    {
        $sql = "INSERT INTO postVotes(userId, postId) VALUES (:userId, :postId);";

        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT),
            ':postId' => array($postId => PDO::PARAM_INT)
        );

        return parent::insert($sql, $params);
    }

    public static function getUserVoteCount($userId)
    {
//        $sql = "SELECT COUNT(postId) FROM postVotes WHERE userId = :userId";
        $sql = "SELECT postId FROM postVotes WHERE userId = :userId";
        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT)
        );

        return count(parent::get($sql, $params));

    }

    public static function getUserVote($userId, $postId)
    {
        $sql = "SELECT postId FROM postVotes WHERE userId = :userId AND postId = :postId";
        $params = array(
            ':userId' => array($userId => PDO::PARAM_INT),
            ':postId' => array($postId => PDO::PARAM_INT)
        );

        return parent::get($sql, $params);
    }

    public static function getAllVotesForPost($postId)
    {
        $sql = "SELECT userId FROM postVotes WHERE postId = :postId";
        $params = array(
            ':postId' => array($postId => PDO::PARAM_INT)
        );

        return count(parent::get($sql, $params));
    }
}