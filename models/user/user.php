<?php
/**
 * This class creates a user object.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018 Jen Shin <Jshin13@mail.greenriver.edu>
 *
 */


/**
 * This class creates a user object.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018 Jen Shin <Jshin13@mail.greenriver.edu>
 *
 */
class User
{
    // Login credentials
    protected $emailUsername;
    protected $password;

    // User id
    protected $userId;

    /**
     * User constructor.
     * @param $emailUsername
     * @param $password
     * @param $userId
     */
    public function __construct($emailUsername, $password, $userId)
    {
        $this->emailUsername = $emailUsername;
        $this->password = $password;
        $this->userId = $userId;
    }

    /**
     * This function retrieves the user's email.
     * @return String email of user.
     */
    public function getEmailUsername()
    {
        return $this->emailUsername;
    }

    /**
     * This function sets the user's email to the given value.
     * @param String $emailUsername A valid email address to replace the old email.
     */
    public function setEmailUsername($emailUsername)
    {
        $this->emailUsername = $emailUsername;
    }

    /**
     * This function retrieve's the user's current password.
     * @return String The user's password.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * This function sets the user's password to the given password.
     * @param String $password A valid password to replace old password.
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * This function retrieves the user's unique id.
     * @return int user id
     */
    public function getUserId()
    {
        return $this->userId;
    }

}