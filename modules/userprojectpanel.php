<?php
class userprojectpanel
{
    public $gets;
    public $posts;
    public $user_id;
    public $user_name;
    function __construct()
    {
        $this->gets = $_GET;
        $this->posts = $_POST;
        tools::checkIfUserLoggedIn();
        $this->user_id = $_SESSION["user_id"];
        $this->user_name = $_SESSION["user_name"];
    }
    public function SetPage()
    {
        
    }
}
?>