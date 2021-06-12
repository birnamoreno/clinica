<?php

require '../model/header.php';
require '../model/conexion.php';

class LoginApi
{

    public static function validarUsuariosApi()
    {
        $stmt =  Conexion::conectar()->prepare("SELECT * FROM tbl_usuarios WHERE username = :username and password = :password");
        $stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $_POST["password"], PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        exit(json_encode($result));
    }
}


LoginApi::validarUsuariosApi();
