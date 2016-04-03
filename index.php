<html>
<head>
	<title>Calculo del Recubrimiento M&iacute;nimo y Llaves Candidatas</title>
	<script type="text/javascript" src="javascript/jquery-2.2.1.min.js"></script>
	<script type="text/javascript" src="javascript/jquery-validate.min.js"></script>
	<script type="text/javascript" src="javascript/processAjax.js"></script>

	<script type="text/javascript">

		$(document).ready(function() {
		    
		    var attributesCont = $(".attributeFields");
		    var add_button = $(".addAtributte");
		    var create_attributes = $(".createAttributes");
		    var buttons = $(".buttons");
		    var add_buttonDep = $(".addDependency");
		    var dependencyCont = $(".dependencyFields");
		    
		    var x = y = 1; 

		    $(add_button).click(function(e){
		        e.preventDefault();
		      
		            x++;
		            $(attributesCont).append('<div><div class="label">Atributo '+x+': </div><input type="text" onkeypress="return validar(event)" name="atributo[]"/><a href="#" class="remove_fieldAt">Eliminar</a></div>');
		    });
		    
		    $(attributesCont).on("click",".remove_fieldAt", function(e){
		        e.preventDefault(); $(this).parent('div').remove(); x--;
		    })

		    $(create_attributes).click(function(e){

		    	if($("#attributes-form").valid() == false){

		    		return false;
	    		}

		        e.preventDefault();

		        $(buttons).html('');

		        $('input[name^="atributo[]"]').each(function(){

	        		var valor = $(this).val();

			  		$(buttons).append('<li><a href="'+valor+'" class="val">'+valor+'</a></li>');
				});

				$('#first').hide();
				$('#second').show();
		    });

		    $(add_buttonDep).click(function(e){
		        e.preventDefault();
		      
	            y++;
	            $(dependencyCont).append('<div><div class="label"> Dependencia '+y+': </div><input type="text" onkeypress="return validar(event)" id= "implicantes'+y+'" name= "implicantes[]"/> => <input type="text" onkeypress="return validar(event)" id= "implicados'+y+'" name= "implicados[]"/><a href="#" class="remove_fieldDep">Eliminar</a></div>');
		    });
		    
		    $(dependencyCont).on("click",".remove_fieldDep", function(e){
		        e.preventDefault(); $(this).parent('div').remove(); y--;
		    });

 			$('body').on('click', 'a.val', function(e) {

   				e.preventDefault();

   				var id = $('#currentElement').val();

    			if($('#'+id).val() != ''){

    				if($('#'+id).val().indexOf($(this).text()) == -1){

    					var valor = $('#'+id).val()+','+$(this).text();
    					$('#'+id).val(valor); 
    				}

    			}else{

					$('#'+id).val($(this).text());
    			}

    			$('#'+id).focus();

			}); 

 			$(document).on('focus','input[name^="implicantes[]"]', function () {
		        $('#currentElement').val(this.id);
    		});

    		$(document).on('focus','input[name^="implicados[]"]', function () {
		        $('#currentElement').val(this.id);
    		});

    		$('.backfirst').click(function(e){

    			e.preventDefault();

    			$('#second').hide();
		      	$('#first').show(); 

       	    });

    		$('#fileResponse').hide();
    		$('#second').hide();
    		$('#third').hide();
			
		});

		function validar(e) { 

		    tecla = (document.all) ? e.keyCode : e.which; 

		    if (tecla==8) return true; 

		    patron = /\w/;
		    te = String.fromCharCode(tecla); 

		    return patron.test(te); 
		}

	</script>	

	<style type="text/css">

		.label {
	
			width:150px;
			font-weight: bold;
		}

		input{

			width:200px;
		}

		#l0, #l1, #l2, #l0File, #l1File, #l2File, #llaves, #l2Filellaves{

			font-weight: bold;
			color: blue;
			font-size: 18px;
		}

		#massivecharge, #ullman, #first, #second, #third{

			width:80%;
			margin:auto;
			border: solid 2px gray;
			padding: 0 0 30px 30px;
			box-shadow: 0 2px 5px #666666;
		}

		li {
			margin:2px;
			padding:2px 6px 2px 6px;
			font-size: 30px;
			border:1px solid #666666;
			box-shadow: 0 2px 5px #666666;
			display:inline;
		}

		li a{

			font-weight:bold;
			text-decoration:none;
			color: green;
		}

		li a:hover{

			color: orange;
		}

		#attributesFile{

			color: red;
			font-weight: bold;
		}

		#dependencyFile{

			color: green;
			font-weight: bold;
		}
		
		.error{

			color: red;
			padding-left: 5px;
			font-weight: bold;
			font-size: 14px;
		}


		 #ulmanResult{

			font-weight: bold;
			color: blue;
			font-size: 18px;
		}

	</style>

<script type="text/javascript">

	$( document ).ready(function() {

        $('#attributes-form').submit(function(e) {
            e.preventDefault();

        }).validate({
            debug: false,
            rules: {
                "atributo[]": {
                    required: true
                }
            },
            messages: {
                "atributo[]": {
                    required: "Por favor ingrese el atributo."
                }
            }
        });
	});

</script>

</head>

<body>
	<h1 align="center">Calculo del Recubrimiento M&iacute;nimo y Llaves Candidatas</h3>

	<div id="first">

		<h2>Paso 1 de Forma Manual: Crear el Conjunto de Atributos T</h2>

		<p>De click en el bot&oacute;n para agregar cada atributo del conjunto: <button class="addAtributte">+ Agregar Atributo</button> </p>
		
		<form id="attributes-form" name="attributes-form">

			<div class="attributeFields">
				<div class="label"> Atributo 1: </div><input type="text" name= "atributo[]" id= "atributo[]" onkeypress="return validar(event)"/>
			</div>

			<p><button class="createAttributes">Generar Conjunto de  Atributos</button> </p>

		</form>
	</div>
	
	<div id="second">

		<h2>Paso 2 de Forma Manual: Crear las Dependencias del Conjunto L y Calcular el Recubrimiento</h2>

	    <input type="hidden" name="currentElement" id="currentElement"/>

	    <p>Para agregar las dependencias por favor de click en el icono de cada atributo </p>

		<div style="clear:both"><ul class="buttons"></ul></div>

		<p>De click en el bot&oacute;n para agregar la cantidad de dependencias que requiera (Implicados e Implicantes): <button class="addDependency">+ Agregar Dependencia</button></p>

		<div class="dependencyFields">
			<div class="label"> Dependencia 1: </div><input type="text" name= "implicantes[]" id="implicantes1" onkeypress="return validar(event)"/> => <input type="text" onkeypress="return validar(event)" name= "implicados[]" id="implicados1"/> 
		</div>

		<p><button class="calclo">Generar Conjunto L0</button> </p>
		<div id="l0"></div>

		<p><button class="calcl1" disabled="disabled">Generar Conjunto L1</button></p>
		<div id="l1"></div>	

		<p><button class="calcl2" disabled="disabled">Generar Conjunto L2</button></p>
		<div id="l2"></div>	

		<p><button class="calcllaves" disabled="disabled">Generar Llaves Candidatas</button></p>
		<div id="llaves"></div>	

		<br/>
		<a href="" class="backfirst">Regresar al Paso Anterior</a>

	</div>	
	
	<div id ="massivecharge">

		<h2>Carga masiva de Atributos y Dependencias por Archivo XML</h2>
		<p>Subir archivo XML: <input type="file" name="archivo" id="archivo" /></p>
		<p><button class="uploadFile">Subir Archivo </button> </p>

        <div id="fileResponse">

			<p><b>Conjunto de Atributos<b/></p>
			<div id="attributesFile"></div>

			<p><b>Conjunto de Dependencias</b></p>
			<div id="dependencyFile"></div>

			<p><b>Conjunto L0</b></p>
			<div id="l0File"></div>

			<p><b>Conjunto L1</b></p>
			<div id="l1File"></div>	

			<p><b>Conjunto L2</b></p>
			<div id="l2File"></div>	

			<p><b>Llaves Candidatas</b></p>
			<div id="l2Filellaves"></div>	

		</div>
	</div>

	<div id="ullman">

		<h2>Demostraci&oacute;n de Ejemplo de Aplicaci&oacute;n Algoritmo de ULLMAN</h2>
		<p>Determinacion si una descomposici&oacute;n es sin p&eacute;rdida de informaci&oacute;n y sin perdida de dependencias</p>
		<p><button class="calcUllman">Calcular</button></p>

		<div id="ulmanResponse" style="display:none">
			<p><b>Resultado de la Demonstraci&oacute;n</b></p>
			<div id="ulmanResult"></div>
		</div>	
	</div>	
</body>
</html>	