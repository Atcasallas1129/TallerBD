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
			$atributos = "a".$j;
		}
		else{
			$atributos = "b".$i.$j;
		}
		$arrayAtributos[] = $atributos;
		$elementosMatriz = implode(',',$arrayAtributos);
		$matriz[$atribProyecciones[$i]][$j] = $atributos;
		$atributos = "";
	}
	$matriz[$atribProyecciones[$i]] = $elementosMatriz;
	unset($arrayAtributos);
}
echo "<pre>matriz Inicial de descomposicion ";
print_r($matriz);
echo "</pre>";

/*
1. OBTENER TODAS LAS DEPENDENCIAS FUNCIONALES DE LAS PROYECCIONES
2. PROBAR UNA A UNA LAS DEPENDENCIAS DEL CONJUNTO R
*/
#1. OBTENER TODAS LAS DEPENDENCIAS FUNCIONALES DE LAS PROYECCIONES
foreach($dependenciasProyecciones as $key => $dependenciasR){
	//separar las dependencias que llegan del conjunto de dependencias por relacion
	$dependencias = explode(',', $dependenciasR);
	//separar implicantes de implicados de cada relacion
	foreach($dependencias as $pos => $relacion){
		$separarDependencias = explode('=>', $relacion);
		$implicantesG[]=$separarDependencias[0];
		$implicadosG[]=$separarDependencias[1];
	}
}

foreach($implicantesG as $key => $implicanteG){
	$DependenciasG[]="$implicanteG=>$implicadosG[$key]";
}

#2. PROBAR UNA A UNA LAS DEPENDENCIAS DEL CONJUNTO R
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
echo "<pre>Dependencias a usar para comprobar<br>";
print_r($DependenciasG);
print_r($arrayPerdidaDependencias);
echo "</pre>";

#Funcion para calcular el cierre

function calcularCierre($implicantesDF, $implicadosDF, $cadena){
	#añade el primer elemento enviado segun lo que dice el axioma de Reflexividad
	$cierre = $cadena;
	foreach($implicantesDF as $id => $implicante){
		#generar todas las posibles combinaciones de la variable cierre
		$conjunto = combinarAtributos2($cierre);
		foreach ($conjunto as $pos => $atributo){
			#para cada combinacion se verifica si corresponde a un implicante del conjunto de relaciones.
			if($atributo == $implicante){
				#en caso afirmativo, la variable cadena, es complementada con el valor del implicado asociado al implicante
				$cierre = $cierre.",".$implicadosDF[$id];
			}
		}
	}
	$precierre =explode(',',$cierre);
	$precierre = array_unique($precierre);
	asort($precierre);
	#se organiza el contenido del array y se eliminan datos duplicados. Adicionalmente el array se convierte a un string 
	$cierre=implode(',',$precierre);
	return $cierre;
}

#Funcion para combinar atributos en el cierre.
function combinarAtributos2($Cadena){
	// Algoritmo para sacar las combinaciones de n elementos
	// Los elementos que hay que combinar
	$elementos = explode(',',$Cadena);
	$elementos_invert = array_flip($elementos);
	$cuantos = count($elementos);
	$corridas = array();
	$resultados = $elementos;
	// Por cada elemento, hay una vuelta, un nCr
	for ($i=1; $i<=$cuantos; $i++) {
		
		// La primera corrida son los elementos a secas
		if (!isset($corridas[$i-1])) {
			$corridas[$i] = $elementos;
			continue;
		}
		$elementos_new = $elementos;
		
		// Las letras sobrantes
		$letras_slice = array_slice($elementos_new, ($i-1));
		$letras_rebuilt = implode(",", $letras_slice);
		
		// Cada elemento de la vuelta anterior es una vuelta en esta
		foreach($corridas[$i-1] as $j=>$itera) {
			$subletras = str_split($itera);
			$subletras_c = count($subletras)-1;
			
			// Cada letra de las letras sobrantes es otra vuelta
			foreach ($letras_slice as $itera2) {
				$letra_fin = $elementos_invert[$subletras[$subletras_c]];
				$letra_coteja = $elementos_invert[$itera2];
				
				if (!in_array($itera2, $subletras) && ($letra_fin < $letra_coteja)
				) {
					$corridas[$i][] = $itera.",".$itera2;
					$resultados[] = $itera.",".$itera2;
				}
			}
		}
	}
	return $resultados;
}
