<?php
class uploadingProcess
{
    public $gets;
    public $posts;
    public $files;
    public $user_id;
    public $user_name;
    public $fileMessage;
    function __construct()
    {
        $this->gets = $_GET;
        $this->posts = $_POST;
        $this->files = $_FILES;
        tools::checkIfUserLoggedIn();
        $this->user_id = $_SESSION["user_id"];
        $this->user_name = $_SESSION["user_name"];
    }
    function setUserFilePanel()
    {
        if(!empty($this->posts) && !empty($this->files))
        {
            $query = "SELECT * FROM users WHERE user_id = $this->user_id AND user_name = '$this->user_name'";
            $result = mysqlDB::$_connectionGDB->query($query);
            if($result->num_rows > 0)
            {
                if(!is_dir(DATA."users/user_$this->user_id/"))
                    mkdir(DATA."users/user_$this->user_id/");
                $targetDir = DATA."users/user_$this->user_id/";
                $len = count($this->files["fileToUpload"]["name"]);
                for($i = 0; $i < $len; $i++)
                {
                    $name = $this->files["fileToUpload"]["name"][$i];
                    $size = $this->files["fileToUpload"]["size"][$i];
                    $dataType = $this->posts["dataType"][$i];
                    $randomName = tools::generateRandomString();
                    $ext = strtolower(pathinfo($name,PATHINFO_EXTENSION));
                    $targetFile = $targetDir.$randomName.'.'.$ext;
                    if(file_exists($targetFile))
                    {
                        //code
                    }
                    $date=date('Y-m-d H:i:s');
                    $query = "INSERT INTO user_files (original_name, random_name, size, type, user_id, attachment_date) VALUES ('$name','$randomName.$ext',$size,$dataType,$this->user_id, '$date')";
                    mysqlDB::$_connectionGDB->query($query);
                    if(copy($this->files["fileToUpload"]["tmp_name"][$i],$targetFile))
                    {
                        $this->fileMessage .= "File has been uploaded successfully";
                    }
                    else
                    {
                        $this->fileMessage .= "$this->files[fileToUpload][name][$i] has been not uploaded!";
                    }
                }
                header('Location: '. HTTP_PATH . '?m=ufp');
            }
            else
            {
                exit;
            }
        }
    }
}
?>