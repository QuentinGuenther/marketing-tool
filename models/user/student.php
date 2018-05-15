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
class Marketing_Student extends User
{
    private $_firstName;
    private $_lastName;
    private $_teamId;

    /**
     * Marketing_Student constructor.
     * @param $username String The student's Green River email.
     * @param $password String The student's password to log into the marketing tool web application.
     * @param $userId int The student's auto-generated user id.
     * @param $_firstName String The student's first name.
     * @param $_lastName String The student's last name.
     * @param $_teamId int The team id associated with a Student's team.
     */
    public function __construct($username, $password, $userId, $_firstName, $_lastName, $_teamId)
    {
        parent::__construct($username, $password, $userId);

        $this->_firstName = $_firstName;
        $this->_lastName = $_lastName;
        $this->_teamId = $_teamId;
    }

    /**
     * This function retrieves the student's first name.
     * @return String The student's first name
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * This function sets the student's first name to the given value.
     * @param String $firstName The student's new first name.
     */
    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    /**
     * This function retrieves the student's last name.
     * @return String The student's last name.
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * This function sets the student's last name to the given value.
     * @param String $lastName The student's new last name.
     */
    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    /**
     * This function retrieves the student's current team id
     * @return int Student's team Id
     */
    public function getTeamId()
    {
        return $this->_teamId;
    }

    /**
     * This function sets the student's team Id to the given value.
     * @param int $teamId Student's new team Id.
     */
    public function setTeamId($teamId)
    {
        $this->_teamId = $teamId;
    }

    /**
     * This function retrieves a student's email address.
     * @return String The student's email
     */
    public function getEmailUsername()
    {
        return $this->emailUsername;
    }

    /**
     * This function sets the student's email to the given value.
     * @param String $emailUsername A new email address
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