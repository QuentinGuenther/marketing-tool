<?php
    /*
        Name: Quentin, Kianna, Jen, Bessy
        Date: 04/10/2018
        Name of File: index.php
        Purpose: This page is the controller for marketing tool application.
     */

    session_start();

    // Turn on error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Require autoload
    require_once('vendor/autoload.php');

    // Create fat-free instance
    $f3 = Base::instance();

    // Set debug level to dev
    $f3->set('DEBUG', 3);

    // Team Home Page
    $f3->route('GET /home', function($f3) {

        $template = new Template();
        echo $template->render('views/html/team-home.html');
    });

    // home route (Currently create new post page)
    $f3->route('GET|POST /', function($f3) {


        if(isset($_POST['submit'])) {

            $isValid = true;

            if (!empty($_POST['title'])) {
                $title = $_POST['title'];
            } else {
                $titleErr = "Please input a title.";
                $f3->set('titleErr', $titleErr);
                $isValid = false;
            }

            if (isset($_POST['new-post'])) {

                $content = $_POST['new-post'];
                $_SESSION['content'] = $content;
                $json = json_decode($content, true);
              //  var_dump($json);
                //print_r($content . '<br/>');
                var_dump($json['ops'][0]['insert']); //check if in correct place

                if(strlen($json['ops'][0]['insert']) == 1) {
                    $isValid = false;
                    $contentErr = "Please input text and/or images.";
                }
                $f3->set('contentErr', $contentErr);

            }


            if ($isValid) {
                $_SESSION['content'] = $content;

                $f3->reroute('/view-post');

            }
        }
        echo Template::instance()->render('views/html/home.html');
    });

    $f3->route('GET /get-post', function($f3) {
        header('Content-Type: application/json');
        echo json_encode($_SESSION['content']);
    });

    // preview post route
    $f3->route('GET|POST @view: /view-post', function($f3) {
        echo Template::instance()->render('views/html/view-post.html');
    });

    /**
     * Route for the error page
     *
     * If a page is not found, the error page
     * gets displayed
     *
     * @alias /views/error.html
     * @param $f3 Base
     */
    $f3->set('ONERROR', function($f3) {
        if($f3->get('ERROR.code') == '404')
            echo Template::instance()->render('views/error.html');
    });

    $f3->run();