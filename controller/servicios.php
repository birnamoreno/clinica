<?php

require '../model/header.php';
require '../model/conexion.php';

class ServiciosController
{

    public static function  ctrMostrarSerivicios()
    {

        if (isset($_GET["id_servicio"])) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_serivcios WHERE id_servicio = :id_servicio");
            $stmt->bindParam(":id_servicio", $_GET["id_servicio"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_serivcios");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }
        echo json_encode($response);
    }


    public static function ctrAgregarSerivicios($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_serivcios (nombre_servicio , monto_servicio) VALUES (:nombre_servicio , :monto_servicio)");
        $stmt->bindParam(":nombre_servicio", $datos->nombre_servicio, PDO::PARAM_STR);
        $stmt->bindParam(":monto_servicio", $datos->monto_servicio, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarSerivicios($datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE tbl_serivcios SET nombre_servicio = :nombre_servicio , monto_servicio = :monto_servicio WHERE  id_servicio =:id_servicio");
        $stmt->bindParam(":id_servicio", $datos->id_servicio, PDO::PARAM_INT);
        $stmt->bindParam(":nombre_servicio", $datos->nombre_servicio, PDO::PARAM_STR);
        $stmt->bindParam(":monto_servicio", $datos->monto_servicio, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrEliminarServicios()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_serivcios WHERE id_servicio = :id_servicio");
        $stmt->bindParam(":id_servicio", $_GET["id_servicio"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? true : false;
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        ServiciosController::ctrMostrarSerivicios();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        ServiciosController::ctrAgregarSerivicios($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        ServiciosController::ctrActulizarSerivicios($datos);
        break;
    case 'DELETE':
        ServiciosController::ctrEliminarServicios();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
