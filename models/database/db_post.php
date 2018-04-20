<?php
/**
 * Created by PhpStorm.
 * User: kyongah
 * Date: 4/20/18
 * Time: 12:33 PM
 */

class db_post extends RestDB
{
    public function __construct() {
        parent::__construct();
    }

    public static function insertPost($title, $content)
    {
        $sql = "INSERT INTO post(title, content) VALUES (:title, :content)";

        $params = array(
            ':title' => array($title => PDO::PARAM_STR),
            ':content' => array($content => PDO::PARAM_STR)
        );

        return parent::insert($sql, $params);
    }

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



}