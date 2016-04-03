<?php

$dImplicantes = array('A','A,H','A','A,B','C');
$dImplicados = array('A','A,H','B','B,C','D');

foreach ($dImplicantes as $key => $implicantes){ 

    if($dImplicantes[$key] == $dImplicados[$key]){

        unset($dImplicantes[$key]);
        unset($dImplicados[$key]);
    }

    if(strpos($dImplicantes[$key],',') !== false){

        //echo $dImplicantes[$key];
        //echo "<br/>";

        $elementosImplicante = explode(',', $dImplicantes[$key]);

        foreach($elementosImplicante as $elemento){

            if(strpos($dImplicados[$key],$elemento) !== false){

                unset($dImplicantes[$key]);
                unset($dImplicados[$key]);
            }    
        }
    }   
}

echo "Implicantes: <pre>";
print_r(array_values($dImplicantes));
echo "</pre>";

echo "Implicantes: <pre>";
print_r(array_values($dImplicados));
echo "</pre>";