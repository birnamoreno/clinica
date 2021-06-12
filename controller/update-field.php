<?php

require '../model/header.php';
require '../model/conexion.php';

class updateField
{


    public static function updateStatusCita($datos)
    {
        $stmt =  Conexion::conectar()->prepare("UPDATE tbl_citas SET estado_cita = :estado_cita,  estatus_pago = :estatus_pago WHERE id_cita = :id_cita;");
        $stmt->bindParam(":estado_cita", $datos->estado_cita, PDO::PARAM_INT);
        $stmt->bindParam(":estatus_pago", $datos->estatus_pago, PDO::PARAM_INT);
        $stmt->bindParam(":id_cita", $datos->id_cita, PDO::PARAM_INT);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }
}

$datos = json_decode(file_get_contents('php://input'));
switch ($datos->action) {
    case 'updateStatusCita':
        updateField::updateStatusCita($datos);
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
