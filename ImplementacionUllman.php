
<?php
$Conjunto = array('A','B','C','D');
$Dependencias = array(
				0=>'A=>B',
				1=>'B=>C',
				2=>'C=>D',
				3=>'D=>A'
				);
echo "<pre>Conjunto de atributos T <br>";
print_r($Conjunto);
echo "<hr>Dependencias Funcionales del conjunto L <br>";
print_r($Dependencias);
echo "<hr></pre>";
$atribProyecciones = 	array(
						0 => 'A,B',
						1 => 'B,C',
						2 => 'C,D'
						);
$dependenciasProyecciones =	array(
							0 => 'A=>B,B=>A',
							1 => 'B=>C,C=>B',
							2 => 'C=>D,D=>C'
							);

foreach($atribProyecciones as $key => $proyeccion){
	$ProyeccionesSPI[]="{".$proyeccion."}{".$dependenciasProyecciones[$key]."}";
}

echo "<pre>Proyecciones del conjunto PROY<br>";
print_r($ProyeccionesSPI);
echo "<hr></pre>";

#Aca Inicia el codigo de la implementacion
if(UllmanPerdidaInformacion($atribProyecciones, $Conjunto, $Dependencias) == true)
{
	echo "la descomposicion es sin perdida de informacion";
}

function UllmanPerdidaInformacion($atribProyecciones, $Conjunto, $Dependencias){
	for ($i = 0; $i < count($atribProyecciones); $i++)
	{
		$atributos = "";
		for($j = 0; $j < count($Conjunto); $j++)
		{
			$arrayProyeccion = explode(',',$atribProyecciones[$i]);
			foreach ($arrayProyeccion as $key => $valor)
			{
				if($valor == $Conjunto[$j]){
					$atributos += 1;
				}
				else{
					$atributos += 0;
				}	
			}
			if($atributos == 1){
				$atributos = "A";
			}
			else{
				$atributos = "B";
			}
			$arrayAtributos[] = $atributos;
			$elementosMatriz = implode(',',$arrayAtributos);
			$matriz[$atribProyecciones[$i]][$j] = $atributos;
			$atributos = "";
		}
		$matriz[$atribProyecciones[$i]] = $elementosMatriz;
		unset($arrayAtributos);
	}
	$matrizOriginal = $matriz;
	
	do{
		foreach ($Dependencias as $clave => $Dependecia){
		$Relacion = explode('=>',$Dependecia);
		$implicante = $Relacion[0];
		$implicado = $Relacion[1];
		$posicionImplicante = array_search($implicante, $Conjunto);
		$posicionImplicado = array_search($implicado, $Conjunto);
		foreach($matriz as $atrib => $valores){
			$vector = explode(',',$valores);
			if($vector[$posicionImplicante] == 'A'){
				$arrayEvaluar[]=$atrib;
			}
		}
		if(count($arrayEvaluar) > 1 ){
			$verificadorImplicado = "";
			for($x = 0; $x < count($arrayEvaluar);$x++){
				$atribArrayEvaluar = explode(',', $matriz[$arrayEvaluar[$x]]);
				$verificadorImplicado .= $atribArrayEvaluar[$posicionImplicado];
			}
			$analisisREsultado = strpos($verificadorImplicado, 'A');
			if ($analisisREsultado !== FALSE)
			{
				for($x = 0; $x < count($arrayEvaluar);$x++){
					$elementosMatriz = explode(',', $matriz[$arrayEvaluar[$x]]);
					$elementosMatriz[$posicionImplicado] = 'A';
					$var = implode(',', $elementosMatriz);
					$matriz[$arrayEvaluar[$x]] = implode(',', $elementosMatriz);
				}
			}
			
		}
		unset($arrayEvaluar);
	}
		foreach($matriz as $key => $elementos){
			$verificador = 0;
			$elementos = explode(',',$elementos);
			foreach ($elementos as $x => $dato){
				if($dato == 'A'){
					$verificador +=1;
				}
			}
			if ($verificador == count($Conjunto)){
				return true;
				break 2;
			}
			else{
				return false;
			}
		}	
	}while (($matrizOriginal != $matriz) || ($verificacion != true));
}
#Funcion para evaluacion de perdida de dependencias funcionales de las proyecciones
function UllmanPerdidaDepedencias($dependenciasProyecciones, $Dependencias){
	foreach($dependenciasProyecciones as $key => $dependenciasR){
		$dependencias = explode(',', $dependenciasR);
		foreach($dependencias as $pos => $relacion){
			$separarDependencias = explode('=>', $relacion);
			$implicantesG[]=$separarDependencias[0];
			$implicadosG[]=$separarDependencias[1];
		}
	}
	foreach($implicantesG as $key => $implicanteG){
		$DependenciasG[]="$implicanteG=>$implicadosG[$key]";
	}
	foreach ($Dependencias as $id => $Dependencia){
		$splitDependences = explode('=>',$Dependencia);
		$Implicante = $splitDependences[0];
		$Implicado = $splitDependences[1];
		$arrayPerdidaDependencias[$Implicante][0]=calcularCierre($implicantesG, $implicadosG, $Implicante); 
		if(strpos($arrayPerdidaDependencias[$Implicante][0],$Implicado) !== false){
			$arrayPerdidaDependencias[$Implicante][1] = "TRUE";
		}
		else{
			$arrayPerdidaDependencias[$Implicante][1] = "FALSE";
		}
	}
	foreach ($arrayPerdidaDependencias as $implicante => $evaluacion){
		if ($evaluacion[1] == "FALSE"){
			$evaluacion = 0;
		}
		else{
			$evaluacion = 1;
		}
	}
	if ($evaluacion == 1){
		return TRUE;
	}
	else{
		return FALSE;
	}
}
?>