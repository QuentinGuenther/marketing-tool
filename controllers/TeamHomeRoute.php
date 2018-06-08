<?php
/**
 * This class handles the register route functionality.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018 Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 */

/**
 * This class handles the register route functionality.
 *
 * PHP version 5.3
 * @author Kianna Dyck <kdyck@mail.greenriver.edu>
 * @author Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Kianna Dyck <kdyck@mail.greenriver.edu>
 * @copyright 2018 Bessy Torres-Miller <Btorres-miller@mail.greenriver.edu>
 *
 */
class TeamHomeRoute extends ParentController
{
    /**
     * This function renders a team's home page.
     * @param $f3 fat-free instance
     * @param $params array parameters array
     */
    public function render($f3, $params)
    {
        // database connection
        global $db; // Db_post
        $db2 = new Db_user();

        // get teamId of logged in user
        $teamId = $params['teamId'];
        // Retrieve team name with teamId
        $teamName = $db2::getTeamName($teamId);
        // retrieve all team member names with teamId
        $teamMembers = $db2::getTeamMembers($teamId);

        // retrieve all project ideas with teamId
        $posts = $db::getAllPosts($teamId);

        if (empty($posts)) {
            $f3->set('noPosts', "There are currently no project ideas for your team. 
            Click the Add New Project button to be the first to share an idea.");
        }

        /* $posts array */

        /*Array
        ( [0] => Array (
            [postId] => 16
            [title] => asas
            [totalVotes] => 9 )
          [1] => Array (
            [postId] => 14
            [title] => sdfsdf
            [totalVotes] => 2 )
          [2] => Array (
            [postId] => 13
            [title] => new
            [totalVotes] => 5 )
        [3] => Array (
            [postId] => 12
            [title] => post
            [totalVotes] => 8 )
        [4] => Array (
            [postId] => 11
             [title] => potato
            [totalVotes] => 7 )
        [5] => Array (
            [postId] => 10
            [title] => Test Vote #6
            [totalVotes] => 4 )
        [6] => Array (
            [postId] => 9
            [title] => Test Vote #5
            [totalVotes] => 4 )
        [7] => Array (
            [postId] => 8
            [title] => Test Vote #4
            [totalVotes] => 11 )
        [8] => Array (
            [postId] => 7
            [title] => Vote Test #3
            [totalVotes] => 3 )
        [9] => Array (
            [postId] => 6
            [title] => Vote Test #2
            [totalVotes] => 6 )
        [10] => Array (
            [postId] => 5
            [title] => Testing Voting
            [totalVotes] => 18 ) )
        */

//        print_r($posts);

        // set hive variables
        $f3->set('postIdeas', $posts);
        $f3->set('teamName', $teamName);
        $f3->set('teamMembers', $teamMembers);

        $template = new Template();
        echo $template->render('views/html/team-home.html');
    }
}