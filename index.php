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

$f3->route('GET|POST @login: /login', function($f3) {
    echo Template::instance()->render('views/html/home.html');

});

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

//user teams view
// Page with a lis t of teams
$f3->route('GET /teams', function($f3) {
    global $db;

    // retrieve all project ideas with teamId
    $teams = $db::getAllTeamsId(1);
    if (empty($teams)) {
        $f3->set('noTeams', "No teams created yet");
    }

    // set hive variables
    $f3->set('teams', $teams);

    $template = new Template();
    echo $template->render('views/html/user-teams.html');
});

// create new post route
$f3->route('GET|POST @create: /create-post', function($f3) {

    if (isset($_POST['submit'])) {
        $title = "";
        $content = "";

        $isValid = true;

        if (!empty($_POST['title'])) {
            $title = $_POST['title'];
            $f3->set('title', $title);
        } else {
            $titleErr = "Please input a title.";
            $f3->set('titleErr', $titleErr);
            $isValid = false;
        }

        if (isset($_POST['new-post'])) {

            $title = $_POST['title'];
            $content = $_POST['new-post'];

            $json = json_decode($content, true);

            if (count($json['ops']) < 1 || $json['ops'][0]['insert'] == "\n") {
                $isValid = false;
                $contentErr = "Please input text and/or images.";
                $f3->set('contentErr', $contentErr);
            } else if(strlen(implode('', $json)) > 7000000) {
                $isValid = false;
                $contentErr = "Post is too large. Try resizing/compressing your images.";
                $f3->set('contentErr', $contentErr);
            }

        }

        if ($isValid) {
            //reroute to home page with refreshed list after posting
            $id = Db_post::insertPost($title, $content, 1);
            $f3->reroute('/view-post/'.$id);
            //$f3->reroute('/');
        }
    }

    echo Template::instance()->render('views/html/create-post.html');
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

$f3->route('GET|POST /register', function($f3) {

    if (isset($_POST['submit'])) {
        // Basic PHP Validation. Still need to validate email format, teamChoice radio selection, and drop-down menu.
        // Probably should move this validation to another file. Or utilize a loop...
        $isValid = true;

        // also need to add email validation for email format
        if (empty($_POST['email'])) {
            $isValid = false;
            // error message
            $f3->set('invalidEmail', 'Please enter a Green River email.');
        } else {
            // create variable that will be sent to user object
        }

        if (empty($_POST['first-name'])) {
            $isValid = false;
            // error message
            $f3->set('invalidFirst', 'Please enter a first name.');
        }

        if (empty($_POST['last-name'])) {
            $isValid = false;
            // error message
            $f3->set('invalidLast', 'Please enter a last name.');
        }

        if (empty($_POST['password']) || empty($_POST['password-confirm'])) {
            $isValid = false;
            // error message
            $f3->set('invalidPassword', 'Please enter a password in both fields.');
        } else if (!empty($_POST['password']) && !empty($_POST['password-confirm'])) {
            if ($_POST['password'] != $_POST['password-confirm']) {
                $isValid = false;
                // error message
                $f3->set('mismatchedPasswords', 'Passwords do no match.');
            }
        }

        // verify teamChoice radio selection is a valid option (anti-hacker!)

        if ($_POST['teamChoice'] == 'new') {
            if (empty($_POST['create-team'])) {
                $isValid = false;
                // error message
                $f3->set('invalidTeam', 'Please enter a team name');
            }
        }

        // if teamChoice = old, verify $_POST['team'] is a valid value from available drop-down choices (anti-hacker!)

        if ($isValid)
        {
            // create user object, store user object in session, and reroute to team-home
        }

    }

    echo Template::instance()->render('views/html/register.html');
});

$f3->route('GET /admin', function($f3) {
    echo Template::instance()->render('views/html/admin-team-view.html');
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