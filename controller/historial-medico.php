<?php
require '../model/header.php';
require '../model/conexion.php';
class CitasControlador
{
    public static function ctrMostrarHistorialMedico()
    {
        if (isset($_GET["id_medico"])) {
            $stmt = Conexion::conectar()->prepare("SELECT T1.* , CONCAT_WS(' ', T2.nombre_paciente , T2.apaterno_paciente , T3.amaterno_medico) AS nombre_paciente,
				CONCAT_WS(' ' , T3.nombre_medico , T3.apaterno_medico, T3.amaterno_medico) AS nombre_medico
				FROM tbl_citas T1 
				INNER JOIN tbl_pacientes T2 ON T1.id_paciente = T2.id_paciente
				INNER JOIN tbl_medicos T3 ON T1.id_medico = T3.id_medico
				WHERE T1.id_medico = :id_medico");
            $stmt->bindParam(":id_medico", $_GET["id_medico"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetchAll();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT  T1.* , CONCAT_WS(' ', T2.nombre_paciente , T2.apaterno_paciente , T3.amaterno_medico) AS nombre_paciente,
				CONCAT_WS(' ' , T3.nombre_medico , T3.apaterno_medico, T3.amaterno_medico) AS nombre_medico
				FROM tbl_citas T1 
				INNER JOIN tbl_pacientes T2 ON T1.id_paciente = T2.id_paciente
				INNER JOIN tbl_medicos T3 ON T1.id_medico = T3.id_medico");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }
        echo json_encode($response);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        CitasControlador::ctrMostrarHistorialMedico();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
