<?php
/**
 * This page handles routing functionality for routes accessed using Ajax.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 *
 */


/**
 * This page handles routing functionality for routes accessed using Ajax. Also includes other routes accessed only
 * in processing.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 *
 */
class AjaxRoutes
{
    /**
     * This function processes voting functionality when called using ajax.
     */
    public function addVote()
    {
        global $db;
        echo $db::addVote($_POST['userId'], $_POST['postId'], $_POST['inputVote']); // String, boolean

    }

    /**
     * This function retrieves and processes post information when called using ajax.
     * @param $f3 fat-free instance, required for params array to work properly inside class.
     * @param $params array url parameters array
     */
    public function getPost($f3, $params)
    {
        global $db;

        if ($params['uuid'] == 'session') {
            $post['content'] = $_SESSION['postContent'];
        } else {
            // retrieve post information
            $post = $db::getPost($params['uuid']);
        }

        header('Content-Type: application/json');
        // return string that is encoded in JSON format
        echo json_encode($post['content']);
    }

    /**
     * This function sets the post being viewed as the active/current post
     * @param $f3 fat-free instance, required for params array to work properly inside class.
     * @param $params array url parameters array
     */
    public function setNewCurrent($f3, $params)
    {
        // establish connection with database
        $db = new Db_post();
        $success = false;

        //grabbing the teamId from the parameter
        $postId = $_POST['postId'];
        $allVersions = $db::getAllPostVersions($postId);

        // Search for current active version and set isActive to zero
        foreach ($allVersions as $row) {
            if ($row['isActive'] == 1) {
                $success = $db::changeActiveStatus($row['postId']);
                break;
            }
        }

        // update current post to isActive
        $success = $db::changeActiveStatus($postId, 1);

        if($success)
        {
            echo "success";
        } else {
            echo "Failed to set post as current version. Please try again later.";
        }
    }

    /**
     * This function removes the given team from the db.
     * @param $f3 fat-free instance, required for params array to work properly inside class.
     * @param $params array url parameters array
     */
    function removeTeam($f3, $params)
    {
        // establish connection with database
        $db1 = new Db_post();
        $db2 = new Db_user();

        //grabbing the teamId from the parameter
        $teamId = $params['teamId'];

        //calling the sql statements by passing the teamId
        $success4 = $db1->removeVotes($teamId);
        $success3 = $db1->removePosts($teamId);
        $success1 = $db2->removeUser($teamId);
        $success = $db2->removeTeam($teamId);

        //if all the remove where successful, reroute to team page
        if($success && $success1 && $success3 && $success4)
        {
            $f3 -> reroute('/teams');
        } else {
            echo "Unsuccessful";
        }
    }

    /**
     * This function allows a student to change their team
     * @param $f3 fat-free instance, required for params array to work properly inside class.
     */
    function changeTeam($f3)
    {
        if ($_SESSION['admin']) {
            $f3->reroute('/');
        }

        //grabs the current user's info from the session
        $userId = $_SESSION['userId'];
        $teamId = $_SESSION['teamId'];

        // establish connection with database
        $db2 = new Db_user();

        //check if the user has changed teams before
        $hasChangesTeam = $db2::getHasChangedTeam($userId);
        if($hasChangesTeam == 1)
        {
            //reroute to teams page
            $f3->reroute('/teams');
        }

        //get the teams availables to the user
        $teams = $db2::getOtherTeams($teamId);
        if (empty($teams)) {
            $f3->set('noTeams', "No other teams created yet");
        }

        // set hive variables
        $f3->set('teams', $teams);

        // verifies team chosen from selection is a valid option.
        function validExistingTeam($teams, $teamChosen) {
            foreach($teams as $team) {
                if ($teamChosen == $team['teamId']) {
                    return true;
                }
            }
            return false;
        }

        //if a team is chosen
        if (isset($_POST['submit'])) {

            if (validExistingTeam($teams, $_POST['teams'])) {
                $teamId = $_POST['teams'];

                $success = $db2::updateTeam($teamId, $userId);

                // retrieve new teamId
                $teamId = $db2::getUserTeamId($userId);
                $_SESSION['teamId'] = $teamId;
                $_SESSION['success'] = $success;

                //update has Changed Team -
                $changedTeam = $db2::hasChangeTeamUpdate($userId);
                $hasChangedTeam = $db2::getHasChangedTeam($userId);
                $_SESSION['hasChangedTeam'] = $hasChangedTeam;

                $f3 -> reroute('/teams');
            }
        }

        $template = new Template();
        echo $template->render('views/html/teamsList.html');
    }
}