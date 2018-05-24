<?php
/**
 * This page handles routing functionality for admin pages.
 *
 * PHP version 5.3

 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 */

/**
 * This page handles routing functionality for admin pages.
 *
 * PHP version 5.3

 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 */
class AdminRoutes
{
    /**
     * This function handles routing for the admin home page which contains
     * a list of team urls.
     * @param $f3 Fat-free instance
     */
    public function adminHome($f3)
    {
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
    }
}