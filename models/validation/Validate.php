<?php
/**
 * Author: Kianna Dyck
 * Date: 5/23/2018
 * File Name: Validate.php
 * Purpose: This class validates user input when created an account
 */

class Validate
{
    /**
     * This function verifies email is in correct format
     * @param $email String Email entered by user
     * @return bool Returns true if email is in valid email format
     */
    public static function validEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }


    /**
     * This function verifies the given email address is not already registered to an account.
     * @param $allEmails array All email addresses currently registered to an account.
     * @param $email String Email entered by user
     * @return bool Returns true if email is not already registered, false otherwise.
     */
    public static function unregisteredEmail($allEmails, $email)
    {
        if (!empty($allEmails)) {
            foreach ($allEmails as $registered) {
                if(in_array($_POST['email'], $registered)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * This function verifies password meets password complexity rules
     * @param $password password enter by the user
     * @return bool returns true is the password is valid, false otherwise
     */
    public static function validPassword($password) {
        // 8 or more characters
        return strlen($password) >= 8;
    }

    /**
     * This function validates radio to join/create a team is chosen & is a valid option
     * @param $choice is option selected by the user (join or create a team)
     * @return bool returns true if it's a valid option, false otherwise
     */
    public static function validTeamChoice($choice)
    {
        $choices = array("old", "new");
        if (!empty($choice)) {

            if (!in_array($choice, $choices)) {
                return false;
            }

            return true;
        }
    }

    /**
     * This function check if to-be-created name is already taken
     * @param $teams list of teams from the db
     * @param $teamName name entered by the user
     * @return bool true if it's a valid name or false otherwise
     */
    public static function validNewTeamName($teams, $teamName) {
        foreach($teams as $team) {
            if (strtoupper($teamName) == strtoupper($team['team_name'])) {
                return false;
            }
        }

        return true;
    }


    /**
     * This function verifies team chosen from drop down selection is a valid option.
     * @param $teams Team names from the db
     * @param $teamChosen team selected by the user
     * @return bool returns true if it's valid selection or false otherwise
     */
    public static function validExistingTeam($teams, $teamChosen) {

        foreach($teams as $team) {
            if ($teamChosen == $team['teamId']) {
                return true;
            }
        }

        return false;

    }
}