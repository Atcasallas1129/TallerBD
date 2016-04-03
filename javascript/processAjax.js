
$(document).ready(function() {

	$('.calclo').click(function(){

		var implicantes = $('input[name^="implicantes[]"]').serialize();
		var implicados = $('input[name^="implicados[]"]').serialize();

		$.ajax({
		   
		    url : './process.php',
		    data : implicantes+'&'+implicados+'&action=l0',
		    type : 'POST',
		   
		    success : function(response) {

		         var l0 = $.parseJSON(response);

		         $('#l0').html('Conjunto de Dependencias L0: {'+l0+'}');
		         $('.calcl1').prop( "disabled", false );
		    },

		    error : function(xhr, status) {

		        alert('Disculpe, existió un problema');
		    },

		    complete : function(xhr, status) {

		    }
		});
	});

	$('.calcl1').click(function(){

		var implicantes = $('input[name^="implicantes[]"]').serialize();
		var implicados = $('input[name^="implicados[]"]').serialize();

		$.ajax({
		   
		    url : './process.php',
		    data : implicantes+'&'+implicados+'&action=l1',
		    type : 'POST',
		   
		    success : function(response) {
		    	 //alert(response);
		         var l1 = $.parseJSON(response);

		         $('#l1').html('Conjunto de Dependencias L1: {'+l1+'}');
		         $('.calcl2').prop( "disabled", false );
		    },

		    error : function(xhr, status) {

		        alert('Disculpe, existió un problema');
		    },

		    complete : function(xhr, status) {

		    }
		});
	});

	$('.calcl2').click(function(){

		var implicantes = $('input[name^="implicantes[]"]').serialize();
		var implicados = $('input[name^="implicados[]"]').serialize();

		$.ajax({
		   
		    url : './process.php',
		    data : implicantes+'&'+implicados+'&action=l2',
		    type : 'POST',
		   
		    success : function(response) {
		    	 //alert(response);
		         var l2 = $.parseJSON(response);

		         $('#l2').html('Conjunto de Dependencias L2: {'+l2+'}');
		         $('.calcllaves').prop( "disabled", false );
		    },

		    error : function(xhr, status) {

		        alert('Disculpe, existió un problema');
		    },

		    complete : function(xhr, status) {

		    }
		});
	});

	$('.calcllaves').click(function(){


		var atributos = $('input[name^="atributo[]"]').serialize();
		var implicantes = $('input[name^="implicantes[]"]').serialize();
		var implicados = $('input[name^="implicados[]"]').serialize();

		$.ajax({
		   
		    url : './process.php',
		    data : implicantes+'&'+implicados+'&'+atributos+'&action=llaves',
		    type : 'POST',
		   
		    success : function(response) {
		    	 
		         var llaves = $.parseJSON(response);
		         var llavesResult = '';

		         $.each(llaves, function( key, value ) {

				    		llavesResult+= value+' '; 

				 });

		         $('#llaves').html('Conjunto de Llaves Candidatas: { '+llavesResult+' }');
		    },

		    error : function(xhr, status) {

		        alert('Disculpe, existió un problema');
		    },

		    complete : function(xhr, status) {

		    }
		});
	});

	$('.calcUllman').click(function(){

		/*var atributos = $('input[name^="atributo[]"]').serialize();
		var implicantes = $('input[name^="implicantes[]"]').serialize();
		var implicados = $('input[name^="implicados[]"]').serialize();*/

		$.ajax({
		   
		    url : 'Class/Ullman.php',
		    //data : implicantes+'&'+implicados+'&'+atributos+'&action=llaves',
		    type : 'POST',
		   
		    success : function(response) {
		    	 
		        /* var llaves = $.parseJSON(response);
		         var llavesResult = '';

		         $.each(llaves, function( key, value ) {

				    		llavesResult+= value+' '; 

				 });*/
				$('#ulmanResponse').show();
	         	$('#ulmanResult').html(response);
		    },

		    error : function(xhr, status) {

		        alert('Disculpe, existió un problema');
		    },

		    complete : function(xhr, status) {

		    }
		});
	});

	$('.uploadFile').on('click', function() {

				var file_data = $('#archivo').prop('files')[0];   
				var form_data = new FormData();                  
				form_data.append('file', file_data);
                          
				$.ajax({
				    url: './processFile.php',
				    dataType: 'text',
				    cache: false,
				    contentType: false,
				    processData: false,
				    data: form_data,                         
				    type: 'post',
				    success: function(response){

				    	var response = $.parseJSON(response);
				    	var l0 = l1 = l2 = conjunto = dependencias = conjuntoLlaves = '';
				        var llaves = response['llaves'];

				    	$.each(response['conjunto'], function( key, value ) {

				    		conjunto+= value+' , '; 
						});

						$.each(response['dependencias'], function( key, value ) {

				    		dependencias+= value+' , '; 
						});

				    	$.each(response['l0'], function( key, value ) {

				    		l0+= value+' , '; 
						});

						$.each(response['l1'], function( key, value ) {

				    		l1+= value+' , '; 
						});

						$.each(response['l2'], function( key, value ) {

				    		l2+= value+' , '; 
						});

						$.each(response['llaves'], function( key, value ) {

				    		conjuntoLlaves+= value+' '; 
						});

						$('#fileResponse').show();
				    	$('#attributesFile').html('{ '+conjunto.substring(0, conjunto.length - 2)+' }');
				    	$('#dependencyFile').html('{ '+dependencias.substring(0, dependencias.length - 3)+' }');
						$('#l0File').html('{ '+l0.substring(0, l0.length - 3)+' }');
						$('#l1File').html('{ '+l1.substring(0, l1.length - 3)+' }');
						$('#l2File').html('{ '+l2.substring(0, l2.length - 3)+' }');
						$('#l2Filellaves').html('{ '+conjuntoLlaves+' }');
						
				    	//console.log(response)
				    }
				});
	});
});