<?php

require_once('Class/Recubrimiento.php');

$recubrimiento = new Recubrimiento($_REQUEST['implicantes'],$_REQUEST['implicados']);

switch ($_REQUEST['action']) {

    case 'l0':
        echo json_encode($recubrimiento->calcularL0());
    break;

    case 'l1':
        $recubrimiento->calcularL0();
        echo json_encode($recubrimiento->calcularL1());
    break;

    case 'l2':
        $recubrimiento->calcularL0();
        $recubrimiento->calcularL1();
        echo json_encode($recubrimiento->calcularL2());
    break;

    case 'llaves':
        $recubrimiento->calcularL0();
        $recubrimiento->calcularL1();
        $recubrimiento->calcularL2();
        echo json_encode($recubrimiento->calcularClavesCandidatas($_REQUEST['atributo']));
    break;

}