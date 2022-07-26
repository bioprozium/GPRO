<?php
class Taxonomy
{
    public $gets;
    public $posts;
    static $sciName;
    static $sName;
    static $dbline;
    function __construct()
    {
        $this->gets = $_GET;
        $this->posts = $_POST;
    }
    public static function extensiveInformation($id)
    {
        $extensiveData = array(
            "title"=>self::titleInformation($id),
            "lineage"=>self::lineInformation($id),
            "group"=>self::groupInformation($id),
            "species"=>self::speciesInformation($id)
        );
        return $extensiveData;
    }
    public static function lineInformation($id)
    {
        $query = "SELECT MAX(species.lineage), taxtree.scientificName FROM species JOIN taxtree ON taxtree.id=$id AND species.lineage LIKE IF( taxtree.id=1, CONCAT('>', taxtree.scientificName, '%') ,CONCAT('%***', taxtree.scientificName, '***%'))";
        $result = mysqlDB::$_connectionGDB->query($query);
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            self::$dbline = $row["MAX(species.lineage)"];
            self::$sciName = '***'.$row["scientificName"].'***';
            self::$sName = $row["scientificName"];
            $pos = strpos(self::$dbline,self::$sciName);
            $res = substr(self::$dbline,0,$pos).self::$sciName;
            $line = explode("*** ***",$res);
            $newLine = array();
            foreach($line as $taxName)
            {
                if($taxName[0]== '>')
                {
                    $taxName = str_replace('>', '', $taxName);
                }
                $sql = "SELECT id FROM taxtree WHERE scientificName='$taxName'";
                $res = mysqlDB::$_connectionGDB->query($sql);
                if($res->num_rows>0)
                {
                    $row = $res->fetch_assoc();
                    $newLine["$row[id]"] = $taxName;
                }
            }
            $line = $newLine;
        }
        else
        {
            $line = null;
        }
        return $line;
    }
    public static function groupInformation($id)
    {
        $ptr = explode("*** ***",self::$dbline);
        if(end($ptr) == self::$sName.'***')
        {
            $group = array();
            return $group;
        }
        $query = "SELECT id, scientificName FROM taxtree WHERE p_id=$id";
        $result = mysqlDB::$_connectionGDB->query($query);
        if($result->num_rows > 0)
        {
            $group = $result->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            $group = null;
        }
        return $group;
    }
    public static function speciesInformation($id)
    {
        $query = "SELECT species.id, species.organism_name, species.refseq_category, species.assembly_level, species.seq_rel_date, species.division FROM species JOIN taxtree ON taxtree.id=$id AND species.lineage LIKE IF( taxtree.id=1, CONCAT('>', taxtree.scientificName, '%') ,CONCAT('% ***', taxtree.scientificName, '%'))
        UNION SELECT subspecies.id, subspecies.organism_name, subspecies.refseq_category, subspecies.assembly_level, subspecies.seq_rel_date, subspecies.division FROM subspecies JOIN taxtree ON taxtree.id=$id AND subspecies.lineage LIKE IF( taxtree.id=1, CONCAT('>', taxtree.scientificName, '%') ,CONCAT('% ***', taxtree.scientificName, '%'))";
        $result = mysqlDB::$_connectionGDB->query($query);
        if($result->num_rows > 0)
        {
            $species = $result->fetch_all(MYSQLI_ASSOC);
        }
        else
        {
            $species = null;
        }
        return $species;
    }
    public static function titleInformation($id)
    {
        $query = "SELECT scientificName FROM taxtree WHERE id=$id";
        $result = mysqlDB::$_connectionGDB->query($query);
        if($result->num_rows>0)
        {
            $row = $result->fetch_all(MYSQLI_ASSOC);
            $title = $row[0]["scientificName"];
        }
        else
        {
            $title = null;
        }
        return $title;
    }
}
?>