<?php

require '../model/header.php';
require '../model/conexion.php';

class UserController
{

    public static function  ctrMostrarUsers()
    {

        if (isset($_GET["id_user"])) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_users WHERE id_user = :id_user");
            $stmt->bindParam(":id_user", $_GET["id_user"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_users");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }

        echo json_encode($response);
    }


    public static function ctrAgregarUsers($datos)
    {


        $password = md5($datos->password_user);

        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_users (nombre_user, apaterno_user, amaterno_user, correo_user, domicilio_user, telefono_user, username_user , password_user, status_user, rol_user) 
            VALUES (:nombre_user, :apaterno_user, :amaterno_user, :correo_user, :domicilio_user, :telefono_user, :username_user, :password_user, :status_user, :rol_user)");

        $stmt->bindParam(":nombre_user", $datos->nombre_user, PDO::PARAM_STR);
        $stmt->bindParam(":apaterno_user", $datos->apaterno_user, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_user", $datos->amaterno_user, PDO::PARAM_STR);
        $stmt->bindParam(":correo_user", $datos->correo_user, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_user", $datos->domicilio_user, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_user", $datos->telefono_user, PDO::PARAM_STR);
        $stmt->bindParam(":username_user", $datos->username_user, PDO::PARAM_STR);
        $stmt->bindParam(":password_user", $password, PDO::PARAM_STR);
        $stmt->bindParam(":status_user", $datos->status_user, PDO::PARAM_INT);
        $stmt->bindParam(":rol_user", $datos->rol_user, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarUsers($datos)
    {

        $stmt = Conexion::conectar()->prepare("UPDATE tbl_users SET nombre_user = :nombre_user, apaterno_user = :apaterno_user, amaterno_user = :amaterno_user,
            correo_user = :correo_user, domicilio_user = :domicilio_user, telefono_user = :telefono_user, rol_user = :rol_user WHERE  id_user =:id_user");

        $stmt->bindParam(":id_user", $datos->id_user, PDO::PARAM_INT);
        $stmt->bindParam(":nombre_user", $datos->nombre_user, PDO::PARAM_STR);
        $stmt->bindParam(":apaterno_user", $datos->apaterno_user, PDO::PARAM_STR);
        $stmt->bindParam(":amaterno_user", $datos->amaterno_user, PDO::PARAM_STR);
        $stmt->bindParam(":correo_user", $datos->correo_user, PDO::PARAM_STR);
        $stmt->bindParam(":domicilio_user", $datos->domicilio_user, PDO::PARAM_STR);
        $stmt->bindParam(":telefono_user", $datos->telefono_user, PDO::PARAM_STR);
        $stmt->bindParam(":rol_user", $datos->rol_user, PDO::PARAM_INT);

        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrEliminarUser()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_users WHERE id_user = :id_user");
        $stmt->bindParam(":id_user", $_GET["id_user"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? "ok" : "error";
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        UserController::ctrMostrarUsers();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        UserController::ctrAgregarUsers($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        UserController::ctrActulizarUsers($datos);
        break;
    case 'DELETE':
        UserController::ctrEliminarUser();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
