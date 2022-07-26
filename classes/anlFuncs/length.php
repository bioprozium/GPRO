<?php
class length
{
    //public $fileSource;
    //public $filter;
    public $result = array();
    //public $graph;
    public $filter;
    function __construct($propArr="",$spropArr="", $filter="")
    {
        //print_r($spropArr);
        //require_once CLASSES."Graph.php";
        $this->filter = $filter;
        $this->calculation($propArr,$spropArr);
        //$this->graph = new Graph($this->result);
        //return $this->result;
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
    private function commandMakerOld($seqProp, $id)
    {
        $fileSource = DATA."public/$id/$id.$seqProp";
        $commandWithOutFilter = '"{\"operation\":\"calc\",\"fileSource\":\"'.$fileSource.'\",\"action\":\"len\",\"seqType\":\"'.$seqProp.'\",\"action_type\":\"\",\"filter\":false}"';
        $this->result[$id]["$seqProp"] = $this->commandToProgramm($commandWithOutFilter);
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
        $command = '"{\"operation\":\"calc\",\"fileSource\":\"'.$fileSource.'\",\"action\":\"len\",\"seqType\":\"'.$seqProp.'\",\"action_type\":\"\",\"filter\":false}"';
        $this->result[$from][$id]["$seqProp"] = $this->commandToProgramm($command);
        
    }
    private function commandToProgramm($jsonstring)
    {
        $output = shell_exec("c://server/data/htdocs/GPRO/classes/anlFuncs/GPro.exe $jsonstring");
        $json = json_decode($output);
        return $json;
    }
    private function getGraph()
    {
        foreach($this->result as $id=>$sprop)
        {
            foreach($sprop as $index=>$data)
            {

            }
        }
        $graph = "<div id='chart'></div>
        var chart = c3.generate({
        data: {
            columns: [
                ['data1Total', 30, 200, 100, 400, 150, 250],
                ['data1Selected', 30, 200, 100, 400, 150, 250],
                ['data2Total', 130, 100, 140, 200, 150, 50]
                ['data2Selected', 130, 100, 140, 200, 150, 50]
                $this->data
            ],
            type: 'bar'
        },
        bar: {
            width: {
                ratio: 0.5 // this makes bar width 50% of length between ticks
            }
            // or
            //width: 100 // this makes bar width 100px
        }
    });";
    }
    //'"{\"operation\":\"calc\",\"fileSource\":\"'.$fileSource.'\",\"action\":\"len\",\"seqType\":\"cdna\",\"action_type\":\"\",\"filter\":false}"';
}
?>