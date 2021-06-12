<?php

require '../model/header.php';
require '../model/conexion.php';

class PaqueteController
{

    public static function ctrMostrarPaquetes()
    {
        if (isset($_GET["id_paquete"])) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_paquetes WHERE id_paquete = :id_paquete");
            $stmt->bindParam(":id_paquete", $_GET["id_paquete"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_paquetes");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }
        echo json_encode($response);
    }
    public static function ctrAgregarPaquetes($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_paquetes (nombre_paquete , monto_paquete) VALUES (:nombre_paquete , :monto_paquete)");
        $stmt->bindParam(":nombre_paquete", $datos->nombre_paquete, PDO::PARAM_STR);
        $stmt->bindParam(":monto_paquete", $datos->monto_paquete, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarPaquetes($datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE tbl_paquetes SET nombre_paquete = :nombre_paquete,  monto_paquete = :monto_paquete WHERE  id_paquete =:id_paquete");
        $stmt->bindParam(":id_paquete", $datos->id_paquete, PDO::PARAM_INT);
        $stmt->bindParam(":nombre_paquete", $datos->nombre_paquete, PDO::PARAM_STR);
        $stmt->bindParam(":monto_paquete", $datos->monto_paquete, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrEliminarPaquete()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_paquetes WHERE id_paquete = :id_paquete");
        $stmt->bindParam(":id_paquete", $_GET["id_paquete"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? "ok" : "error";
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        PaqueteController::ctrMostrarPaquetes();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        PaqueteController::ctrAgregarPaquetes($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        PaqueteController::ctrActulizarPaquetes($datos);
        break;
    case 'DELETE':
        PaqueteController::ctrEliminarPaquete();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
