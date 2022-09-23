<?php
class projectCtrl
{
    public $posts;
    public $gets;
    public $answer;
    function __construct()
    {
        $this->posts = $_POST;
        $this->gets = $_GET;

        if(isset($this->gets["action"]))
        {
            if($this->gets["action"] == "save")
            {
                $this->answer = $this->saveProject();
            }
            else if($this->gets["action"] == "share")
            {
                $this->shareProject();
            }
        }
    }
    public function SetPage()
    {
        if(isset($this->gets["act"]) && $this->gets["act"] == "sf")
        {
            echo file_get_contents(MAINBLOCK."project-Control_modal_save.html");
            exit;
        }
        else if(isset($this->gets["act"]) && $this->gets["act"] == "shf")
        {
            echo file_get_contents(MAINBLOCK."project-Control_modal_share.html");
            exit;
        }
        echo $this->answer;
        exit;
    }
    private function saveProject()
    {
        //$data = json_decode($this->posts["data"]);
        $data = $this->posts["data"];
        $act = $this->posts["act"];
        return $data . " : " . $act;
    }
    private function shareProject()
    {
        echo "answer";
    }
}
?>