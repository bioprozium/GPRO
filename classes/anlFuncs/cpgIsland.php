<?php
class cpgIsland
{
    public $result=array();
    function __construct($propArr="",$spropArr="", $filter="")
    {
        $this->calculation($propArr,$spropArr);
    }
    public function getResult()
    {
        return $this->result;
    }
    private function calculation($propertiesArray,$userFilePropertiesArray)
    {
        if($propertiesArray != "")
        {
            foreach($propertiesArray as $id=>$properties)
            {
                if(!is_dir(DATA."public/$id"))
                    return;
                foreach($properties as $index=>$pr)
                {
                    switch($pr)
                    {
                        case "cdna":
                            $this->commandMaker("cdna", $id, "p");
                            break;
                        case "cds":
                            $this->commandMaker("cds",$id, "p");
                            break;
                        case "utr5":
                            $this->commandMaker("utr5",$id, "p");
                            break;
                        case "utr3":
                            $this->commandMaker("utr3",$id, "p");
                            break;
                        case "chr":
                            $this->commandMaker("chr",$id, "p");
                            break;
                        default:
                            return false;
                    }
                }
            }
        }
        if($userFilePropertiesArray != "")
        {
            foreach($userFilePropertiesArray as $id=>$properties)
            {
                foreach($properties as $index=>$pr)
                {
                    switch($pr)
                    {
                        case "CDNA":
                            $this->commandMaker("cdna", $id, "u");
                            break;
                        case "CDS":
                            $this->commandMaker("cds",$id, "u");
                            break;
                        case "UTR5":
                            $this->commandMaker("utr5",$id, "u");
                            break;
                        case "UTR3":
                            $this->commandMaker("utr3",$id, "u");
                            break;
                        case "CHR":
                            $this->commandMaker("chr",$id, "u");
                            break;
                        default:
                            return false;
                    }
                }
            }
        }
    }
    private function commandMaker($seqProp, $id, $from)
    {
        //echo $id;
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
            $fileSource = DATA."public/$id/$id.$seqProp";
        $command = '"{\"operation\":\"calc\",\"fileSource\":\"'.$fileSource.'\",\"action\":\"cpg\",\"seqType\":\"'.$seqProp.'\",\"action_type\":\"\",\"filter\":false}"';
        $this->result[$from][$id]["$seqProp"] = $this->commandToProgram($command);
        
    }
    private function commandToProgram($jsonstring)
    {
        $output = shell_exec("c://server/data/htdocs/GPRO/classes/anlFuncs/GPro.exe $jsonstring");
        $json = json_decode($output);
        return $json;
    }
}
?>