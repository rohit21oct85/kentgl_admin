
$("#submit_btn").click(function(){
    var data=$("form").serialize();
    //alert(data);            
    $.ajax({
        url: "./getLoginResponse.php",
        type: "POST",
        data: data,
        success: function(rel){
		//alert(rel);
        var obj = jQuery.parseJSON(rel);
		if(obj.result=="TRUE")
        {
			window.location.href = "add_user.php";      
        }else if(obj.result=="FALSE"){ 
			alert(obj.message);
			$("#errorMsg").html(obj.message).delay(2500).fadeOut("slow").css({'color':'red'});
        }
            }
    });        
    return false; 
  });
  

   