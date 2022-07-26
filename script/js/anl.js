"use strict";

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
        let host = location.protocol + "//" + location.host + "/GPRO/";
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
    let host = location.protocol + "//" + location.host + "/GPRO/";
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
