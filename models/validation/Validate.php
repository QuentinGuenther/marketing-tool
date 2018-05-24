<?php
/**
 * Author: Kianna Dyck
 * Date: 5/23/2018
 * File Name: Validate.php
 * Purpose: This class validates user input
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

    // verifies password meets password complexity rules
    public static function validPassword($password) {
        // 8 or more characters
        return strlen($password) >= 8;
    }

    // validates radio to join/create a team is chosen & is a valid option
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

    // check if to-be-created name is already taken
    public static function validNewTeamName($teams, $teamName) {
        foreach($teams as $team) {
            if (strtoupper($teamName) == strtoupper($team['team_name'])) {
                return false;
            }
        }

        return true;
    }

    // verifies team chosen from dropdown selection is a valid option.
    public static function validExistingTeam($teams, $teamChosen) {
        // $teams is all teams from database

        foreach($teams as $team) {
            if ($teamChosen == $team['teamId']) {
                return true;
            }
        }

        return false;

    }
}