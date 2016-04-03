<?php

require_once('Class/Recubrimiento.php');

$path = '/Applications/XAMPP/htdocs/ejercicio/uploads/';

if ( 0 < $_FILES['file']['error'] ){

    echo 'Error: No se pudo cargar el archivo por favor intentelo nuevamente';

}else{

    if(move_uploaded_file($_FILES['file']['tmp_name'], $path. $_FILES['file']['name'])){   

        if(file_exists($path. $_FILES['file']['name'])){

            $xml = simplexml_load_file($path. $_FILES['file']['name']);

            foreach ($xml->atributos->item_atributo as $item){

                $conjuntoAtributos[] = (string) $item;
            }

            foreach ($xml->dependencias->item_depen as $itemDepend){

               $dependences[] = (string) $itemDepend;
               $splitDependences = explode('=>',(string) $itemDepend);
               $dependenciasImplicantes[] = $splitDependences[0];
               $dependenciasImplicadas[] = $splitDependences[1];
            }

            $recubrimiento = new Recubrimiento($dependenciasImplicantes,$dependenciasImplicadas);

            $arrayResponse['conjunto'] = $conjuntoAtributos;
            $arrayResponse['dependencias'] = $dependences;
            $arrayResponse['l0'] = $recubrimiento->calcularL0();
            $arrayResponse['l1'] = $recubrimiento->calcularL1();
            $arrayResponse['l2'] = $recubrimiento->calcularL2();
            $arrayResponse['llaves'] = $recubrimiento->calcularClavesCandidatas($conjuntoAtributos);

            echo json_encode($arrayResponse);
        }

    }else{

        echo 'Error: No se pudo mover el archivo por favor intentelo nuevamente';
    }
}