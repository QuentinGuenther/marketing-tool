<?php
/**
 * Created by PhpStorm.
 * User: Kiwi
 * Date: 6/12/2018
 * Time: 2:32 PM
 */

class Login
{
    /**
     * This function renders the login page.
     * @param $f3 fat-free instance, required for params array to work properly inside class.
     */
    function render($f3)
    {
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

                        // get teamId of user and set to session
                        $teamId = $db2::getUserTeamId($userId);
                        $hasChangedTeam = $db2::getHasChangedTeam($userId);
                        $_SESSION['teamId'] = $teamId;
                        $_SESSION['hasChangedTeam']=$hasChangedTeam;

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
    }
}