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
}