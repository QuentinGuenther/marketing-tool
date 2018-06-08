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
 * @author Quentin Guenther
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
        $maxVotes = 10;
        $currentVoteCount = $db::getUserVoteCount($this->userId); // user votes made
        $availableVotes = $maxVotes - $currentVoteCount;

        // get total vote count for project post
        $postVotes = $db::getAllVotesForPost($postId);

        // check if user has already voted for this post
        $voted = $db::getUserVote($this->userId, $postId);
        /*var_dump($voted);
        var_dump("current: ".$currentVoteCount);
        var_dump("available: ".$availableVotes);*/
        if(is_null($voted)) {
            $voted = 0;
        }

        $f3->set('hasAlreadyVoted', $voted);
        /*if (!empty($voted))
        {
            $f3->set('hasAlreadyVoted', "You have already voted for this project.");
        }*/

        /* Version Control Logic */

        /* Retrieve all teams (team_name & teamId) from database */
        $postsArray = $db::getAllPostVersions($postId);
        //print_r($postVersions);

        $postsVersion = array();

        foreach ($postsArray as $row) {
//            $time = $row['date_created'];
            // timestamp in database returns as eastern standard time.
            // This converts 2018-05-26 04:46:22 to May 26, 2018 at 01:46am, subtracting three hours for pacific standard time
            // because the DateTime setTimeZone doesn't output the correct time...
            $time = date("M d, Y \a\\t h:ia", strtotime('-3 hours', strtotime($row['date_created'])));
            $postId = $row['postId'];
            $isActive = $row['isActive'];
            $member = $db2::getUserName($row['userId']);
            $postsVersion[] = array(
                'member' => $member,
                'time' => $time,
                'postId' => $postId,
                'isActive' => $isActive
            );
        }

        /*        print_r($postsVersion);*/

        // Set hive variables
        // $f3->set('postId', $postId);
        $f3->set('userId', $this->userId);
        $f3->set('availableVotes', $availableVotes);
        $f3->set('currentVoteCount', $currentVoteCount);
        $f3->set('postVotes', $postVotes);
        $f3->set('title', $post['title'] );
        $f3->set('postsVersions', $postsVersion);


        /* Quentin Validation */
        if (isset($_POST['submit'])) {
            $title = $f3->get('title');
            $content = "";
            // retrieve teamId from Session
            $teamId = $_SESSION['teamId'];
            $isValid = true;
            if (isset($_POST['new-post'])) {
                $content = $_POST['new-post'];
                $json = json_decode($content, true);
                $_SESSION['postContent'] = $content;
                if (count($json['ops']) < 1 || $json['ops'][0]['insert'] == "\n") {
                    $isValid = false;
                    $contentErr = "Please input text and/or images.";
                    $f3->set('contentErr', $contentErr);
                } else if(strlen(implode('', $json)) > 7000000) {
                    $isValid = false;
                    $contentErr = "Post is too large. Try resizing/compressing your images.";
                    $f3->set('contentErr', $contentErr);
                }
            }
            if ($isValid) {
                unset($_SESSION['postContent']);

                //change active status of previous version
                Db_post::changeActiveStatus($params['postId']);

                $parentId = Db_post::getParentId($params['postId']);
                //reroute to new post with refreshed list after posting
                $id = $db::insertPostVersion($title, $content, $this->userId, $teamId, $parentId);

                $f3->reroute('/view-post/'.$id);

            }
        }

        echo Template::instance()->render('views/html/view-post.html');
    }
}