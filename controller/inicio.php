
<?php

require '../model/header.php';
require '../model/conexion.php';

class InicioController
{

    public static function ctrMostrarTotales($datos)
    {
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) AS total FROM $datos->tbl_value");
        $stmt->execute();
        $response = $stmt->fetch();
        echo json_encode($response);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        InicioController::ctrMostrarTotales($datos);
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
