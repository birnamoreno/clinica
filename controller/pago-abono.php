<?php

require '../model/header.php';
require '../model/conexion.php';

class PagoAbonoController
{

    public static function ctrMostrarPagosAbono()
    {
        if (isset($_GET["id_pago_abono"])) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_pago_abono WHERE id_pago_abono = :id_pago_abono");
            $stmt->bindParam(":id_pago_abono", $_GET["id_pago_abono"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_pago_abono");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }
        echo json_encode($response);
    }

    public static function ctrAgregarPagoAbono($datos)
    {
        $sql = "CALL sp_pago_abono(? , ? ,  ?);";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindParam(1, $datos->folio_pago, PDO::PARAM_STR);
        $stmt->bindParam(2, $datos->monto_abono, PDO::PARAM_STR);
        $stmt->bindParam(3, $datos->tipo, PDO::PARAM_STR);
        $stmt->execute();
        $response = $stmt->fetch();

        // $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarPagoAbono($datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE tbl_pago_abono SET folio_pago = :folio_pago, monto_abono  = :monto_abono,tipo  = :tipo
            WHERE  id_pago_abono =:id_pago_abono");
        $stmt->bindParam(":folio_pago", $datos->folio_pago, PDO::PARAM_STR);
        $stmt->bindParam(":monto_abono", $datos->monto_abono, PDO::PARAM_STR);
        $stmt->bindParam(":tipo", $datos->tipo, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode([$response]);
    }

    public static function eliminarPagoAbono()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_pago_abono WHERE  id_pago_abono=:id_pago_abono;");
        $stmt->bindParam(":id_pago_abono", $_GET["id_pago_abono"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? true : false;
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        PagoAbonoController::ctrMostrarPagosAbono();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        PagoAbonoController::ctrAgregarPagoAbono($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        PagoAbonoController::ctrActulizarPagoAbono($datos);
        break;
    case 'DELETE':
        PagoAbonoController::eliminarPagoAbono();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
