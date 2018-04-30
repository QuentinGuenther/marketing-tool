<?php
/**
 * This page is the controller for marketing tool application.
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

/*
    Authors: Quentin Guenther, Kianna Dyck, Jen Shin, Bessy Torres-Miller
    Date: 04/10/2018
    Name of File: index.php
    Purpose: This page is the controller for marketing tool application.
 */

// Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Require autoload
require_once 'vendor/autoload.php';

// start session
session_start();

// Create fat-free instance
$f3 = Base::instance();

// Set debug level to dev
$f3->set('DEBUG', 3);

// establish connection to database
$db = new Db_post();

// Team Home Page
$f3->route('GET /', function($f3) {

    global $db;

    // get teamId and teamName of logged in user

    // retrieve all project ideas with teamId
    $posts = $db::getAllPosts(1);
    if (empty($posts)) {
        $f3->set('noPosts', "There are currently no project ideas for your team. 
            Click the Add New Project button to be the first to share an idea.");
    }

    // retrieve all team member names with teamId

    // set hive variables
    $f3->set('postIdeas', $posts);

    $template = new Template();
    echo $template->render('views/html/team-home.html');
});

// create new post route
$f3->route('GET|POST /create-post', function($f3) {

    if (isset($_POST['submit'])) {
        $title = "";
        $content = "";

        $isValid = true;

        if (!empty($_POST['title'])) {
            $title = $_POST['title'];
        } else {
            $titleErr = "Please input a title.";
            $f3->set('titleErr', $titleErr);
            $isValid = false;
        }

        if (isset($_POST['new-post'])) {

            $title = $_POST['title'];
            $content = $_POST['new-post'];

            $json = json_decode($content, true);

            if (strlen($json['ops'][0]['insert']) == 1) {
                $isValid = false;
                $contentErr = "Please input text and/or images.";
                $f3->set('contentErr', $contentErr);
            }

        }

        if ($isValid) {
            $id = Db_post::insertPost($title, $content, 1);
            $f3->reroute('/view-post/'.$id);
        }
    }

    echo Template::instance()->render('views/html/home.html');
});

// intermediary route, only accessed by post.js ajax call
$f3->route('GET /get-post/@uuid', function($f3, $params) {
    // retrieve post information
    $post = Db_post::getPost($params['uuid']);

    header('Content-Type: application/json');
    // return string that is encoded in JSON format
    echo json_encode($post['content']);
});

// If user tries to navigate to view-post directly without a parameter
$f3->route('GET /view-post', function($f3) {
    $f3->reroute("/");
});

// view post route
$f3->route('GET|POST @view: /view-post/@postId', function($f3, $params) {

    // check that param entered is a number
    if (!ctype_digit($params['postId'])) {
        $f3->reroute("/");
    }

    $postId = $params['postId'];
    $f3->set('postId', $postId);
    $f3->set('title', Db_post::getPost($params['postId'])['title'] );

    echo Template::instance()->render('views/html/view-post.html');
});

/**
 * Route for the error page
 *
 * If a page is not found, the error page
 * gets displayed
 *
 * @alias /views/error.html
 * @param $f3 Base
 */
$f3->set('ONERROR', function($f3) {
    if ($f3->get('ERROR.code') == '404')
        echo Template::instance()->render('views/error.html');
});

$f3->run();