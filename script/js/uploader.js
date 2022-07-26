"use strict";
const uploadButton = document.getElementById('uploadButton');
const fileSelector = document.getElementById('gFile');
const selectArea = document.getElementById('selectedFiles');
if(fileSelector.files.length == 0)
{
    document.getElementById("selectedFiles").innerHTML = "Please, select one or more files.";
}//First entrance to the uploader page and LABEL

/**-----------------------ADDEVENTLISTENERS------------------------------**/
uploadButton.addEventListener("click", function (){
    if(uploadButton.getAttribute('listener') != 'true'){
        let gFile = document.getElementById('gFile');
        if(gFile.files.length == 0)
        {
            let file_alert = document.createElement('div');
            file_alert.innerHTML = "Please Select File";
            file_alert.setAttribute('class', 'alert alert-danger col');
            file_alert.setAttribute('id', 'file-alert');
            file_alert.setAttribute('style', 'display:block; margin:5px');
            insertAfter(fileSelector, file_alert);
            uploadButton.setAttribute('listener','true');
            event.preventDefault();
        }
    }
    else{
        event.preventDefault();
    }
});// When you click 'Upload' button, if file is not selected SHOWS FILE ALERT!

fileSelector.addEventListener('click', function(){
    const fileAlert = document.getElementById('file-alert');
    if (fileAlert != null)
    {
        fileAlert.remove();
        uploadButton.setAttribute('listener','false');
    }
});//When you click 'File Select' function REMOVES a table and file alert.

/**-----------------------FUNCTIONS------------------------------**/
function uploadInfo()
{
    let table = document.createElement('table');
    table.setAttribute('class', 'table table-striped');
    let thead = document.createElement('thead');
    let tbody = document.createElement('tbody');
    table.appendChild(thead);
    table.appendChild(tbody);
    
    // Adding the entire table to the body tag
    document.getElementById("selectedFiles").innerHTML = "";
    document.getElementById('selectedFiles').appendChild(table);

    // Creating and adding head data to first row of the table
    let row_1 = document.createElement('tr');
    let heading_1 = document.createElement('th');
    heading_1.setAttribute('scope', 'col');
    heading_1.innerHTML = "No.";
    let heading_2 = document.createElement('th');
    heading_2.setAttribute('scope', 'col');
    heading_2.innerHTML = "File name";
    let heading_3 = document.createElement('th');
    heading_3.setAttribute('scope', 'col');
    heading_3.innerHTML = "Size";
    let heading_4 = document.createElement('th');
    heading_4.setAttribute('scope', 'col');
    heading_4.innerHTML = "File type";
    let heading_5 = document.createElement('th');
    heading_5.setAttribute('scope', 'col');
    heading_5.innerHTML = "Delete File";

    row_1.appendChild(heading_1);
    row_1.appendChild(heading_2);
    row_1.appendChild(heading_3);
    row_1.appendChild(heading_4);
    row_1.appendChild(heading_5);
    thead.appendChild(row_1);

    let r = "";
    let file_number = "";
    let file_name = "";
    let file_size = "";  
    let opt = "";
    let delete_button = "";
    let delBut = "";
    let icon = "";
    
    if(fileSelector.files.length == 0)
    {
        selectArea.innerHTML = "Please, select one or more files.";
    }
    else
    {
        for(let i = 0; i < fileSelector.files.length; i++)
        {
            let file = fileSelector.files[i];
            file.si
            r = document.createElement('tr');
            r.setAttribute('id',`row_${i}`);
            file_number = document.createElement('th');
            file_number.setAttribute('scope', 'col');
            file_number.innerHTML = `${i+1}`;
            file_name = document.createElement('td');
            file_name.innerHTML = `${minimizeFileName(file.name)}`;
            file_size = document.createElement('td');
            file_size.innerHTML = `${(file.size/(1024 * 1024)).toFixed(2)} mb`;
            opt = document.createElement('td');
            opt.appendChild(fileTypeSelector());
            delete_button = document.createElement('td');
            delBut = document.createElement('button');
            delBut.setAttribute('id','deleteFile');
            delBut.setAttribute('onclick',`delRow(${i},"${file.name}")`);
            delBut.setAttribute('class','btn btn-outline-success');
            icon = document.createElement('i');
            icon.setAttribute('class','bi bi-trash');
            icon.setAttribute('id',`deleteFile_${i}`);
            delBut.appendChild(icon);
            delete_button.appendChild(delBut);
            r.appendChild(file_number);
            r.appendChild(file_name);
            r.appendChild(file_size);
            r.appendChild(opt);
            r.appendChild(delete_button);
            tbody.appendChild(r);
        }
        
    }
}//FILE LIST

function delRow(index, fname)
{
    let row = document.getElementById(`row_${index}`);
    row.remove();
    const dt = new DataTransfer()
    let fileIndex = "";
    const { files } = fileSelector;
    for(let i = 0; i < files.length; i++)
    {
        const file = files[i];
        if(fname!=file.name)
        {
            dt.items.add(file);
        }
    }
    fileSelector.files = dt.files;
}// DELETE SELECTED FILE

function check()
{
    if(!checkFileSize(52428800))
    {
        return false;
    }
    else if(!checkFileType())
    {
        return false;
    }
}//FILE VALIDATION (SIZE, TYPE)
////////////////////////////////////////////////////////////////////////
/**-----------------------TOOLS----------------------------------**/
function insertAfter(referenceNode, newNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}//The function inserts new Element after another element

function fileTypeSelector()
{
    let select = document.createElement('select');
    select.setAttribute('class', 'form-select form-select-sm');
    select.setAttribute('id','selectType');
    select.setAttribute('name','dataType[]');
    let option_1 = document.createElement('option');
    option_1.setAttribute('value', '1');
    option_1.innerHTML = 'CDNA';
    let option_2 = document.createElement('option');
    option_2.setAttribute('value', '2');
    option_2.innerHTML = 'CDS';
    let option_3 = document.createElement('option');
    option_3.setAttribute('value', '3');
    option_3.innerHTML = 'CHROMOSOME';
    let option_4 = document.createElement('option');
    option_4.setAttribute('value', '4');
    option_4.innerHTML = 'UTR3';
    let option_5 = document.createElement('option');
    option_5.setAttribute('value', '5');
    option_5.innerHTML = 'UTR5';
    let option_6 = document.createElement('option');
    option_6.setAttribute('value', '6');
    option_6.innerHTML = 'CSV data';
    let option_7 = document.createElement('option');
    option_7.setAttribute('value', '7');
    option_7.innerHTML = 'Tablulated data';
    select.appendChild(option_1);
    select.appendChild(option_2);
    select.appendChild(option_3);
    select.appendChild(option_4);
    select.appendChild(option_5);
    select.appendChild(option_6);
    select.appendChild(option_7);

    return select;
}//<select> tag with options ['CDNA','CDS','CHROMOSOME','UTR3','UTR5']

function minimizeFileName(fname)
{
    let split = fname.split('.');
    let filename = split[0];
    let extension = split[split.length - 1];
    if (filename.length > 10) {
        filename = filename.substring(0, 10);
        filename = filename + "...";
    }
    let result = filename + '.' + extension;
    return result;
}

function checkFileType(file_type)
{
    return true;
}//EMPTY FUNCTION

function checkFileSize(max_size)
{
    let input = document.getElementById('gFile');
    if(input.files && input.files.length == 1)
    {           
        if (input.files[0].size > max_size) 
        {
            alert("The file must be less than " + (max_size/1024/1024) + "MB");
            fileSelector.value = '';
            event.preventDefault();
            return false;
        }
    }
    return true;
}