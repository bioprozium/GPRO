<?php
function parse_by_constants($text)
{
    while(preg_match("/\{\|(.+?)\|\}/", $text, $res))
    {
        $block=str_replace("{|", "", $res[0]);
        $block=str_replace("|}", "", $block);
        $result=constant($block);
        $text=preg_replace("/\{\|(.+?)\|\}/", "$result", $text, 1);
    }
    return $text;
}
function parse_by_blocks($block,$text,$rep)
{
    $block="<{".$block."}>";
    $text=str_replace($block,$rep,$text);
    return $text;
}
function remove_empty_blocks($text)
{
    return preg_replace('/\<\{(.*?)\}\>/', '', $text);
}
?>