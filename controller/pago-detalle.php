<?php

require '../model/header.php';
require '../model/conexion.php';

class PagoDetalleController
{

    public static function ctrMostrarPagosDetalle()
    {

        if (isset($_GET["id_pago_detalle"])) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_pago_detalle WHERE id_pago_detalle = :id_pago_detalle");
            $stmt->bindParam(":id_pago_detalle", $_GET["id_pago_detalle"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_pago_detalle");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }

        echo json_encode($response);
    }

    public static function ctrAgregarPagoDetalle($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_pago_detalle(folio_pago, tipo, id_paquete_servicio, costo, iva, subtotal) 
        VALUES (:folio_pago, :tipo, :id_paquete_servicio, :costo, :iva, :subtotal)");
        $stmt->bindParam(":folio_pago", $datos->folio_pago, PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $datos->tipo, PDO::PARAM_STR);
        $stmt->bindParam(":id_paquete_servicio", $datos->id_paquete_servicio, PDO::PARAM_STR);
        $stmt->bindParam(":costo", $datos->costo, PDO::PARAM_STR);
        $stmt->bindParam(":iva", $datos->iva, PDO::PARAM_STR);
        $stmt->bindParam(":subtotal", $datos->subtotal, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarPagoDetalle($datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE tbl_pago_detalle SET folio_pago = :folio_pago, tipo  = :tipo, id_paquete_servicio  = :id_paquete_servicio, costo  = :costo,
            iva  = :iva, subtotal  = :subtotal WHERE  id_pago_detalle =:id_pago_detalle");

        $stmt->bindParam(":folio_pago", $datos->folio_pago, PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $datos->tipo, PDO::PARAM_STR);
        $stmt->bindParam(":id_paquete_servicio", $datos->id_paquete_servicio, PDO::PARAM_STR);
        $stmt->bindParam(":costo", $datos->costo, PDO::PARAM_STR);
        $stmt->bindParam(":iva", $datos->iva, PDO::PARAM_STR);
        $stmt->bindParam(":subtotal", $datos->subtotal, PDO::PARAM_STR);
        $stmt->bindParam(":id_pago_detalle", $datos->id_pago_detalle, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function eliminarPago()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_pago_detalle WHERE id_pago_detalle = :id_pago_detalle");
        $stmt->bindParam(":id_pago_detalle", $_GET["id_pago_detalle"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? true : false;
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        PagoDetalleController::ctrMostrarPagosDetalle();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        PagoDetalleController::ctrAgregarPagoDetalle($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        PagoDetalleController::ctrActulizarPagoDetalle($datos);
        break;
    case 'DELETE':
        PagoDetalleController::eliminarPago();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
