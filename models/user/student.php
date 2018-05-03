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
     * @return String
     */
    public function getFirstName()
    {
        return $this->_firstName;
    }

    /**
     * @param String $firstName
     */
    public function setFirstName($firstName)
    {
        $this->_firstName = $firstName;
    }

    /**
     * @return String
     */
    public function getLastName()
    {
        return $this->_lastName;
    }

    /**
     * @param String $lastName
     */
    public function setLastName($lastName)
    {
        $this->_lastName = $lastName;
    }

    /**
     * @return int
     */
    public function getTeamId()
    {
        return $this->_teamId;
    }

    /**
     * @param int $teamId
     */
    public function setTeamId($teamId)
    {
        $this->_teamId = $teamId;
    }

    /**
     * @return mixed
     */
    public function getEmailUsername()
    {
        return $this->emailUsername;
    }

    /**
     * @param mixed $emailUsername
     */
    public function setEmailUsername($emailUsername)
    {
        $this->emailUsername = $emailUsername;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

}