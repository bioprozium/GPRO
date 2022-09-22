"use strict";

const host = location.protocol + "//" + location.host + "/GPRO/";
$(document).ready(function(){
	$("#saveprojbutton").click(function(){
    	$("#saveproj_form").modal();
	$.get( host+"index.php?m=pctrl", { act: "sf"} )
		.done(function( data ) {
			$("#saveproj_form").html(data);
		});
        //const saveButton = document.getElementById('saveproj');
        //console.log(saveButton);
    });
    $("#shareprojbutton").click(function(){
    	$("#shareproj_form").modal();
	$.get( host+"index.php?m=pctrl", { act: "shf"} )
		.done(function( data ) {
			$("#shareproj_form").html(data);
		});
    });
});
//const saveButton = document.querySelector('saveproj');
//console.log(saveButton);
//const shareButton = document.getElementById('shareproj');
 /*saveButton.addEventListener("click", function(){
    let jsonToServer = {user:{},public:{}};
    let checked = document.querySelectorAll("input[id='propcheck']:checked");
    let action = document.getElementById('saveprojectbutton').value;
    checked.forEach(function(cb,index){
        let prop = cb.name.substr(0,1);
        if(prop == "s")
        {
            let matches = cb.name.match(/\d+/g);
            if(matches in jsonToServer.user)
            {
                jsonToServer.user[matches].push(cb.value);
            }
            else
            {
                jsonToServer.user[matches] = [cb.value];
            }
        }
        else if(prop == "p")
        {
            let matches = cb.name.match(/\d+/g);
            if(matches in jsonToServer.public)
            {
                jsonToServer.public[matches].push(cb.value);
            }
            else
            {
                jsonToServer.public[matches] = [cb.value];
            }
        }

    });
    const myJSON = JSON.stringify(jsonToServer);
    $.post( host+"/index.php?m=pctrl&action=save", { data: myJSON, act: action } )
	.done(function( data ) {
		if(data !== null || data !== undefined)
		{
			console.log(data);
		}
		else
		{
			
			window.location.replace(query);
		}
	  });
});  */










let actBtn = document.querySelectorAll('[name="action"]');
for (let i = 0; i < actBtn.length; i++) {
    actBtn[i].addEventListener("click", function() {
        let checked = document.querySelectorAll('input:checked');
        if (checked.length === 0) {
            // there are no checked checkboxes
            //console.log(checked.length + ' checkboxes checked');
            event.preventDefault();
        }
    });
}


const selectedObjects = document.querySelectorAll('[selected-object]');
selectedObjects.forEach((item, index)=>{
    let spinner = item.lastChild;
    let status = setInterval(()=>{
        $.post( host+"/index.php?m=check", { id: item.attributes[1].value } )
        .done(function( data ) {
            if(data==1)
            {
                const checked = document.createElement('i');
                checked.classList.add("bi");
                checked.classList.add("bi-check");
                spinner.parentNode.replaceChild(checked, spinner);
                clearInterval(status);  
            }
        });
    },1000);
});
function checkStatus()
{

}
////////////////////////////////////////////////////////////////////////////////

function statusResponce(id)
{
    $.post( host+"/index.php?m=check", { id: id } )
	.done(function( data ) {
		if(data!=1)
		{
			return false;
		}
		else
		{
			return true;
		}
	  });
}

/* const checked = document.createElement('i');
checked.classList.add("bi bi-check");
myAnchor.parentNode.replaceChild(checked, myAnchor); */
