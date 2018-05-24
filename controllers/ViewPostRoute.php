<?php
/**
 * This class handles the view-post/@postId route functionality.
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
 * This class handles the view-post/@postId route functionality.
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
class ViewPostRoute extends ParentController
{
    /**
     * This function renders the view-post/@postId page, validates the parameter, and contains voting functionality.
     * @param $f3 fat-free instance
     * @param $params array parameters array
     */
    public function render($f3, $params)
    {
        // establish connection to database
        $db = new Db_post();
        $db2 = new Db_user();

        // check that param entered is a number
        if (!ctype_digit($params['postId'])) {
            $f3->reroute("/");
        }
        $postId = $params['postId'];

        // check if postId's associated teamId matches teamId of logged in user
        // or if user is admin
        $post = $db::getPost($postId);
        if (!$_SESSION['admin'] && $_SESSION['teamId'] != $post['teamId']) {
            $f3->reroute("/");
        }

        // Voting Logic
        $maxVotes = 20;
        $currentVoteCount = $db::getUserVoteCount($this->userId);
        $availableVotes = $maxVotes - $currentVoteCount;

        // get total vote count for project post
        $postVotes = $db::getAllVotesForPost($postId);

        // check if user has already voted for this post
        $voted = $db::getUserVote($this->userId, $postId);
        if (!empty($voted))
        {
            $f3->set('hasAlreadyVoted', "You have already voted for this project.");
        }

        /* Version Control Logic */

        /* Retrieve all teams (team_name & teamId) from database */
        $postVersions = $db::getAllPostVersions($postId);
        print_r($postVersions);

        foreach ($postVersions as $row) {
            $time = $row['date_created'];
            $member = $db2::getUserName($row['userId']);
            echo "<p>".$member." - ".$time."</p>";
        }

        // Set hive variables
        $f3->set('postId', $postId);
        $f3->set('userId', $this->userId);
        $f3->set('availableVotes', $availableVotes);
        $f3->set('postVotes', $postVotes);
        $f3->set('title', $post['title'] );

        echo Template::instance()->render('views/html/view-post.html');
    }
}