<?php
/**
 * Created by PhpStorm.
 * User: Jen Shin, Quentin Guenther, Kianna Dyck, Bessy Torres-Miller
 * Date: 4/20/18
 * Time: 12:33 PM
 */

/**
 * This class contains functions for retrieving and inserting post information.
 * @author Quentin Guenther, Jen Shin, Kianna Dyck, Bessy Torres-Miller
 * @copyright 2018
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
     * @param $title The title of the project post.
     * @param $content The quill json object containing text, formatting, and images.
     * @return int The ID of the last inserted row or sequence value.
     */
    public static function insertPost($title, $content, $teamId)
    {
        $sql = "INSERT INTO post(title, content, teamId) VALUES (:title, :content, :teamId)";

        $params = array(
            ':title' => array($title => PDO::PARAM_STR),
            ':content' => array($content => PDO::PARAM_STR),
            ':teamId' => array($teamId => PDO::PARAM_STR)
        );

        return parent::insert($sql, $params);
    }

    /**
     * This function retrieves a single post from the database.
     * @param $postId The unique id number associated with a specific post.
     * @return array A row from the database containing all post information; the title and the content.
     */
    public static function getPost($postId)
    {
        $sql = "SELECT title, content FROM post WHERE postId = :postId";

        $params = array(
            ':postId' => array($postId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        $result = $result[0];

        return $result;

    }

    /**
     * This function retrieves all posts from a specific team from the database.
     * @param $teamId The unique id number associated with a specific team.
     * @return array All rows from the database for a team containing al post information;
     * the title, content, and postId.
     */
    public static function getAllPosts($teamId)
    {
        $sql = "SELECT postId, title, content FROM post WHERE teamId = :teamId";

        $params = array(
            ':teamId' => array($teamId => PDO::PARAM_INT)
        );

        $result = parent::get($sql, $params);

        return $result;

    }

}