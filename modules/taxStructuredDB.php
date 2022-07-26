<?php
class taxStructuredDB
{
    public $gets;
    public $posts;
    public $id;
    public $taxPage;
    private $title;
    private $line;
    private $group;
    private $user_id;
    private $user_name;
    public $list;
    public $mainList;
    function __construct()
    {
        $this->gets = $_GET;
        $this->posts = $_POST;
        if(isset($_SESSION['user_id']) && isset($_SESSION['user_name']))
        {
            $this->user_id = $_SESSION['user_id'];
            $this->user_name = $_SESSION['user_name'];
        }
        if(isset($this->gets["id"]))
            $this->id = $this->gets["id"];
        else
            $this->id = 1;
        gpro::$HTML["LINKS"] = "<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css'>";
        gpro::$HTML["BODY_SCRIPT_BLOCK"]="<script src='https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js'></script>";
        if(isset($this->gets['delbuf']))
        {
            $this->deleteBuffer();
        }
        if(!empty($this->user_id) && !empty($this->user_name))
        {
            if(isset($this->posts["addFileToList"]) && $this->posts["addFileToList"]==true)
            {
                $this->addSelectedFilesToList(true);
            }
            $this->list = $this->checkSelectedFiles(true);
            if($this->list != "")
            {
                $this->mainList=file_get_contents(MAINBLOCK.'Tax-selected_files_card.html');
                if(array_key_exists("USER_SELECTED_FILES",$this->list))
                {
                    $this->mainList = tools::parse_by_blocks("USER_SELECTED_FILES",$this->mainList,$this->list["USER_SELECTED_FILES"]);
                }
                if(array_key_exists("USER_SELECTED_TAX_FILES",$this->list))
                {
                    $this->mainList = tools::parse_by_blocks("USER_SELECTED_TAX_FILES",$this->mainList,$this->list["USER_SELECTED_TAX_FILES"]);
                }
                $this->mainList = tools::remove_empty_blocks($this->mainList);
            }
            else
            {
                $this->mainList = "";
            }
        }
        else
        {
            if(isset($this->posts["addFileToList"]) && $this->posts["addFileToList"]==true)
            {
                $this->addSelectedFilesToList(false);
            }
            $this->list = $this->checkSelectedFiles(false);
            if($this->list != "")
            {
                $this->mainList=file_get_contents(MAINBLOCK.'Tax-selected_files_card.html');
                if(array_key_exists("USER_SELECTED_TAX_FILES",$this->list))
                {
                    $this->mainList = tools::parse_by_blocks("USER_SELECTED_TAX_FILES",$this->mainList,$this->list["USER_SELECTED_TAX_FILES"]);
                }
                $this->mainList = tools::remove_empty_blocks($this->mainList);
            }
            else
            {
                $this->mainList = "";
            }
        }
        if(isset($this->posts['analyzing']) && $this->posts['analyzing']==true)
        {
            if(!empty($this->user_id) && !empty($this->user_name))
            {
                if(is_dir(DATA."users/user_$this->user_id/"))
                {
                    $data=file_get_contents(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json");
                    if(empty($data))
                    {
                        return false;
                    }
                    else
                    {
                        header('Location:'.HTTP_PATH.'?m=anl');
                    }
                }
            }
            else
            {
                $tempUser = $_COOKIE['PHPSESSID'];
                if(is_dir(DATA."public/"))
                {
                    if(!file_exists(DATA."public/$tempUser-selectedFiles.json"))
                    {
                        return false;
                    }
                    else
                    {
                        $data=file_get_contents(DATA."public/$tempUser-selectedFiles.json");
                        if(empty($data))
                        {
                            return false;
                        }
                        else
                        {
                            header('Location:'.HTTP_PATH.'?m=anl');
                        }
                    }
                }
            }
        }
    }
    function SetPage()
    {
        if(isset($_GET["ajax"]))
        {
            $this->get_species();
        }
        else
        {
            $this->setData($this->id);
            gpro::$HTML["TITLE"] = $this->title;
            $taxtable = tools::parse_by_constants(file_get_contents(MAINBLOCK."Tax-Table.html"));
            $this->taxPage = tools::parse_by_blocks("LINEAGE",file_get_contents(MAINBLOCK."Tax-mainBlock.html"),$this->line);
            $this->taxPage = tools::parse_by_blocks("GROUPS",$this->taxPage,$this->group);
            $this->taxPage = tools::parse_by_blocks("SELECTED_FILES_LIST",$this->taxPage,$this->mainList);
            $this->taxPage = tools::parse_by_constants(tools::parse_by_blocks("DATA",$this->taxPage,$taxtable));
        }
        gpro::$HTML["MAIN_BLOCK"] = $this->taxPage;
    }
    function setData($id)
    {
        $data = Taxonomy::extensiveInformation($id);
        $this->title = $data["title"];
        $this->line = $this->getLine($data["lineage"]);
        $this->group = $this->getGroup($data["group"],$this->title);
    }
    function getLine($arr)
    {
        $_line="<div class='btn-group btn-group-sm container-sm' role='group'>";
        foreach($arr as $id=>$name)
        {
            $_line.="<a href='{|HTTP_PATH|}?m=txstrdb&id=$id' class='btn btn-outline-success'>$name >>></a> ";
        }
        return $_line.="</div>";
    }
    function getGroup($arr, $title)
    {
        $_groupItem = "<a href='{|HTTP_PATH|}?m=txstrdb&id=$this->id' class='list-group-item active' style='text-align: center;'>$title</a>";
        foreach($arr as $index=>$group)
        {
            $_groupItem .= "<a href='{|HTTP_PATH|}?m=txstrdb&id=$group[id]'class='list-group-item list-group-item-action list-group-item-success'>$group[scientificName]</a>";
        }
        $_group = "<div class='card list-group'>$_groupItem</div>";
        return $_group;
    }
    function get_species()
    {
        $out=[];
        $data=Taxonomy::speciesInformation($this->id);
        $start=$_GET["start"];
        $len=$_GET["length"];
        $end=$start+$len;
        $pos=0;
        $sp=array();
        foreach($data as $species)
        {
            if($pos<$start)
            {
                $pos++;
                continue;
            }
            if($pos>$end)
                break;
            
            $sp[0]="<input type=\"checkbox\" id=\"species\" name=\"tax_sp_id[]\" value=\"$species[id]\">";
            $sp[1]="<a href=\"".HTTP_PATH."?m=anl&sp_id=$species[id]\">$species[organism_name]</a>";
            $sp[2]="$species[refseq_category]";
            $sp[3]="$species[assembly_level]";
            $sp[4]="$species[seq_rel_date]";
            $sp[5]="$species[division]";
            $out[]=$sp;
            $pos++;
        }
        $json=array("recordsTotal"=>count($data), "recordsFiltered"=>count($data), "data"=>$out);
        echo json_encode($json);
        exit;
    }
    function checkSelectedFiles($ifuser)
    {
        if($ifuser==true)
        {
            if(is_dir(DATA."users/user_$this->user_id/"))
            {
                if(!file_exists(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json"))
                {
                    return;
                }
                $list = file_get_contents(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json");
                $list = json_decode($list, true);
                if($list==false)
                {
                    return;
                }
                if(array_key_exists("user_files",$list))
                {
                    $html_list["USER_SELECTED_FILES"] = "<h6 class='card-subtitle mb-2 text-muted'>Files from user panel</h6>";
                    foreach($list['user_files'] as $id=>$name)
                    {
                        $html_list["USER_SELECTED_FILES"] .= "<p class='card-text'><i class='bi bi-pin-angle-fill'></i> $name[name]</p>";
                    }
                }
                if(array_key_exists("tax_files",$list))
                {
                    $html_list["USER_SELECTED_TAX_FILES"] = "<h6 class='card-subtitle mb-2 text-muted'>Files from TaxGDatabase</h6>";
                    foreach($list["tax_files"] as $id)
                    {
                        $html_list["USER_SELECTED_TAX_FILES"] .= "<p class='card-text'><i class='bi bi-pin-angle-fill'></i> $id[name]</p>";
                    }
                }
                return $html_list;
            }
            else
            {
                return;
            }
        }
        else if($ifuser==false)
        {
            $tempUser = $_COOKIE['PHPSESSID'];
            if(is_dir(DATA."public/"))
            {
                if(!file_exists(DATA."public/$tempUser-selectedFiles.json"))
                {
                    return;
                }
                $list = file_get_contents(DATA."public/$tempUser-selectedFiles.json");
                $list = json_decode($list, true);
                if($list==false)
                {
                    return;
                }
                if(array_key_exists("tax_files",$list))
                {
                    $html_list["USER_SELECTED_TAX_FILES"] = "<h6 class='card-subtitle mb-2 text-muted'>Files from TaxGDatabase</h6>";
                    foreach($list["tax_files"] as $id)
                    {
                        $html_list["USER_SELECTED_TAX_FILES"] .= "<p class='card-text'><i class='bi bi-pin-angle-fill'></i> $id[name]</p>";
                    }
                    return $html_list;
                }
                else
                {
                    return;
                }
            }
        }
    }
    //////////////////////SERVER_SIDE_FUNCTIONS////////////////////////
    function addSelectedFilesToList($ifuser)
    {
        if(isset($this->posts["tax_sp_id"]))
        {
            if($ifuser==true)
            {
                if(!file_exists(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json"))
                {
                    $list = fopen(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json","w");
                    fclose($list);
                }
                $list = file_get_contents(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json");
                if($list != false)
                    $list = json_decode($list,true);
                else
                    $list = array();
                foreach($this->posts["tax_sp_id"] as $index=>$sp_id)
                {
                    $query = "SELECT id, organism_name, ftp_path FROM species WHERE id = $sp_id";
                    $result = mysqlDB::$_connectionGDB->query($query);
                    if($result->num_rows < 1)
                    {
                        $query = "SELECT id, organism_name, ftp_path FROM subspecies WHERE id = $sp_id";
                        $subresult = mysqlDB::$_connectionGDB->query($query);
                        if($subresult->num_rows < 1)
                        {
                            return "";
                        }
                        else
                        {
                            //code!!!!!!!!!!!!
                        }
                    }
                    else
                    {
                        $row = $result->fetch_all(MYSQLI_ASSOC);
                        $row = $row[0];
                        $temp_id = $row["id"];
                        $temp_org_name = $row["organism_name"];
                        $temp_ftp_path = $row["ftp_path"];
                        $list["tax_files"]["$temp_id"] =  array("name"=>$temp_org_name,"ftp_path"=>$temp_ftp_path);
                    }
                }
                $list = json_encode($list);
                file_put_contents(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json", $list);
            }
            else if($ifuser==false)
            {
                $tempUser = $_COOKIE['PHPSESSID'];
                if(!file_exists(DATA."public/$tempUser-selectedFiles.json"))
                {
                    $list = fopen(DATA."public/$tempUser-selectedFiles.json","w");
                    fclose($list);
                }
                $list = file_get_contents(DATA."public/$tempUser-selectedFiles.json");
                if($list != false)
                    $list = json_decode($list,true);
                else
                    $list = array();
                foreach($this->posts["tax_sp_id"] as $index=>$sp_id)
                {
                    $query = "SELECT id, organism_name, ftp_path FROM species WHERE id = $sp_id";
                    $result = mysqlDB::$_connectionGDB->query($query);
                    if($result->num_rows < 1)
                    {
                        $query = "SELECT id, organism_name, ftp_path FROM subspecies WHERE id = $sp_id";
                        $subresult = mysqlDB::$_connectionGDB->query($query);
                        if($subresult->num_rows < 1)
                        {
                            return "";
                        }
                        else
                        {
                            $row = $subresult->fetch_all(MYSQLI_ASSOC);
                            $row = $row[0];
                            $temp_id = $row["id"];
                            $temp_org_name = $row["organism_name"];
                            $temp_ftp_path = $row["ftp_path"];
                            $list["tax_files"]["$temp_id"] =  array("name"=>$temp_org_name,"ftp_path"=>$temp_ftp_path);
                        }
                    }
                    else
                    {
                        $row = $result->fetch_all(MYSQLI_ASSOC);
                        $row = $row[0];
                        $temp_id = $row["id"];
                        $temp_org_name = $row["organism_name"];
                        $temp_ftp_path = $row["ftp_path"];
                        $list["tax_files"]["$temp_id"] =  array("name"=>$temp_org_name,"ftp_path"=>$temp_ftp_path);
                    }
                }
                $list = json_encode($list);
                file_put_contents(DATA."public/$tempUser-selectedFiles.json", $list);
            }
        }
        else
        {
            return "";
        }
    }
    function deleteBuffer()
    {
        if(!empty($this->user_id) && !empty($this->user_name))
        {
            //user
            if(file_exists(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json"))
            {
                if(unlink(DATA."users/user_$this->user_id/user_$this->user_id-selectedFiles.json"))
                {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
                else
                {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
            }
        }
        else
        {
            //public
            $filename = $_COOKIE['PHPSESSID']."-selectedFiles.json";
            if(file_exists(DATA."public/$filename"))
            {
                if(unlink(DATA."public/$filename"))
                {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
                else
                {
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
            }
        }
        //header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
?>