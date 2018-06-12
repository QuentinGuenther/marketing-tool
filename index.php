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
    Name of File: index2.php
    Purpose: This page is the controller for marketing tool application.
 */

// Require autoload
require_once 'vendor/autoload.php';

// start session
session_start();

// Create fat-free instance
$f3 = Base::instance();

// establish connection to database
$db = new Db_post();

//Login for user
$f3->route('GET|POST @login: /login', 'Login->render');

// Default Route
$f3->route('GET /', function($f3) {
    if (empty($_SESSION['userId'])) {
        $f3->reroute("./login");
    } else {
        if ($_SESSION['admin']) {
            $f3->reroute('/teams');
        } else {
            // Reroute to team home
            $teamId = $_SESSION['teamId'];
            $f3->reroute("/teams/".$teamId);
        }
    }
});

// Team Home Route
$f3->route('GET /teams/@teamId', 'TeamHomeRoute->render');

// user teams view (Admin Home Page)
// Page with a list of teams
$f3->route('GET /teams', 'AdminRoutes->adminHome');

// create new post route
$f3->route('GET|POST @create: /create-post', 'CreatePost->render');

// intermediary route to retrieve post contents, only accessed by post.js ajax call
$f3->route('GET /get-post/@uuid', 'AjaxRoutes->getPost');

// If user tries to navigate to view-post directly without a parameter
$f3->route('GET /view-post', function($f3) {
    $f3->reroute("/");
});

// view post route
$f3->route('GET|POST @view: /view-post/@postId', 'ViewPostRoute->render');

// Route for Registration
$f3->route('GET|POST /register', 'RegisterRoute->render');

// Intermediary route for adding a vote to the database with ajax
$f3->route('POST /addVote', 'AjaxRoutes->addVote');

//logout page
$f3->route('GET|POST /logout', function($f3) {
    session_destroy();
    $f3 -> reroute('/login');
});


//FAQ page
$f3->route('GET /faq', function() {

    $template = new Template();
    echo $template->render('views/html/faq.html');
});

//route used for a page to show teams (user picks to change teams)
$f3->route('GET|POST /change-teams', 'AjaxRoutes->changeTeam');

//route to a page used to grab the teamId when admin user is removing teams
$f3->route('GET|POST /remove/@teamId', 'AjaxRoutes->removeTeam');

// route to set post being viewed as current
$f3->route('GET|POST /set-new-current', 'AjaxRoutes->setNewCurrent');


$f3->run();