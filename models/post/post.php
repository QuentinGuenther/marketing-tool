<?

class Post 
{

    private $_title;
    private $_content;

    public function __construct($title, $content) 
    {
        $this->title = $title;
        $this->content = $content;
    }

    public function getTitle() 
    {
        return $this->title;
    }

    public function getContent() 
    {
        return $this->content;
    }

    public function setTitle($title) 
    {
        $this->title = $title;
    }

    public function setContent($content) 
    {
        $this->content = $content;
    }

}
