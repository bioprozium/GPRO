<?php
class Graph
{
    public $graph;
    public $script;
    public $data;
    public $name;
    function __construct($data,$act)
    {
        $this->data = $data;
        switch($act)
        {
            case "length":
                $this->graph = $this->setBarChart();
                break;
            case "codonOccurrence":
                $this->graph = $this->setCodonBarChart();
                break;
            case "codonposition":
                $this->graph = $this->setCodonPosLineChart();
                break;
            case "gcContent":
                $this->graph = $this->setGCContentBarChart();
                break;
            case "cpgIsland":
                $this->graph = $this->setCpGIslandBarChart();
                break;
            case "aminoacids":
                $this->graph = $this->setAminoAcidBarChart();
                break;
            case "aminoacidspos":
                $this->graph = $this->setAminoAcidPosLineChart();
                break;
            default:
                return;
        }                
    }
    public function getGraph()
    {
        return $this->script;
    }
    private function setCodonBarChart()
    {
        $column = "";
        $value = array();
        foreach($this->data as $from=>$data)
        {
            if($from == "u")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"u");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $codon=>$data)
                        {
                            if($codon == "cdnCount")
                                continue;
                            $v = "'$codon'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$data,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
            if($from == "p")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"p");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $codon=>$data)
                        {
                            if($codon == "cdnCount")
                                continue;
                            $v = "'$codon'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$data,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
        }
        $column = rtrim($column, ",");
        $this->script = "
        <script src='script/js/c3.min.js'></script>
        <script src='script/js/d3.min.js' charset='utf-8'></script>
        <div id=\"chart\"></div>
        <script>
        var vals = [".implode(",",$value)."];
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    $column
                ],
                type: 'bar'
            },
            bar: {
                width: {
                    ratio: 0.5 // this makes bar width 50% of length between ticks
                }
                // or
                //width: 100 // this makes bar width 100px
            },
            axis: {
                x: {
                    tick: {
                        format: function (x) { return vals[x] }
                    }
                }
            }
        });
        </script>";
    }
    private function setCodonPosLineChart()
    {
        $column = "";
        foreach($this->data as $from=>$data)
        {
            if($from == "u")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"u");
                    foreach($prop as $p=>$dataArr)
                    {
                        foreach($dataArr as $codon=>$data)
                        {
                            $column .= "['$this->name $p $codon',";
                            foreach($data as $i=>$count)
                            {
                                $column .= "$count,";
                            }
                            $column = rtrim($column, ",");
                            $column .= "],";
                        }
                    }
                }
            }
            if($from == "p")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"p");
                    foreach($prop as $p=>$dataArr)
                    {
                        foreach($dataArr as $codon=>$data)
                        {
                            $column .= "['$this->name $p $codon',";
                            foreach($data as $i=>$count)
                            {
                                $column .= "$count,";
                            }
                            $column = rtrim($column, ",");
                            $column .= "],";
                        }
                    }
                }
            }
        }
        $column = rtrim($column, ",");
        $this->script = "
        <script src='script/js/c3.min.js'></script>
        <script src='script/js/d3.min.js' charset='utf-8'></script>
        <div id=\"chart\"></div>
        <script>
        var chart = c3.generate({
            bindto: '#chart',
            size: {
                height: 720,
                width: 720
            },
            data: {
                columns: [
                    $column
                ]
            }
        });
        </script>";
    }
    private function setBarChart()
    {
        $column = "";
        $value = array();
        foreach($this->data as $from=>$data)
        {
            if($from == "u")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"u");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $index=>$data)
                        {
                            $first = $index * 500;
                            $second = $first + 500;
                            $v = "'$first-$second'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$data,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
            if($from == "p")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"p");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $index=>$data)
                        {
                            $first = $index * 500;
                            $second = $first + 500;
                            $v = "'$first-$second'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$data,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
        }
        $column = rtrim($column, ",");
        $this->script = "
        <script src='script/js/c3.min.js'></script>
        <script src='script/js/d3.min.js' charset='utf-8'></script>
        <div id=\"chart\"></div>
        <script>
        var vals = [".implode(",",$value)."];
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    $column
                ],
                type: 'bar'
            },
            bar: {
                width: {
                    ratio: 0.5 // this makes bar width 50% of length between ticks
                }
                // or
                //width: 100 // this makes bar width 100px
            },
            axis: {
                x: {
                    tick: {
                        format: function (x) { return vals[x] }
                    }
                }
            }
        });
        </script>";
    }
    private function setGCContentBarChart()
    {
        $column = "";
        $value = array();
        foreach($this->data as $from=>$data)
        {
            if($from == "u")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"u");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $percent=>$count)
                        {
                            $v = "'$percent'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$count,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
            if($from == "p")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"p");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $percent=>$count)
                        {
                            $v = "'$percent'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$count,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
        }
        $column = rtrim($column, ",");
        $this->script = "
        <script src='script/js/c3.min.js'></script>
        <script src='script/js/d3.min.js' charset='utf-8'></script>
        <div id=\"chart\"></div>
        <script>
        var vals = [".implode(",",$value)."];
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    $column
                ],
                type: 'bar'
            },
            bar: {
                width: {
                    ratio: 0.5 // this makes bar width 50% of length between ticks
                }
                // or
                //width: 100 // this makes bar width 100px
            },
            axis: {
                x: {
                    tick: {
                        format: function (x) { return vals[x] }
                    }
                }
            },
            subchart: {
                show: true
            }
        });
        </script>";
    }
    private function setCpGIslandBarChart()
    {
        $column = "";
        $value = array();
        foreach($this->data as $from=>$data)
        {
            if($from == "u")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"u");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $percent=>$count)
                        {
                            $v = "'$percent'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$count,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
            if($from == "p")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"p");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $percent=>$count)
                        {
                            $v = "'$percent'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$count,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
        }
        $column = rtrim($column, ",");
        $this->script = "
        <script src='script/js/c3.min.js'></script>
        <script src='script/js/d3.min.js' charset='utf-8'></script>
        <div id=\"chart\"></div>
        <script>
        var vals = [".implode(",",$value)."];
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    $column
                ],
                type: 'bar'
            },
            bar: {
                width: {
                    ratio: 0.5 // this makes bar width 50% of length between ticks
                }
                // or
                //width: 100 // this makes bar width 100px
            },
            axis: {
                x: {
                    tick: {
                        format: function (x) { return vals[x] }
                    }
                }
            },
            subchart: {
                show: true
            }
        });
        </script>";
    }
    private function setAminoAcidBarChart()
    {
        $column = "";
        $value = array();
        foreach($this->data as $from=>$data)
        {
            if($from == "u")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"u");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $aa=>$count)
                        {
                            $v = "'$aa'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$count,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
            if($from == "p")
            {
                foreach($data as $id=>$prop)
                {
                    $this->name = $this->getOrgName($id,"p");
                    foreach($prop as $p=>$dataArr)
                    {
                        $column .= "['$this->name $p',";
                        foreach($dataArr as $aa=>$count)
                        {
                            $v = "'$aa'";
                            if(!in_array($v,$value))
                                $value[] = $v;
                            $column .= "$count,";
                        }
                        $column = rtrim($column, ",");
                        $column .= "],";
                    }
                }
            }
        }
        $column = rtrim($column, ",");
        $this->script = "
        <script src='script/js/c3.min.js'></script>
        <script src='script/js/d3.min.js' charset='utf-8'></script>
        <div id=\"chart\"></div>
        <script>
        var vals = [".implode(",",$value)."];
        var chart = c3.generate({
            bindto: '#chart',
            data: {
                columns: [
                    $column
                ],
                type: 'bar'
            },
            bar: {
                width: {
                    ratio: 0.5 // this makes bar width 50% of length between ticks
                }
                // or
                //width: 100 // this makes bar width 100px
            },
            axis: {
                x: {
                    tick: {
                        format: function (x) { return vals[x] }
                    }
                }
            },
            subchart: {
                show: true
            }
        });
        </script>";
    }
    private function setAminoAcidPosLineChart()
    {

    }
    private function getOrgName($id,$from)
    {
        if($from == "u")
        {
            $sql = "SELECT original_name FROM user_files WHERE id = $id";
            $res = mysqlDB::$_connectionGDB->query($sql);
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $row = $row[0];
            $name=$row["original_name"];
            return $name;
        }
        else if($from == "p")
        {
            $sql = "SELECT organism_name FROM species WHERE id = $id";
            $res = mysqlDB::$_connectionGDB->query($sql);
            $row = $res->fetch_all(MYSQLI_ASSOC);
            $row = $row[0];
            $name=$row["organism_name"];
            return $name;
        }

    }

}
?>