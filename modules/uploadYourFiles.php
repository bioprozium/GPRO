<?php
class uploadYourFiles
{
    public $gets;
    public $posts;
    public $files;
    function __construct()
    {
        $this->gets = $_GET;
        $this->posts = $_POST;
        $this->files = $_FILES;
        if(isset($this->gets["action"]))
        {
            if($this->gets["action"]=="checkuser")
            {
                if(isset($_SESSION["user_id"]) && isset($_SESSION["user_name"]))
                    echo "OK";
                else
                    echo "Please, log in first.";
            }
            exit;
        }
        if(!isset($_SESSION["user_id"]) && !isset($_SESSION["user_name"]))
        {
            header('Location: '. HTTP_PATH . 'index.php');
        }
        if(!empty($this->posts) && !empty($this->files))
        {
            require_once CLASSES."uploadingProccess.php";
            $uploadProc = new uploadingProcess;
            $uploadProc->setUserFilePanel();
        }
    }
    function SetPage()
    {
        gpro::$HTML["TITLE"]="GPro: Upload Your File('s)...";
        gpro::$HTML["MAIN_BLOCK"]=$this->getHTMLContent();
    }
    function getHTMLContent()
    {
        return parse_by_constants(file_get_contents(MAINBLOCK."Uploader-upload_your_files.html"));
    }
}
?>