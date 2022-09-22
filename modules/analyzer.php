<?php
class analyzer
{
    public $page;
    public $gets;
    public $posts;
    //User properties
    public $user_id;
    public $user_name;
    public $userType;
    //Files
    public $data;
    public $userFiles = array();
    public $publicFiles = array();
    //Lists
    public $listMaker;
    public $seqPropList;
    public $seqFuncList;
    //action
    public $prop = "";
    public $sprop = "";
    public $filter = "";
    //result
    public $graphTable;
    //Project tools
    public $projCTRL;
    function __construct()
    {
        $this->page = tools::parse_by_constants(file_get_contents(MAINBLOCK.'Anl-mainBlock.html'));
        gpro::$HTML["BODY_SCRIPT_BLOCK"]="<script src='script/js/anl.js'></script>";
        $this->gets = $_GET;
        $this->posts = $_POST;
        //$this->projCTRL = "";
        $this->userType = $this->checkUserExists();
        //Get Files
        if($this->userType==true)
        {
            //usersite code...
            $this->getUserFiles(true);
            $this->projCTRL = $this->setProjCTRLdata();
        }
        else if($this->userType==false)
        {
            //publicsite code...
            $this->getUserFiles(false);
        }
        else if($this->userType==null)
        {
            //redirect to home  !SECURITY!
            header('Location: '.HTTP_PATH);
        }
        $this->downloadingProcess();
        //List Maker
        require_once CLASSES."analyzerListMaker.php";
        $this->listMaker = new anlListMaker($this->data);
        $this->seqPropList = $this->listMaker->seqProp();
        $this->seqFuncList = $this->listMaker->seqFunc();
        if(isset($this->posts["action"]))
        {
            if((!isset($this->posts["prop"])) && (!isset($this->posts["sprop"])))
                return;
            if(isset($this->posts["prop"]))
                $this->prop = $this->posts["prop"];
            if(isset($this->posts["sprop"]))
                $this->sprop = $this->posts["sprop"];
            $this->analyzing();
        }
    }
    public function getUserFiles($type)
    {
        if($type==true)
        {
            if(is_dir(DATA.'users/user_'.$this->user_id.'/'))
            {
                $this->data = json_decode(file_get_contents(DATA.'users/user_'.$this->user_id.'/user_'.$this->user_id.'-selectedFiles.json'),true);
                //var_dump($this->data["tax_files"]);
                foreach($this->data as $fsource=>$arr)
                {
                    if($fsource=='user_files')
                    {
                        foreach ($arr as $uid=>$fname)
                        {
                            $sql = "SELECT original_name, random_name, type FROM user_files WHERE id=$uid";
                            $res = mysqlDB::$_connectionGDB->query($sql);
                            if($res->num_rows > 0)
                            {
                                while($row=$res->fetch_assoc())
                                {
                                    $this->userFiles["$uid"] = $row;
                                }
                            }
                        }
                    }
                    else if($fsource=='tax_files')
                    {
                        $this->publicFiles = $arr;
                    }
                }
            }
        }
        else if($type==false)
        {
            $tempUser = $_COOKIE['PHPSESSID'];
            if(is_dir(DATA."public/"))
            {
                if(!file_exists(DATA."public/$tempUser-selectedFiles.json"))
                    return false;
                $this->data = json_decode(file_get_contents(DATA."public/$tempUser-selectedFiles.json"),true);
                $this->publicFiles = $this->data["tax_files"];
            }
        }
    }
    public function SetPage()
    {
        gpro::$HTML['TITLE'] = 'GPro: Analyzing>>>';
        gpro::$HTML["LINKS"] = '<link href="script/css/c3.min.css" rel="stylesheet">';

        $this->page = tools::parse_by_blocks("OBJECTS",$this->page,$this->setList());
        $this->page = tools::parse_by_blocks("OBJECTS_N_SEQ_PROP",$this->page,$this->seqPropList);
        $this->page = tools::parse_by_blocks("SEQ_FUNCTIONS",$this->page,$this->seqFuncList);
        $this->page = tools::parse_by_blocks("PROJ_CTRL",$this->page,$this->projCTRL);
        if($this->graphTable != null)
        $this->page = tools::parse_by_blocks("GRAPH",$this->page,$this->graphTable);
        $this->page = tools::remove_empty_blocks($this->page);
        gpro::$HTML['MAIN_BLOCK'] = $this->page;
    }
    public function downloadingProcess()
    {
        if(!empty($this->publicFiles))
        {
            foreach($this->publicFiles as $id=>$finfo)
            {
                $post_data = http_build_query($finfo)."&id=$id";
                $fconn = fsockopen("localhost",80,$errno, $errstr, 10);       //MUST BE CHANGED [ssl://]
                if(!$fconn)
                {
                    echo "$errstr ($errno)
                    \n";
                }
                else
                {
                    $out = "POST http://localhost/GPRO/classes/fileDownload.php HTTP/1.1\r\n";
                    $out .= "Host: localhost/GPRO/classes\r\n";
                    $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
                    $out .= "Content-length: " . strlen($post_data) . "\r\n";
                    $out .= "Connection: Close\r\n\r\n";
                    $out .= $post_data . "\r\n\r\n";
                    fwrite($fconn, $out);
                    fclose($fconn);
                }
            }
        }
    }
    public function analyzing()
    {
        $action = $this->posts["action"];
        if(isset($this->posts["filter"]))
            $this->filter = $this->posts["filter"];
        require_once CLASSES."analyzingProcess.php";
        $anlProc = new analyzingProcess($action,$this->prop,$this->sprop,$this->filter);
        $this->graphTable = $anlProc->getScript();
    }
    ////////////////////////////TOOLS//////////////////////////////////////////
    function checkUserExists()
    {
        if(isset($_SESSION["user_id"]) && isset($_SESSION["user_name"]))
        {
            $this->user_id = $_SESSION["user_id"];
            $this->user_name = $_SESSION["user_name"];
            $query = "SELECT * FROM users WHERE user_id = $this->user_id && user_name = '$this->user_name'";
            $res = mysqlDB::$_connectionGDB->query($query);
            if($res->num_rows > 0)
            {
                return true;
            }
            else
            {
                return null;
            }
        }
        else
        {
            return false;
        }

    }
    private function setList()
    {
        $list = "";
        if(!empty($this->userFiles))
        {
            $list .= "<div class='col-4'><ol class='list-group list-group-numbered'>";
            foreach($this->userFiles as $id=>$properties)
            {
                $list .= "<li class='list-group-item list-group-item-success'>$properties[original_name]</li>";
            }
            $list .= "</ol></div>";
        }
        if(!empty($this->publicFiles))
        {
            $list .= "<div class='col-4'><ol class='list-group list-group-numbered'>";
            foreach($this->publicFiles as $id=>$properties)
            {
                $list .= "<li class='list-group-item list-group-item-info'selected-object='$id'>$properties[name]
                <div class='spinner-border spinner-border-sm text-success' role='status' selected-status='$id'>
                <span class='visually-hidden'>Loading...</span>
              </div></li>
                ";
            }
            $list .= "</ol></div>";
        }
        return $list;
    }
    private function setProjCTRLdata()
    {
        if(isset($this->posts["action"]))
            $data = tools::parse_by_blocks("ACTION",file_get_contents(MAINBLOCK."project-Control.html"),$this->posts["action"]);
        else
        $data = tools::parse_by_blocks("ACTION",file_get_contents(MAINBLOCK."project-Control.html"),"");
        return $data;
    }
}
?>