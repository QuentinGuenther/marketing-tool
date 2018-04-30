<?php
/**
 * This class creates a post object that manages the title and content.
 *
 * PHP version 5.3
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @copyright Jen Shin <Jshin13@mail.greenriver.edu>
 *
 */

/**
 * This class creates a post object.
 *
 * PHP version 5.3
 * @author Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @author Jen Shin <Jshin13@mail.greenriver.edu>
 *
 * @since 1.0
 * @version 1.0
 *
 * @copyright 2018 Quentin Guenther <Qguenther@mail.greenriver.edu>
 * @copyright Jen Shin <Jshin13@mail.greenriver.edu>
 */
class Post
{

    private $_title;
    private $_content;

    /**
     * Post constructor.
     * @param $title String The title of the project post.
     * @param $content String The content of the project post.
     */
    public function __construct($title, $content)
    {
        $this->_title = $title;
        $this->_content = $content;
    }

    /**
     * This function retrieves the post title.
     * @return String Post Title
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * This function retrieves the post content.
     * @return String Post Content
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * This function sets the title of the post.
     * @param $title String Post title.
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * This function sets the content of the post.
     * @param $content Post Content
     */
    public function setContent($content)
    {
        $this->_content = $content;
    }

}