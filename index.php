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

    // home route
    $f3->route('GET|POST /', function($f3) {

        if(isset($_POST['submit'])) {
            $contentErr = "";
            $titleErr = "";
           // var_dump($_POST);
            // }
            $isValid = true;

            if (!empty($_POST['title'])) {
                $title = $_POST['title'];
            } else {
                $titleErr = "Please input a title.";
                $f3->set('titleErr', $titleErr);
                $isValid = false;
            }
            $content = $_POST['new-post'];
            $json = json_decode($content, true);
            print_r($content.'<br/>');

            foreach($json as $key => $value) {
                print_r($value);
                foreach($value as $valuekey => $secondvalue) {
                    print_r($secondvalue);
                    foreach($secondvalue as $key1 => $value2) {
                        if (!isset($value2)) {
                            $isValid = false;

                            $contentErr = "Please input text and/or images.";
                            $f3->set('contentErr', $contentErr);
                            return;
                        }
                    }
                }
            }

            /*foreach($json['ops']['insert'] as $item) {
                if($item == "\n") {
                    $isValid = false;

                    $contentErr = "Please input text and/or images.";
                    $f3->set('contentErr', $contentErr);
                }            }
            //for ( $i = 0; $i < sizeof($content); $i++ ) {
           /* foreach($content as $key => $value){
                foreach ( $value['ops']['insert'] as $item) {
                    if($item == "\n") {
                        $isValid = false;

                        $contentErr = "Please input text and/or images.";
                        $f3->set('contentErr', $contentErr);
                    }
                    }
               // }
            }
            /*foreach () {
                $f3->set('content', $content);
            } else {
                $contentErr = "Please input text and/or images.";
                $f3->set('contentErr', $contentErr);
                $isValid = false;
            }*/

            if ($isValid) {
                $f3->reroute('/view-post');
            }

        }
        echo Template::instance()->render('views/html/home.html');
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
?>