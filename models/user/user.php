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