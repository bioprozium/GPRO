<?php
    $items=array();        
    $search_block="";
    $types="";
    $vals=array();
    $use_selected=false;
    if($filters["search"]!="") //For guests, datatables uses serverside list. If 'search' exists, apply to query
    {
        $search_block=" WHERE (";
        $sep="";
        foreach($filters["columns"] as $column)
        {
            $search_block.="$sep $column LIKE ?";
            $types.="s";
            $vals[]="%{$filters["search"]}%";
            $sep=" OR ";
        }
        $search_block.=")";
        $use_selected=true;
    }
    $query="SELECT * FROM guests $search_block ORDER BY {$filters["col"]} {$filters["order"]}";
    $result=DB::Select($query, $types, $vals);
    $pos=-1;
    $qt=0;
    while($arr=DB::Fetch($result))
    {
        $pos++;
        if($pos<$filters["offset"]) //Skip all items before an offset
            continue;
        $items[]=$arr;
        $qt++;
        if($filters["limit"]>0 and $qt>=$filters["limit"]) //Break if reached a limit
            break;
    }
    $query="SELECT COUNT(*) AS qt FROM guests"; //Also get total record count
    $res=DB::Select($query);
    $total=DB::Fetch($res)["qt"];
    $selected=($use_selected) ? DB::NumRows($result) : $total;
    $out=array("selected"=>$selected, "total"=>$total, "items"=>$items);
    return $out;
?>