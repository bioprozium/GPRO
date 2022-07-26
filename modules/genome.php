<?php
class genome
{
    function __construct()
    {
        $this->gets=$_GET;
    }
    function SetPage()
    {
        gpro::$HTML["TITLE"]="GPro: Genomes";
        gpro::$HTML["MAIN_BLOCK"]="Page is unavailable right now... :( ";
    }
}
?>