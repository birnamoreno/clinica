<?php

require '../model/header.php';
require '../model/conexion.php';

class PagoController
{

    public static function ctrMostrarPagos()
    {

        if (isset($_GET["id_pago"])) {
            
            $stmt = Conexion::conectar()->prepare("SELECT t1.* , t2.folio_cita , CONCAT_WS(' ' , t3.nombre_paciente , t3.apaterno_paciente , t3.amaterno_paciente) AS nombre_paciente,
                CONCAT_WS(' ' , t4.nombre_medico , t4.apaterno_medico , t4.amaterno_medico) AS nombre_medico 
                FROM tbl_pago t1 
                INNER JOIN tbl_citas t2 ON t1.id_cita = t2.id_cita
                INNER JOIN tbl_pacientes t3 ON  t2.id_paciente = t3.id_paciente
                INNER JOIN tbl_medicos t4 ON t2.id_medico = t4.id_medico
                WHERE id_pago = :id_pago");
            $stmt->bindParam(":id_pago", $_GET["id_pago"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {

            $stmt = Conexion::conectar()->prepare("SELECT t1.* , t2.folio_cita , CONCAT_WS(' ' , t3.nombre_paciente , t3.apaterno_paciente , t3.amaterno_paciente) AS nombre_paciente,
                CONCAT_WS(' ' , t4.nombre_medico , t4.apaterno_medico , t4.amaterno_medico) AS nombre_medico 
                FROM tbl_pago t1 
                INNER JOIN tbl_citas t2 ON t1   .id_cita = t2.id_cita
                INNER JOIN tbl_pacientes t3 ON  t2.id_paciente = t3.id_paciente
                INNER JOIN tbl_medicos t4 ON t2.id_medico = t4.id_medico;
            ");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }

        echo json_encode($response);
    }


    public static function ctrAgregarPago($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_pago(id_cita, monto_total, monto_abonado, monto_subtotal, tipo_pago, notas) 
        VALUES (:id_cita, :monto_total, :monto_abonado, :monto_subtotal, :tipo_pago, :notas)");
        $stmt->bindParam(":id_cita", $datos->id_cita, PDO::PARAM_STR);
        $stmt->bindParam(":monto_total", $datos->monto_total, PDO::PARAM_STR);
        $stmt->bindParam(":monto_abonado", $datos->monto_abonado, PDO::PARAM_STR);
        $stmt->bindParam(":monto_subtotal", $datos->monto_subtotal, PDO::PARAM_STR);
        $stmt->bindParam(":tipo_pago", $datos->tipo_pago, PDO::PARAM_STR);
        $stmt->bindParam(":notas", $datos->notas, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $stmt2 = Conexion::conectar()->prepare("SELECT MAX(id_pago) AS id_pago FROM tbl_pago");
            $stmt2->execute();
            $response = $stmt2->fetch();
            $codigo = "F000" . $response["id_pago"];
            $stmt3 = Conexion::conectar()->prepare("UPDATE tbl_pago SET folio_pago = :codigo WHERE  id_pago= :id_pago;");
            $stmt3->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt3->bindParam(':id_pago', $response["id_pago"], PDO::PARAM_STR);
            $response = ($stmt3->execute()) ? true : false;
            echo json_encode(["response" =>  $response, "folio_pago" => $codigo]);
        } else {
            echo json_encode(["response" =>  false]);
        }

        // $response = ($stmt->execute()) ? true : false;
        // echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarPago($datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE tbl_pago SET id_pago = :id_pago, monto_total  = :monto_total, monto_abonado  = :monto_abonado, monto_subtotal  = :monto_subtotal,
            tipo_pago  = :tipo_pago, notas  = :notas WHERE  id_pago =:id_pago");

        $stmt->bindParam(":id_pago", $datos->id_pago, PDO::PARAM_STR);
        $stmt->bindParam(":monto_total", $datos->monto_total, PDO::PARAM_STR);
        $stmt->bindParam(":monto_abonado", $datos->monto_abonado, PDO::PARAM_STR);
        $stmt->bindParam(":monto_subtotal", $datos->monto_subtotal, PDO::PARAM_STR);
        $stmt->bindParam(":tipo_pago", $datos->tipo_pago, PDO::PARAM_STR);
        $stmt->bindParam(":notas", $datos->notas, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function eliminarPago()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_pago WHERE id_pago = :id_pago");
        $stmt->bindParam(":id_pago", $_GET["id_pago"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? true : false;
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        PagoController::ctrMostrarPagos();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        PagoController::ctrAgregarPago($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        PagoController::ctrActulizarPago($datos);
        break;
    case 'DELETE':
        PagoController::eliminarPago();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
