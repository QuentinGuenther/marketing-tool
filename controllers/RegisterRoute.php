<?php
/**
 * This class handles the register route functionality.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 *
 */

/**
 * This class handles the register route functionality.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 *
 */
class RegisterRoute
{
    /**
     * This function renders the register page of the web application and validates user input.
     * @param $f3 Fat-Free instance
     */
    public function render($f3)
    {
        /* establish connection to the database */
        $db2 = new Db_user();
        /* Retrieve all teams (team_name & teamId) from database */
        $teams = $db2::getAllCurrentTeams();

        /* Create an associative array (teamMembers) for all team members for each team */
        $teamMembers = array();
        foreach ($teams as $row) {
            $team = $row['team_name'];
            $members = $db2::getTeamMembers($row['teamId']);
            $teamMembers[$team] = $members;
        }

        /* Save retrieved teams to the hive */
        $f3->set('currentTeams', $teams);
        /* Save retrieved team members for each team to hive */
        $f3->set('teamMembers', $teamMembers);

        if (isset($_POST['submit'])) {

            /* Email validation */
            $isValid = $this->validateEmail($f3, $db2);

            /* First name validation */
            $isValid = $this->validateName($f3, $_POST['first_name'], 'invalidFirst', 'Please enter a first name.');

            /* Last name validation */
            $isValid = $this->validateName($f3, $_POST['last-name'], 'invalidLast', 'Please enter a last name.');

            /* Password validation */
            $isValid = $this->validatePassword($f3);

            /* Team Placement Validations */
            $this->validateTeam($f3, $teams);

            if ($isValid)
            {
                $email = $_POST['email'];
                $firstName = $_POST['first-name'];
                $lastName = $_POST['last-name'];
                $password = $_POST['password'];
                $teamId = $_POST['team'];

                // Create new team in database if new team name entered
                if (!empty($_POST['create-team'])) {
                    $teamName = $_POST['create-team'];
                    $teamId = $db2::insertNewTeam($teamName);
                }

                // Insert new user into database
                $userId = $db2::insertNewUser($firstName, $lastName, $email, $password, $teamId);
                $_SESSION['userId'] = $userId;
                $_SESSION['teamId'] = $teamId;

                // reroute to team-home page
                $f3->reroute("/");

            }

        }

        echo Template::instance()->render('views/html/register.html');
    }

    /**
     * This function validates email input.
     * @param $f3 Fat-Free instance
     * @param $db2 PDO The database connection.
     * @return bool Returns true if email is valid, false otherwise.
     */
    private function validateEmail($f3, $db2)
    {
        if (!empty($_POST['email']) && Validate::validEmail($_POST['email'])) {
            // check if email is already registered
            $allEmails = $db2::getAllStudentEmails();
            if (!Validate::unregisteredEmail($allEmails, $_POST['email'])) {
                $f3->set('invalidEmail', $_POST['email'].' is already registered to an account.');
                return false;
            }

            return true;
        } else {
            // error message
            $f3->set('invalidEmail', 'Please enter a valid email.');
            return false;
        }
    }

    /**
     * This function validates first and last name are not empty.
     * @param $f3 Fat-free instance
     * @param $input String User input for first and last name.
     * @param $hiveVar String Variable name for hive error message.
     * @param $message String Error message if input is invalid.
     * @return bool Returns true if input is valid, false otherwise.
     */
    private function validateName($f3, $input, $hiveVar, $message)
    {
        if (empty($input)) {
            // error message
            $f3->set($hiveVar, $message);
            return false;
        }
        return true;
    }

    /**
     * This function validates the password input.
     * @param $f3 Fat-free instance
     * @return bool Returns true if password is valid, false otherwise.
     */
    private function validatePassword($f3)
    {
        if (empty($_POST['password']) || empty($_POST['password-confirm'])) {
            // error message
            $f3->set('invalidPassword', 'Please enter a password in both fields.');
            return false;
        } else if (!empty($_POST['password']) && !empty($_POST['password-confirm'])) {
            // check if password meets complexity rules (8+ characters)
            if (!Validate::validPassword($_POST['password'])) {
                $f3->set('invalidPassword', 'Please enter a password that is at minimum 8 characters long.');
                return false;
            } else {
                // Check if passwords match
                if ($_POST['password'] != $_POST['password-confirm']) {
                    // error message
                    $f3->set('mismatchedPasswords', 'Passwords do not match.');
                    return false;
                } else {
                    return true;
                }
            }

        }

        return false;
    }

    /**
     * This function validates team selection/creation.
     * @param $f3 Fat-free instance
     * @param $teams array Teams registered in database
     * @return bool Returns true if team selection or creation is valid, false otherwise.
     */
    private function validateTeam($f3, $teams)
    {
        if (!empty($_POST['teamChoice']) && Validate::validTeamChoice($_POST['teamChoice'])) {
            if ($_POST['teamChoice'] == 'new') {
                if (empty($_POST['create-team'])) {
                    // error message
                    $f3->set('invalidTeam', 'Please enter a team name');
                    return false;
                } else {
                    // verifies entered name does not already exist
                    if (Validate::validNewTeamName($teams, $_POST['create-team'])) {
                        return true;
                    } else {
                        // error message
                        $f3->set('invalidTeam', 'Sorry, that team name is already taken.');
                        return false;
                    }
                }
            } else {
                // if selecting from existing team
                if (Validate::validExistingTeam($teams, $_POST['team'])) {
                    return true;
                } else {
                    // error message
                    $f3->set('invalidTeam', 'Please select from existing teams.');
                    return false;
                }
            }
        } else {
            $f3->set('invalidTeam', 'Please select a team option.');
            return false;
        }
    }

}