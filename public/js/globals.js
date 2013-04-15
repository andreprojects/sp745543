function callAjax(domain,div,type_send,data_send){
	//console.log(domain);
	//console.log(div);
	//console.log(type_send);
	if(type_send == undefined){
		type_send = 'GET';
	}

	if(data_send == undefined){
			data_send = '';
		}
		//console.log(data_send);     		

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
	     
	    } 
	});
	
}