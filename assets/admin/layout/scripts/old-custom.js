$(document).ready(function() {
     $('#filter').change(function () {
        var values = [];
        $('#filter option:selected').each(function () {
            if ($(this).val() != "") values.push($(this).text());
        });
        filter('table > tbody > tr', values);
    });
	
	$('#filter1').change(function () {
        var values = [];
        $('#filter1 option:selected').each(function () {
            if ($(this).val() != "") values.push($(this).text());
        });
        filter('table > tbody > tr', values);
    });
	
	$('#filter2').change(function () {
        var values = [];
        $('#filter2 option:selected').each(function () {
            if ($(this).val() != "") values.push($(this).text());
        });
        filter('table > tbody > tr', values);
    });
	
	$('#filter3').change(function () {
        var values = [];
        $('#filter3 option:selected').each(function () {
            if ($(this).val() != "") values.push($(this).text());
        });
        filter('table > tbody > tr', values);
    });

    function filter(selector, values) {
        $(selector).each(function () {
            var sel = $(this);
            var tokens = sel.text().split('\n');
            var toknesObj = [], i;
            for(i=0;i<tokens.length;i++){
                toknesObj.push( {text:tokens[i].trim(), found:false});
            }
            
            var show = false;
            console.log(values);
            $.each(values, function (i, val) {
					
                for(i=0;i<toknesObj.length;i++){                    
                    if (!toknesObj[i].found && toknesObj[i].text.search(new RegExp("\\b"+val+"\\b")) >= 0) {
                        toknesObj[i].found = true;
                    }
                }
            });          
            
            var count = 0;
             $.each(toknesObj, function (i, val) {
                 if (val.found){
                     count+=1;
                 }
             });
            show = (count === values.length);        
            show ? sel.show() : sel.hide();
        });
    }
} );