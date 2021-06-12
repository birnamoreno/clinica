<?php

require '../model/header.php';
require '../model/conexion.php';

class Especialidadontroller
{

    public static function ctrMostrarEspecialidad()
    {
        if (isset($_GET["id_especialidad"])) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_especialidades WHERE id_especialidad = :id_especialidad");
            $stmt->bindParam(":id_especialidad", $_GET["id_especialidad"], PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM tbl_especialidades");
            $stmt->execute();
            $response = $stmt->fetchAll();
        }
        echo json_encode($response);
    }
    public static function ctrAgregarEspecialidad($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO tbl_especialidades (nombre_especialidad ) VALUES (:nombre_especialidad )");
        $stmt->bindParam(":nombre_especialidad", $datos->nombre_especialidad, PDO::PARAM_STR);
        // $stmt->bindParam(":id_medico", $datos->id_medico, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrActulizarEspecialidad($datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE tbl_especialidades SET nombre_especialidad = :nombre_especialidad  WHERE  id_especialidad =:id_especialidad");
        $stmt->bindParam(":id_especialidad", $datos->id_especialidad, PDO::PARAM_INT);
        $stmt->bindParam(":nombre_especialidad", $datos->nombre_especialidad, PDO::PARAM_STR);
        // $stmt->bindParam(":id_medico", $datos->id_medico, PDO::PARAM_STR);
        $response = ($stmt->execute()) ? true : false;
        echo json_encode(["response" =>  $response]);
    }

    public static function ctrEliminarEspecialidad()
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM tbl_especialidades WHERE id_especialidad = :id_especialidad");
        $stmt->bindParam(":id_especialidad", $_GET["id_especialidad"], PDO::PARAM_INT);
        $results = ($stmt->execute())  ? true : false;
        echo json_encode(["response" =>  $results]);
    }
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        Especialidadontroller::ctrMostrarEspecialidad();
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        Especialidadontroller::ctrAgregarEspecialidad($datos);
        break;
    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        Especialidadontroller::ctrActulizarEspecialidad($datos);
        break;
    case 'DELETE':
        Especialidadontroller::ctrEliminarEspecialidad();
        break;
    default:
        echo json_encode(["Error" => "Accion no requerida"]);
        break;
}
