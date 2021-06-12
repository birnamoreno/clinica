<?php

require '../model/header.php';
require '../model/conexion.php';

class UsuarioController
{

    public static function ctrMostrarUsuarios()
    {

        if (isset($_GET["id_paciente"])) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_pacientes WHERE id_paciente = :id_paciente");
            $stmt->bindParam(":id_paciente", $_GET["id_paciente"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_pacientes");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }

        echo json_encode($response);
    }


    public static function ctrAgregarUsuario($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_pacientes(nombre_paciente, apaterno_paciente, amaterno_paciente, correo_paciente, domicilio_paciente, rfc_paciente, alergias_paciente, telefono_paciente, status_paciente) 
        VALUES (:nombre_paciente, :apaterno_paciente, :amaterno_paciente, :correo_paciente, :domicilio_paciente, :rfc_paciente, :alergias_paciente, :telefono_paciente, :status_paciente)");

        $stmt->bindParam(":nombre_paciente", $datos->nombre_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":apaterno_paciente", $datos->apaterno_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_paciente", $datos->amaterno_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":correo_paciente", $datos->correo_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_paciente", $datos->domicilio_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":rfc_paciente", $datos->rfc_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":alergias_paciente", $datos->alergias_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_paciente", $datos->telefono_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":status_paciente", $datos->status_paciente, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarUsuario($datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE tbl_pacientes SET nombre_paciente = :nombre_paciente, apaterno_paciente  = :apaterno_paciente, amaterno_paciente  = :amaterno_paciente, correo_paciente  = :correo_paciente,
            domicilio_paciente  = :domicilio_paciente, rfc_paciente  = :rfc_paciente, alergias_paciente  = :alergias_paciente, telefono_paciente  = :telefono_paciente, status_paciente = :status_paciente
            WHERE  id_paciente =:id_paciente");

        $stmt->bindParam(":id_paciente", $datos->id_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":nombre_paciente", $datos->nombre_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":apaterno_paciente", $datos->apaterno_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_paciente", $datos->amaterno_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":correo_paciente", $datos->correo_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_paciente", $datos->domicilio_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":rfc_paciente", $datos->rfc_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":alergias_paciente", $datos->alergias_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_paciente", $datos->telefono_paciente, PDO::PARAM_STR);
        $stmt->bindParam(":status_paciente", $datos->status, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function eliminarUsuario()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_pacientes WHERE id_paciente = :id_paciente");
        $stmt->bindParam(":id_paciente", $_GET["id_paciente"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? true : false;
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        UsuarioController::ctrMostrarUsuarios();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        UsuarioController::ctrAgregarUsuario($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        UsuarioController::ctrActulizarUsuario($datos);
        break;
    case 'DELETE':
        UsuarioController::eliminarUsuario();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
