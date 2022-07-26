"use strict";
function deleteSelectedFile(index)
{
    const deleteRow = document.getElementById(`tr_${index}`);
    deleteRow.remove();
    let host=location.protocol + "//" + location.host + "/GPRO/";
    $.post( host+"/index.php?m=ufp", {action: "deleteFile", id: index } ).done();
}

function checkSelectedFiles()
{
    const checkBoxes = document.querySelectorAll('.fileCheckBox');
    let n = 0;
    checkBoxes.forEach((checkbox)=>{
        if(checkbox.checked == true)
        {
            n++;
        }
    });
    if(n == 0)
    {
        alert("please select file");
        event.preventDefault();
    }    
}