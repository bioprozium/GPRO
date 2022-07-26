<?php
class userFilePanel
{
    public $gets;
    public $posts;
    public $user_id;
    public $user_name;
    public $dbMessage;
    public $dataTable;
    function __construct()
    {
        $this->gets = $_GET;
        $this->posts = $_POST;
        tools::checkIfUserLoggedIn();
        $this->user_id = $_SESSION["user_id"];
        $this->user_name = $_SESSION["user_name"];
        $this->dbMessage = $this->setDataTable();
        if(isset($this->posts["fileToAnalyze"]) || isset($this->posts["taxtable"]))
        {
            $this->transfer();
        }
        if(isset($this->posts["action"]) && isset($this->posts["id"]) && !empty($this->user_id))
        {
            $this->deleteFile();
        }
    }
    function SetPage()
    {
        $panel = tools::parse_by_blocks("USER_NAME",file_get_contents(MAINBLOCK."user-file_panel.html"), $_SESSION["user_name"]);
        $panel = tools::parse_by_constants(tools::parse_by_blocks("DATA_TABLE",$panel, $this->dbMessage));
        gpro::$HTML["TITLE"] = "GPro: $this->user_name's file panel";
        gpro::$HTML["MAIN_BLOCK"] = $panel;
    }
    function setDataTable()
    {
        $query = "SELECT id, original_name, random_name, size, type, attachment_date FROM user_files WHERE user_id=$this->user_id";
        $result = mysqlDB::$_connectionGDB->query($query);
        if($result->num_rows > 0)
        {
            $n = 0;
            while($row = $result->fetch_assoc())
            {
                $n++;
                $s = $row["size"]/1024/1024;
                $this->dataTable .= "
                    <tr id='tr_$row[id]'>
                        <td>$n</td>
                        <td><input type='checkbox' class='fileCheckBox' name='fileToAnalyze[]' value='$row[id]'></td>
                        <td>$row[original_name]</td>
                        <td>$s mb</td>
                        <td>$row[type]</td>
                        <td>$row[attachment_date]</td>
                        <td><button class='btn btn-outline-success' onclick='deleteSelectedFile($row[id])'><i class='bi bi-trash'></i></button></td>
                    </tr>
                ";
            }
            return $this->dataTable;
        }
        else
        {
            return "The user does not have any data files yet...";
        }
    }
    function transfer()
    {
        if(isset($this->posts["fileToAnalyze"]) && isset($this->posts["taxtable"]))
        {
            if(isset($_SESSION['user_id']) && isset($_SESSION['user_name']))
            {
                $this->saveSelectedDataToList();
                header('Location:'.HTTP_PATH.'?m=txstrdb');
            }
            else
            {
                header('Location:'.HTTP_PATH);
            }
        }
        else if(isset($this->posts["fileToAnalyze"]) && !isset($this->posts["taxtable"]))
        {
            if(isset($_SESSION['user_id']) && isset($_SESSION['user_name']))
            {
                $this->saveSelectedDataToList();
                //header('HTTP/1.1 307 Temporary Redirect');
                header('Location:'.HTTP_PATH.'?m=anl');
            }
            else
            {
                header('Location:'.HTTP_PATH);
            }
        }
        else if(!isset($this->posts["fileToAnalyze"]) && isset($this->posts["taxtable"]))
        {
            if(isset($_SESSION['user_id']) && isset($_SESSION['user_name']))
            {
                if(is_dir(DATA."users/user_$this->user_id/"))
                {
                    if(file_exists(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json"))
                    {
                        file_put_contents(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json", "");
                    }
                }
                header('Location:'.HTTP_PATH.'?m=txstrdb');
            }
            else
            {
                header('Location:'.HTTP_PATH);
            }
        }
    }
    function deleteFile()
    {
        if($this->posts["action"] == "deleteFile")
        {
            $delId = $this->posts["id"];
            $query = "SELECT random_name FROM user_files WHERE id=$delId AND user_id=$this->user_id";
            $res = mysqlDB::$_connectionGDB->query($query);
            if($res->num_rows > 0)
            {
                $row = $res->fetch_assoc();
                $delFileRandomName = $row["random_name"];
                $deleteFromTableQuery = "DELETE FROM user_files WHERE id=$delId AND user_id=$this->user_id";
                if(!unlink(DATA."users/user_$this->user_id/$delFileRandomName"))
                {

                }
                else
                {
                    mysqlDB::$_connectionGDB->query($deleteFromTableQuery);
                    exit;
                }
            }
            else
            {
                exit;
            }
        }
    }
    ///////////////////////// TOOLS ///////////////////////////
    function saveSelectedDataToList()
    {
        if(is_dir(DATA."users/user_$this->user_id/"))
        {
            $list = fopen(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json", 'w') or die("Unable to open file!");
            $selectedFiles = array();
            $fileId = implode(",",$this->posts["fileToAnalyze"]);
            $query = "SELECT * FROM user_files WHERE id IN ($fileId)";
            $res = mysqlDB::$_connectionGDB->query($query);
            if($res->num_rows > 0)
            {
                while($row=$res->fetch_assoc())
                {
                    $selectedFiles["user_files"]["$row[id]"]["name"] = $row["original_name"];
                    $selectedFiles["user_files"]["$row[id]"]["type"] = $row["type"];
                }
            }
            $selectedFiles = json_encode($selectedFiles);
            fwrite($list,$selectedFiles);
            fclose($list);
        }
    }
}
?>