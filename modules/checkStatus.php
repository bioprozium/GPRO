<?php
class checkStatus
{
    public $id;
    public $res;
    function __construct()
    {
        $this->id = $_POST["id"];
    }
    public function SetPage()
    {
        $this->res = $this->checkingStatus();
        echo $this->res;
        exit;
    }
    public function checkingStatus()
    {
        if(!is_dir(DATA."public/$this->id/"))
            return 0;
        $dir = DATA."public/$this->id/";
        $statusFile = fopen($dir.$this->id."-logFile.txt", "r");
        $buffer = fgets($statusFile, 4096);
        $status = explode(";",$buffer);
        $status = $status[sizeof($status)-2];
        $status = explode(":",$status);
        if($status[1]==0)
            return 0;
        else if($status[1]==1)
            return 1;
        else
            return 0;
        fclose($statusFile);
    }
}
?>