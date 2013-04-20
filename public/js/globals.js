function displayDivDinamic(obj){
	//console.log(obj.is(':visible'));
	if(obj.is(':visible')){
		obj.hide();
	}else{
		obj.show();
	}
}

var last_url;
function callAjax(domain,div,type_send,data_send,save_url,domain_return,div_return){
	//console.log(domain);
	//console.log(div);
	
	if(type_send == undefined){
		type_send = 'GET';
	}

	if(data_send == undefined){
		data_send = '';
	}
	if(save_url != undefined){
		last_url = domain;
	}

  	$.ajax({
	    url: domain,
	    type: type_send,
	    cache: true,
	    data: data_send,
	    //ifModified: true,
	    beforeSend: function(){
	    	div.html('carregando...');
	    }, 
	    success: function(html) {
	        div.html(html);
	    },
	    complete:function() {
	     	if(domain_return!=undefined && div_return!=undefined)
	     		callAjax(domain_return,div_return);
	    } 
	});
	
}