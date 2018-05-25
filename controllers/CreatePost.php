<?php
/**
 * This page handles routing functionality for create-post route.
 *
 * PHP version 5.3
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @copyright 2018 Jen Shin <Jshin13@mail.greenriver.edu>
 *
 */

/**
 * This page handles routing functionality for create-post route.
 *
 * PHP version 5.3
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @copyright 2018 Jen Shin <Jshin13@mail.greenriver.edu>
 *
 */
class CreatePost extends ParentController
{
    /**
     * This function renders the create-post page and validates input.
     * @param $f3 fat-free instance.
     */
    public function render($f3)
    {
        // retrieve teamId from Session
        $teamId = $_SESSION['teamId'];

        if (isset($_POST['submit'])) {
            $title = "";
            $content = "";

            $isValid = true;

            if (!empty($_POST['title'])) {
                $title = $_POST['title'];
                $f3->set('title', $title);
            } else {
                $titleErr = "Please input a title.";
                $f3->set('titleErr', $titleErr);
                $isValid = false;
            }

            if (isset($_POST['new-post'])) {

                $title = $_POST['title'];
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
                //reroute to home page with refreshed list after posting
                $id = Db_post::insertPost($title, $content, $this->userId, $teamId);
                Db_post::updateParentId($id, $id);
                $f3->reroute('/view-post/'.$id);

            }
        }
        echo Template::instance()->render('views/html/create-post.html');
    }
}