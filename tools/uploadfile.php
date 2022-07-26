<?php
function filetoupload($fileToUpload)
{
    $target_FILE=DATA.basename($fileToUpload);
    $FastaFileType = strtolower(pathinfo($target_FILE,PATHINFO_EXTENSION));
    $temp = explode(".", $fileToUpload);
    //$newfilename = round(microtime(true)) . '.' . end($temp);
    require_once "generateRandomString.php";
    $newFILENAME=generateRandomString() . '.' . end($temp);
    return $newFILENAME;
}
?>