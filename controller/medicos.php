<?php

require '../model/header.php';
require '../model/conexion.php';

class MedicoController
{

    public static function ctrMostrarMedicos()
    {
        if (isset($_GET["id_medico"])) {

            $stmt = Conexion::conectar()->prepare("SELECT T1.* , T2.nombre_especialidad 
                FROM tbl_medicos T1
                INNER JOIN tbl_especialidades T2 ON T1.id_especialidad = T2.id_especialidad 
                WHERE T1.id_medico = :id_medico");
            $stmt->bindParam(":id_medico", $_GET["id_medico"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {

            $stmt = Conexion::conectar()->prepare("SELECT T1.* , T2.nombre_especialidad FROM tbl_medicos T1
            INNER JOIN tbl_especialidades T2 ON T1.id_especialidad = T2.id_especialidad ");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }
        echo json_encode($response);
    }

    public static function ctrAgregarMedico($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_medicos(nombre_medico, apaterno_medico, amaterno_medico, correo_medico, domicilio_medico, id_especialidad, rfc_medico, telefono_medico, cedula_medico, status_medico)
        VALUES (:nombre_medico, :apaterno_medico, :amaterno_medico, :correo_medico, :domicilio_medico, :id_especialidad, :rfc_medico, :telefono_medico, :cedula_medico, :status_medico)");
        $stmt->bindParam(":nombre_medico", $datos->nombre_medico, PDO::PARAM_STR);
        $stmt->bindParam(":apaterno_medico", $datos->apaterno_medico, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_medico", $datos->amaterno_medico, PDO::PARAM_STR);
        $stmt->bindParam(":correo_medico", $datos->correo_medico, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_medico", $datos->domicilio_medico, PDO::PARAM_STR);
        $stmt->bindParam(":id_especialidad", $datos->id_especialidad, PDO::PARAM_STR);
        $stmt->bindParam(":rfc_medico", $datos->rfc_medico, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_medico", $datos->telefono_medico, PDO::PARAM_STR);
        $stmt->bindParam(":cedula_medico", $datos->cedula_medico, PDO::PARAM_STR);
        $stmt->bindParam(":status_medico", $datos->status_medico, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarMedico($datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE tbl_medicos SET nombre_medico =:nombre_medico, apaterno_medico = :apaterno_medico, amaterno_medico = :amaterno_medico, correo_medico = :correo_medico, domicilio_medico = :domicilio_medico,
             id_especialidad = :id_especialidad, rfc_medico = :rfc_medico, telefono_medico = :telefono_medico, cedula_medico = :cedula_medico, status_medico = :status_medico WHERE  id_medico =:id_medico");

        $stmt->bindParam(":id_medico", $datos->id_medico, PDO::PARAM_INT);
        $stmt->bindParam(":nombre_medico", $datos->nombre_medico, PDO::PARAM_STR);
        $stmt->bindParam(":apaterno_medico", $datos->apaterno_medico, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_medico", $datos->amaterno_medico, PDO::PARAM_STR);
        $stmt->bindParam(":correo_medico", $datos->correo_medico, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_medico", $datos->domicilio_medico, PDO::PARAM_STR);
        $stmt->bindParam(":id_especialidad", $datos->id_especialidad, PDO::PARAM_STR);
        $stmt->bindParam(":rfc_medico", $datos->rfc_medico, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_medico", $datos->telefono_medico, PDO::PARAM_STR);
        $stmt->bindParam(":cedula_medico", $datos->cedula_medico, PDO::PARAM_STR);
        $stmt->bindParam(":status_medico", $datos->status_medico, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function eliminarMedico()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_medicos WHERE id_medico = :id_medico");
        $stmt->bindParam(":id_medico", $_GET["id_medico"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? true : false;
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        MedicoController::ctrMostrarMedicos();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        MedicoController::ctrAgregarMedico($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        MedicoController::ctrActulizarMedico($datos);
        break;
    case 'DELETE':
        MedicoController::eliminarMedico();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
