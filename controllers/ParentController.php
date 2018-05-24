<?php
/**
 *
 * This class defines fat-free's beforeroute which will execute before every routing call
 * of classes that extend this class.
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
 *
 * This class defines fat-free's beforeroute which will execute before every routing call
 * of classes that extend this class.
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
class ParentController
{
    protected $userId;

    /**
     * This route is a special fat-free route that is called before executing the function called in the route.
     * @param $f3 fat-free instance
     */
    function beforeroute($f3)
    {
        // this function executed before every routing call
        // session management, like checking if user has valid session
        if (empty($_SESSION['userId'])) {
            $f3->reroute("/login");
        }

        // set userId
        $this->userId = $_SESSION['userId'];
    }
}