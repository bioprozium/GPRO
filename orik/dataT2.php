<?php
$filters["order"]=($_GET["order"][0]["dir"]=="asc") ? "ASC" : "DESC";
$filters["col"]=DB::AddSlashes($this->list_columns[$_GET["order"][0]["column"]]);
$filters["search"]=$_GET["search"]["value"];
$filters["limit"]=(is_numeric($_GET["length"])) ? $_GET["length"] : 10;
$filters["offset"]=(is_numeric($_GET["start"])) ? $_GET["start"] : 0;
$filters["columns"]=$this->list_columns;
$data=$this->guests->GetGuestList($filters);

?>