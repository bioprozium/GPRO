<?php
class index_page
{
    function __construct()
    {
        $this->gets=$_GET;
    }
    function SetPage()
    {
        gpro::$HTML["TITLE"]="GPro: Whole Genome Analysis Tools";
        gpro::$HTML["MAIN_BLOCK"]=parse_by_constants(file_get_contents(MAINBLOCK."main_index.html"));
    }
}
?>