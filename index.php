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

//Login for user (student)
$f3->route('GET|POST @login: /login', function($f3) {
    $db2 = new Db_user();

    if (isset($_POST['submit'])) {
        $password = "";
        $username = "";

        $isValid = true;

        //if empty, not valid, otherwise sent into hive
        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
            $f3->set('username', $username);
        } else {
            $usernameErr = "Please input your email in the username field.";
            $f3->set('usernameErr', $usernameErr);
            $isValid = false;
        }

        if (isset($_POST['password'])) {
            $password = $_POST['password'];

            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
                $f3->set('password', $password);
            } else {
                //empty
                $passwordErr = "Please input a password.";
                $f3->set('passwordErr', $passwordErr);
                $isValid = false;
            }

        }
        //both fields are not empty
        if ($isValid) {
            $userId = "";
            $dbPassword = "";
            //check if username matches db
            $userArray = $db2::getLoginUsername($username);

            //the case where username does not exist
            if (empty($userArray)) {
                $usernameErr = "This username does not exist.";
                $f3->set('usernameErr', $usernameErr);
            } else { //username exists
                //check password against userId
                //Array ( [0] => Array ( [userId] => 2 ) )
                foreach ($userArray as $row) {
                    $userId = $row['userId'];
                }

                $dbPasswordArray = $db2::getLoginPassword($userId);

                foreach ($dbPasswordArray as $row) {
                    $dbPassword = $row['password'];
                }

                //check if the user is Admin
                $dbIsAdminArray = $db2::getIsAdmin($userId);

                foreach ($dbIsAdminArray as $row){
                    $dbIsAdmin = $row['isAdmin'];
                }


                //if successful
                if(sha1($password) == $dbPassword) {
                    //set userId in session
                    $_SESSION['userId'] = $userId;

                    //if successful
                    if ($dbIsAdmin == 1) {
                        //set userId in session
                        $_SESSION['userId'] = $userId;
                        $_SESSION['admin'] = true;

                        //reroute to user home
                        $f3->reroute('/teams');
                    }
                    //create user object???
                    // get teamId of user and set to session (eventually will be placed in user object)
                    $teamId = $db2::getUserTeamId($userId);
                    $_SESSION['teamId'] = $teamId;

                    //reroute to user home
                    $f3->reroute('/');
                } else { //error message to show password is incorrect
                    $passwordErr = "Password is incorrect.";
                    $f3->set('passwordErr', $passwordErr);
                }
            }
        }
    }
    echo Template::instance()->render('views/html/home.html');

});

//admin-login routing page
$f3->route('GET|POST @login: /admin-login', function($f3) {
    $db2 = new Db_user();

    if (isset($_POST['submit'])) {
        $password = "";
        $username = "";

        $isValid = true;

        //if empty username, not valid, otherwise sent into hive
        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
            $f3->set('username', $username);
        } else {
            $usernameErr = "Please input your email in the username field.";
            $f3->set('usernameErr', $usernameErr);
            $isValid = false;
        }

        //if empty password, not valid, otherwise sent into hive
        if (isset($_POST['password'])) {
            $password = $_POST['password'];

            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
                $f3->set('password', $password);
            } else {
                //empty
                $passwordErr = "Please input a password.";
                $f3->set('passwordErr', $passwordErr);
                $isValid = false;
            }
        }

        //both fields are not empty
        if ($isValid) {
            $userId = "";
            $dbPassword = "";
            $dbIsAdmin = "";

            //check if username matches db-----------------------------
            $userArray = $db2::getLoginUsername($username);

            //the case where username does not exist
            if (empty($userArray)) {
                $usernameErr = "This username does not exist.";
                $f3->set('usernameErr', $usernameErr);
            } else { //username exists
                //check password against userId
                foreach ($userArray as $row) {
                    $userId = $row['userId'];
                }

                $dbPasswordArray = $db2::getLoginPassword($userId);
                foreach ($dbPasswordArray as $row) {
                    $dbPassword = $row['password'];
                }

                //check if the user is Admin
                $dbIsAdminArray = $db2::getIsAdmin($userId);

                foreach ($dbIsAdminArray as $row){
                    $dbIsAdmin = $row['isAdmin'];
                }

                //if successful
                if ((sha1($password) == $dbPassword) && $dbIsAdmin == 1) {
                    //set userId in session
                    $_SESSION['userId'] = $userId;
                    $_SESSION['admin'] = true;

                    //reroute to user home
                    $f3->reroute('/teams');
                } else { //error message to show password is incorrect
                    $passwordErr = "Password is incorrect.";
                    $f3->set('passwordErr', $passwordErr);
                }
            }
        }
    }
    echo Template::instance()->render('views/html/admin-login.html');
});

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


//route used for a page to show teams (user picks to change teams)
$f3->route('GET|POST /change-teams', function($f3) {

    if ($_SESSION['admin']) {
        $f3->reroute('/');
    }

    //grabs the current user's info from the session
    $userId = $_SESSION['userId'];
    $teamId = $_SESSION['teamId'];

    // establish connection with database
    $db2 = new Db_user();

    //get the teams availables to the user
    $teams = $db2::getOtherTeams($teamId);
    if (empty($teams)) {
        $f3->set('noTeams', "No teams created yet");
    }

    // set hive variables
    $f3->set('teams', $teams);

    // verifies team chosen from selection is a valid option.
    function validExistingTeam($teams, $teamChosen) {
        foreach($teams as $team) {
            if ($teamChosen == $team['teamId']) {
                return true;
            }
        }
        return false;
    }

    //if a team is chosen
    if (isset($_POST['submit'])) {

        if (validExistingTeam($teams, $_POST['teams'])) {
            $teamId = $_POST['teams'];

            $success = $db2::updateTeam($teamId, $userId);

            // retrieve new teamId
            $teamId = $db2::getUserTeamId($userId);
            $_SESSION['teamId'] = $teamId;
            $_SESSION['success'] = $success;

            $f3 -> reroute('/teams');
        }
    }

    $template = new Template();
    echo $template->render('views/html/teamsList.html');
});


//route to a page used to grab the teamId when admin user is removing teams
$f3->route('GET|POST /remove/@teamId', function($f3, $params) {

    // establish connection with database
    $db1 = new Db_post();
    $db2 = new Db_user();

    //grabbing the teamId from the parameter
    $teamId = $params['teamId'];

    //calling the sql statements by passing the teamId
    $success4 = $db1->removeVotes($teamId);
    $success3 = $db1->removePosts($teamId);
    $success1 = $db2->removeUser($teamId);
    $success = $db2->removeTeam($teamId);

    //if all the remove where successful, reroute to team page
    if($success && $success1 && $success3 && $success4)
    {
        $f3 -> reroute('/teams');
    } else {
        echo "Unsuccessful";
    }
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