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

// Team Home Page
$f3->route('GET /teams/@teamId', function($f3, $params) {
    if (empty($_SESSION['userId'])) {
        $f3->reroute("./login");
    } else {
        $userId = $_SESSION['userId'];
    }

    // database connection
    global $db;
    $db2 = new Db_user();

    // get teamId of logged in user
    $teamId = $params['teamId'];
    // Retrieve team name with teamId
    $teamName = $db2::getTeamName($teamId);
    // retrieve all team member names with teamId
    $teamMembers = $db2::getTeamMembers($teamId);

    $f3->set('teamName', $teamName);
    $f3->set('teamMembers', $teamMembers);

    // retrieve all project ideas with teamId
    $posts = $db::getAllPosts($teamId);

    if (empty($posts)) {
        $f3->set('noPosts', "There are currently no project ideas for your team. 
            Click the Add New Project button to be the first to share an idea.");
    }

    // set hive variables
    $f3->set('postIdeas', $posts);

    $template = new Template();
    echo $template->render('views/html/team-home.html');
});

// user teams view (Admin Home Page)
// Page with a list of teams
$f3->route('GET /teams', function($f3) {

    if (!$_SESSION['admin']) {
        $f3->reroute('/');
    }

    // establish connection with database
    $db2 = new Db_user();

    // retrieve all project ideas with teamId
    $teams = $db2::getAllCurrentTeams();
    if (empty($teams)) {
        $f3->set('noTeams', "No teams created yet");
    }

    // set hive variables
    $f3->set('teams', $teams);

    $template = new Template();
    echo $template->render('views/html/admin-teams.html');
});

// create new post route
$f3->route('GET|POST @create: /create-post', function($f3) {
    if (empty($_SESSION['userId'])) {
        $f3->reroute("./login");
    } else {
        $userId = $_SESSION['userId'];
    }

    // retrieve teamId from Session
    $teamId = $_SESSION['teamId'];

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

            $_SESSION['postContent'] = $content;

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
            unset($_SESSION['postContent']);
            //reroute to home page with refreshed list after posting
            $id = Db_post::insertPost($title, $content, $userId, $teamId);
            $f3->reroute('/view-post/'.$id);

        }
    }
    echo Template::instance()->render('views/html/create-post.html');
});

// intermediary route, only accessed by post.js ajax call
$f3->route('GET /get-post/@uuid', function($f3, $params) {
    if (empty($_SESSION['userId'])) {
        $f3->reroute("./login");
    } else {
        $userId = $_SESSION['userId'];
    }

    if ($params['uuid'] == 'session') {
        $post['content'] = $_SESSION['postContent'];
    } else {
        // retrieve post information
        $post = Db_post::getPost($params['uuid']);
    }

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
    if (empty($_SESSION['userId'])) {
        $f3->reroute("./register");
    } else {
        $userId = $_SESSION['userId'];
    }
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

    /* establish connection to the database */
    $db2 = new Db_user();
    /* Retrieve all teams (team_name & teamId) from database */
    $teams = $db2::getAllCurrentTeams();

    /* Save retrieved teams to the hive */
    $f3->set('currentTeams', $teams);

    /* Create an associative array (teamMembers) for all team members for each team */
    $teamMembers = array();
    foreach ($teams as $row) {
        $team = $row['team_name'];
        $members = $db2::getTeamMembers($row['teamId']);
        $teamMembers[$team] = $members;
    }

    /* Save retrieved team members for each team to hive */
    $f3->set('teamMembers', $teamMembers);


    /*  Validation functions (to be moved to another file later) */

    // verifies email is in correct format
    function validEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }

    // verifies password meets password complexity rules
    function validPassword($password) {
        // 8 or more characters
        return strlen($password) >= 8;
    }

    // validates radio to join/create a team is chosen & is a valid option
    function validTeamChoice($choice)
    {
        $choices = array("old", "new");
        if (!empty($choice)) {

            if (!in_array($choice, $choices)) {
                return false;
            }

            return true;
        }
    }

    // check if to-be-created name is already taken
    function validNewTeamName($teams, $teamName) {
        foreach($teams as $team) {
            if (strtoupper($teamName) == strtoupper($team['team_name'])) {
                return false;
            }
        }

        return true;
    }

    // verifies team chosen from dropdown selection is a valid option.
    function validExistingTeam($teams, $teamChosen) {
        // $teams is all teams from database

        foreach($teams as $team) {
            if ($teamChosen == $team['teamId']) {
                return true;
            }
        }

        return false;

    }

    if (isset($_POST['submit'])) {
        $isValid = true;
        $newTeam = false;

        $email = "";
        $firstName = "";
        $lastName = "";
        $password = "";
        $teamName = "";
        $teamId = "";

        /* Email validation */
        if (!empty($_POST['email']) && validEmail($_POST['email'])) {
            // check if email is already registered
            $allEmails = $db2::getAllStudentEmails();
            if (!empty($allEmails)) {
                foreach ($allEmails as $registered) {
                    if(in_array($_POST['email'], $registered)) {

                        $isValid = false;
                        // error message
                        $f3->set('invalidEmail', $_POST['email'].' is already registered to an account.');
                        break;
                    }
                }
            }

            // create variable that will be sent to user object
            $email = $_POST['email'];
        } else {
            $isValid = false;
            // error message
            $f3->set('invalidEmail', 'Please enter a valid email.');
        }

        /* First name validation */
        if (empty($_POST['first-name'])) {
            $isValid = false;
            // error message
            $f3->set('invalidFirst', 'Please enter a first name.');
        } else {
            $firstName = $_POST['first-name'];
        }

        /* Last name validation */
        if (empty($_POST['last-name'])) {
            $isValid = false;
            // error message
            $f3->set('invalidLast', 'Please enter a last name.');
        } else {
            $lastName = $_POST['last-name'];
        }

        /* Password validation */
        if (empty($_POST['password']) || empty($_POST['password-confirm'])) {
            $isValid = false;
            // error message
            $f3->set('invalidPassword', 'Please enter a password in both fields.');
        } else if (!empty($_POST['password']) && !empty($_POST['password-confirm'])) {
            // check if password meets complexity rules (8+ characters)
            if (!validPassword($_POST['password'])) {
                $isValid = false;
                $f3->set('invalidPassword', 'Please enter a password that is at minimum 8 characters long.');
            } else {
                // Check if passwords match
                if ($_POST['password'] != $_POST['password-confirm']) {
                    $isValid = false;
                    // error message
                    $f3->set('mismatchedPasswords', 'Passwords do not match.');
                } else {
                    $password = $_POST['password'];
                }
            }

        }

        /* Team Placement Validations */
        if (!empty($_POST['teamChoice']) && validTeamChoice($_POST['teamChoice'])) {
            if ($_POST['teamChoice'] == 'new') {
                if (empty($_POST['create-team'])) {
                    $isValid = false;
                    // error message
                    $f3->set('invalidTeam', 'Please enter a team name');
                } else {
                    // verifies entered name does not already exist
                    if (validNewTeamName($teams, $_POST['create-team'])) {
                        $newTeam = true;
                        $teamName = $_POST['create-team'];
                    } else {
                        $isValid = false;
                        // error message
                        $f3->set('invalidTeam', 'Sorry, that team name is already taken.');
                    }
                }
            } else {
                // if selecting from existing team
                if (validExistingTeam($teams, $_POST['team'])) {
                    $teamId = $_POST['team'];
                } else {
                    $isValid = false;
                    // error message
                    $f3->set('invalidTeam', 'Please select from existing teams.');
                }
            }
        } else {
            $f3->set('invalidTeam', 'Please select a team option.');
        }


        if ($isValid)
        {
            // Create new team in database if new team name entered
            if ($newTeam) {
                $teamId = $db2::insertNewTeam($teamName);
            }

            // Insert new user into database
            $userId = $db2::insertNewUser($firstName, $lastName, $email, $password, $teamId);

            // create user object, store user object in session, and reroute to team-home
            /*$student = new Marketing_Student($email, $password, $userId, $firstName, $lastName, $teamId);*/
            /*$_SESSION['marketing_student'] = $student;*/

            $_SESSION['userId'] = $userId;
            $_SESSION['teamId'] = $teamId;

            // reroute to team-home page
            $f3->reroute("/");

        }

    }

    echo Template::instance()->render('views/html/register.html');
});

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