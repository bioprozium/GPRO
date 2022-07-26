<?php
class codonOccurrence
{
    public $result;
    function __construct($propArr="",$spropArr="", $filter="")
    {
        $this->calculation($propArr,$spropArr);
    }
    private function calculation($propertiesArray,$userFilePropertiesArray)
    {
        if($propertiesArray != "")
        {
            foreach($propertiesArray as $id=>$properties)
            {
                if(!is_dir(DATA."public/$id"))
                    return;
                $this->commandMaker($id, "p");
            }
        }
        if($userFilePropertiesArray != "")
        {
            foreach($userFilePropertiesArray as $id=>$properties)
            {
                $this->commandMaker($id, "u");
            }
        }
    }
    private function commandMaker($id, $from)
    {
        $fileSource = "";
        $userFile = "";
        if($from == 'u')
        {
            $sql = "SELECT random_name FROM user_files WHERE id = $id";
            $res = mysqlDB::$_connectionGDB->query($sql);
            if($res->num_rows > 0)
            {    
                $row = $res->fetch_all(MYSQLI_ASSOC);
                $userFile = $row[0]["random_name"];
            }
            $fileSource = DATA."users/user_$_SESSION[user_id]/$userFile";
        }
        if($from == 'p')
            $fileSource = DATA."public/$id/$id.cds";
        $command = '"{\"operation\":\"calc\",\"fileSource\":\"'.$fileSource.'\",\"action\":\"cdnocc\",\"seqType\":\"CDS\",\"action_type\":\"\",\"filter\":false}"';
        $this->result[$from][$id]["cds"] = $this->commandToProgramm($command);
    }
    private function commandToProgramm($jsonstring)
    {
        $output = shell_exec("c://server/data/htdocs/GPRO/classes/anlFuncs/GPro.exe $jsonstring");
        $json = json_decode($output);
        return $json;
    }
    public function getResult()
    {
        return $this->result;
    }
}
?>