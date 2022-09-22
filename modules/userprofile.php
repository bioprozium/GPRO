<?php
class userprofile
{
    public $gets;
    public $posts;
    function __construct()
    {
        $this->gets = $_GET;
        $this->posts = $_POST;

    }
    public function SetPage()
    {
        
    }
}
?>