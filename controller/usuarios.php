<?php

require '../model/header.php';
require '../model/conexion.php';

class UsuarioController
{

    public static function ctrMostrarUsuarios()
    {

        if (isset($_GET["id_usuario"])) {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_usuarios WHERE id_usuario = :id_usuario");
            $stmt->bindParam(":id_usuario", $_GET["id_usuario"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {

            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_usuarios");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }

        echo json_encode($response);
    }


    public static function ctrAgregarUsuario($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_usuarios(nombre_usuario, aparterno_usuario, amaterno_usuario, correo_usuario, domicilio_usuario, rfc_usuario, alergias_usuario, telefono_usuario, status_usuario) 
        VALUES (:nombre_usuario, :aparterno_usuario, :amaterno_usuario, :correo_usuario, :domicilio_usuario, :rfc_usuario, :alergias_usuario, :telefono_usuario, :status_usuario)");

        $stmt->bindParam(":nombre_usuario", $datos->nombre_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":aparterno_usuario", $datos->aparterno_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_usuario", $datos->amaterno_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":correo_usuario", $datos->correo_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_usuario", $datos->domicilio_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":rfc_usuario", $datos->rfc_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":alergias_usuario", $datos->alergias_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_usuario", $datos->telefono_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":status_usuario", $datos->status_usuario, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? 'ok' : 'error';
        echo json_encode(["success" =>  $response]);
    }

    public static function ctrActulizarUsuario($datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE tbl_usuarios SET nombre_usuario = :nombre_usuario, aparterno_usuario = :aparterno_usuario, amaterno_usuario = :amaterno_usuario,
            correo_usuario = :correo_usuario, domicilio_usuario = :domicilio_usuario, rfc_usuario = :rfc_usuario, alergias_usuario = :alergias_usuario, telefono_usuario = :telefono_usuario,
             status_usuario = :status_usuario WHERE  id_usuario =:id_usuario");

        $stmt->bindParam(":id_usuario", $datos->id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":nombre_usuario", $datos->nombre_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":aparterno_usuario", $datos->aparterno_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_usuario", $datos->amaterno_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":correo_usuario", $datos->correo_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_usuario", $datos->domicilio_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":rfc_usuario", $datos->rfc_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":alergias_usuario", $datos->alergias_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_usuario", $datos->telefono_usuario, PDO::PARAM_STR);
        $stmt->bindParam(":status_usuario", $datos->status_usuario, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? 'ok' : 'error';
        echo json_encode(["success" =>  $response]);
    }

    public static function eliminarUsuario()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_usuarios WHERE id_usuario = :id_usuario");
        $stmt->bindParam(":id_usuario", $_GET["id_usuario"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? "ok" : "error";
        echo json_encode(["success" =>  $results]);
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
