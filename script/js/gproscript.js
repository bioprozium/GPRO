"use strict";


function checkSize(max_img_size)
{
    var input = document.getElementById("gFile");
    // check for browser support (may need to be modified)
    if(input.files && input.files.length == 1)
    {           
        if (input.files[0].size > max_img_size) 
        {
            alert("The file must be less than " + (max_img_size/1024/1024) + "MB");
            return false;
        }
    }
    const btn = document.querySelector('#uploadButton');
    const radioButtons = document.querySelectorAll('input[name="dType[]"]');
    btn.addEventListener("click", () => {
        let selectedSize;
        for (const radioButton of radioButtons) {
            if (radioButton.checked) {
                selectedSize = radioButton.value;
                break;
            }
        }
        // show the output:
        output.innerText = selectedSize ? `You selected ${selectedSize}` : `You haven't selected any size`;
    });

    return true;
}
function checkUser()
{
    let host = location.protocol + "//" + location.host + "/GPRO/";
    let url = $("#uplfiles").val();
    $.post( host+"/index.php?m=upldyrfls&action=checkuser")
	.done(function( data ) {
		if(data!="OK")
		{
			$("#log-alert").show();
			$("#log-alert").html(data);
		}
		else
		{
			window.location.replace(url);
		}
	});
}