<?php

Class Recubrimiento{

	private $_lDependenciasImplicantes = array();
	private $_lDependenciasImplicadas = array();
	private $_l0 = array();
	private $_l1 = array();
	private $_l2 = array();
	private $_clavesCandidatas = array();

	public function __construct($lDependenciasImplicantes,$lDependenciasImplicadas){

		$dependency = $this->eliminarDependenciasTriv($lDependenciasImplicantes,$lDependenciasImplicadas);

		$this->_lDependenciasImplicantes = $dependency['implicantes'];
		$this->_lDependenciasImplicadas = $dependency['implicados'];
	}	

	private function eliminarDependenciasTriv($lDependenciasImplicantes,$lDependenciasImplicadas){

		$dependencyFilter = array();

		$lDependenciasImplicantes = str_replace(" ", "", $lDependenciasImplicantes);
		$lDependenciasImplicadas = str_replace(" ", "", $lDependenciasImplicadas);

		foreach ($lDependenciasImplicantes as $key => $implicantes){ 

			if($lDependenciasImplicantes[$key] == $lDependenciasImplicadas[$key]){

				unset($lDependenciasImplicantes[$key]);
				unset($lDependenciasImplicadas[$key]);
			}

			if(strpos($lDependenciasImplicantes[$key],',') !== false){

				$elementosImplicante = explode(',', $lDependenciasImplicantes[$key]);

				foreach($elementosImplicante as $elemento){

					if(strpos($lDependenciasImplicadas[$key],$elemento) !== false){

		    			unset($lDependenciasImplicantes[$key]);
		    			unset($lDependenciasImplicadas[$key]);
					}    
				}
			}   
		}

		$dependencyFilter['implicantes'] = array_values($lDependenciasImplicantes);
		$dependencyFilter['implicados'] = array_values($lDependenciasImplicadas);

		return $dependencyFilter;
	}

	public function calcularL0(){
		
		# Paso 1 Calculo de L0

		$l0 = array();

		foreach ($this->_lDependenciasImplicantes as $key => $implicantes){      
	     
	        $arrayImplicado = explode(',', $this->_lDependenciasImplicadas[$key]);

			if(count($arrayImplicado)>1){
	 		
	 			if(is_array($arrayImplicado)){

	 				foreach($arrayImplicado as $valorcadaImplicado){

	 					$l0[] = "$implicantes=>$valorcadaImplicado";
	 				}
	 			}

			}else{
			
				$l0[] = "$implicantes=>".$this->_lDependenciasImplicadas[$key];
			} 		  
		}

        $this->_l0 = array_unique($l0);
        /*echo "<pre> L0:";print_r($this->_l0);echo "</pre>";*/

		return $this->_l0;
	}

	public function calcularL1(){

		# Paso 2 Calculo de L1

		$l0 = str_replace(" ","",$this->_getL0());
	
		foreach ($l0 as $key => $valor){

			$splitDependences = explode('=>',$valor);
			$implicantesL0[] = $splitDependences[0];
			$implicadosL0[] = $splitDependences[1];
		}

		$l1 = array();

		foreach ($implicantesL0 as $key => $valor){
			
			$implicantesl0 = explode(',',$valor);
			
			if(count($implicantesl0) > 1){
				
				$combinacionesL0 = $this->combinarAtributos2($valor);

				for ($inicio = 0; $inicio < count($combinacionesL0); $inicio++){

					$cierre[$valor][$combinacionesL0[$inicio]] = $this->calcularCierre($implicantesL0,$implicadosL0,$combinacionesL0[$inicio]);

					if(strpos($cierre[$valor][$combinacionesL0[$inicio]],$implicadosL0[$key]) !== false){
			
						$l1[] = "$combinacionesL0[$inicio]=>$implicadosL0[$key]";
						$inicio = count($combinacionesL0);
					}
				}
				
			}else{
				
				$l1[] = "$valor=>$implicadosL0[$key]";
			}
		}

   		$this->_l1 = array_unique($l1);
		//echo "<pre> l1:";print_r($this->_l1);echo "</pre>";

		return $this->_l1;
	}

	public function calcularL2(){

		# Paso 3 Calculo de L2, eliminando relaciones redundantes

		$l1 = $this->_getL1();

		foreach($l1 as $posicion => $redundante){

			//se crea un array con el contenido del array L1 en la posicion actual
			$implicante = explode('=>', $redundante);
			//para cada relacion en L1 se crea un array Pre_L2 con el contenido entero de L1
			$Pre_L2 = $l1;
			//se elimina la posicion actual del arreglo para ver si es redundante
			unset($Pre_L2[$posicion]);
			//se crea el conjunto de implicantes e implicados para le nvo array Pre_l2
			foreach($Pre_L2 as $id => $contenido){

				$splitDependences = explode('=>', $contenido);
				$implicantesPre_L2[] = $splitDependences[0];
				$implicadosPre_L2[] = $splitDependences[1];
			}
			//Se calcula el cierre excluyendo del conjunto de relaciones el elemento evaluado
			$cierre = $this->calcularCierre($implicantesPre_L2, $implicadosPre_L2, $implicante[0]);

			if(strpos($cierre, $implicante[1]) == false){//si no encuentra al implicado de la dependencia en evaluacion en el cierre que lo excluye, añade esta relacion al conjunto L2
				
				$l2[] = $redundante;
			}
			//se vacian los arrays utilizados para reutilizarlos vacios
			unset($Pre_L2);
			unset($implicantesPre_L2);
			unset($implicadosPre_L2);
		}

		$this->_l2 = $l2;

		return $this->_l2;
	}

	public function calcularClavesCandidatas($tAtributos){

		# Paso 4 Definir el conjunto de claves candidatas

		$l2 = $this->_getL2();

		#Se separan los implicantes de los implicados de la relacion L2
		foreach ($l2 as $key => $variable){

			$splitDependences = explode('=>', $variable);
			$implicantesL2[] = $splitDependences[0];
			$implicadosL2[] = $splitDependences[1];
		}

		#se calculan todas las posibles combinaciones del conjunto de atributos T
		$Pre_claves = $this->combinarAtributos2(implode(',',$tAtributos));
		//print_r($Pre_claves);
		foreach($Pre_claves as $id => $clave){
			#para cada combinacion se calcula el cierre
			$cierre = $this->calcularCierre($implicantesL2, $implicadosL2, $clave);
			
			#se convierte el cierre obtenido en un array para compararlo
			$arrayCierre = explode(',', $cierre);
			#se verifica si el cierre obtenido corresponde al conjunto completo de atributos T
			$diferencia = count(array_diff($tAtributos,$arrayCierre));
			if($diferencia == 0){
				#si la diferencia entre ambos arays, es 0 o nula, se añade la combinacion al conjunto de super claves candidatas.
				$Pre_ClavesT[] = $clave;
			}
		}

		#Calculo las claves candidatas
		#se crea un conjunto vacio en donde se van a almacenar las claves candidatas
		$clavesCandidatas = array();
		#se validan una a una las claves del conjunto de posibles claves candidatas

		for($x=0; $x < count($Pre_ClavesT); $x++){
			#se hace el llamado de la funcion de verificacion para saber si la combinacion de atributos existe o no dentro del conjunto de claves
			$a = $this->verificacion($clavesCandidatas, $Pre_ClavesT[$x]);

			if($a == false){
				#si la combinacion de atributos no existe en el conjunto de claves, dicha combinacion se añade al conjunto de claves candidatas.
				$clavesCandidatas[] = $Pre_ClavesT[$x];
			}
		}

		//echo "<pre> Conjunto Claves Candidatas: ";print_r($clavesCandidatas);echo "</pre>";
		$this->_clavesCandidatas = $clavesCandidatas;

		return $this->_clavesCandidatas;

	}

    private function _getL0(){

    	return $this->_l0;
    }

    private function _getL1(){

    	return $this->_l1;
    }

    private function _getL2(){

    	return $this->_l2;
    }
		
    private function calcularCierre($implicantesDF, $implicadosDF, $cadena){

		#añade el primer elemento enviado segun lo que dice el axioma de Reflexividad
		$cierre = $cadena;

		foreach($implicantesDF as $id => $implicante){
			#generar todas las posibles combinaciones de la variable cierre
			$conjunto = $this->combinarAtributos2($cierre);
           
			foreach ($conjunto as $pos => $atributo){
				#para cada combinacion se verifica si corresponde a un implicante del conjunto de relaciones.
				if($atributo == $implicante){
					#en caso afirmativo, la variable cadena, es complementada con el valor del implicado asociado al implicante
					$cierre = $cierre.",".$implicadosDF[$id];
				}
			}
		}

		$precierre = explode(',',$cierre);
		$precierre = array_unique($precierre);
		asort($precierre);
		#se organiza el contenido del array y se eliminan datos duplicados. Adicionalmente el array se convierte a un string 
		$cierre = implode(',',$precierre);

		return $cierre;
	}

	private function combinarAtributos2($Cadena){
	
		$elementos = explode(',',$Cadena);
		$elementos_invert = array_flip($elementos);
		$cuantos = count($elementos);
		$corridas = array();
		$resultados = $elementos;
		
		for ($i=1; $i<=$cuantos; $i++) {
			
			
			if (!isset($corridas[$i-1])) {
				$corridas[$i] = $elementos;
				continue;
			}

			$elementos_new = $elementos;
			
			$letras_slice = array_slice($elementos_new, ($i-1));
			$letras_rebuilt = implode(",", $letras_slice);
			
			foreach($corridas[$i-1] as $j=>$itera){

				$subletras = str_split($itera);
				$subletras_c = count($subletras)-1;
				
				foreach ($letras_slice as $itera2){

					$letra_fin = $elementos_invert[$subletras[$subletras_c]];
					$letra_coteja = $elementos_invert[$itera2];
					
					if (!in_array($itera2, $subletras) && ($letra_fin < $letra_coteja)){

						$corridas[$i][] = $itera.",".$itera2;
						$resultados[] = $itera.",".$itera2;
					}
				}
			}
		}

		return $resultados;
	}

	private function verificacion($ConjutoGeneral, $vlrEvaluar){

		#se verifica si el conjuntogeneral esta vacio
		if(count($ConjutoGeneral) ==0){
			#en caso afirmativo, se retorna falso
			return false;

		}else{

			#en caso contrario, crea una variable numerica para contar las apariciones.
			$apariciones = 0;
			#para cada elemento del conjunto general se evaluará si coincide o no con las combinaciones de la clave a evaluar 
			foreach($ConjutoGeneral as $posicion => $clave){
				#se calcula la combinacion de todos los atributos de la clave candidata en evaluacion
				$conjunto = $this->combinarAtributos2($vlrEvaluar);	
				foreach($conjunto as $combinaciones => $combinacion){
					#para cada combinacion se verifica si existe dentro del conjunto de claves candidatas del conjunto general
					if($combinacion == $clave){
						#en caso de que alguna combinacion de la clave en evaluacion figure como clave del conjunto general, se incrementa el valor de la variable apariciones.
						$apariciones = $apariciones +1;
					}
				}
			}
		}

		#se evalua el valor de la variable apariciones, en caso que sea mayor que 0 se retorna el valor true para indicar que el dato ya existe en el conjunto general, en caso contrario se retorna false para incluirlo dentro del conjunto general.
		if($apariciones > 0){
			return true;
		}
		else{
			return false;
		}
	}
}