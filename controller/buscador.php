<?php

require '../model/header.php';
require '../model/conexion.php';

class   BuscadorApi
{

    public static function buscadorGeneral()
    {
        $datos = json_decode(file_get_contents('php://input'));
        $valor = "'%" . $datos->valor . "%'";
        $stmt = Conexion::conectar()->prepare("SELECT * FROM   $datos->tabla WHERE $datos->item like $valor ORDER BY  $datos->item  ASC LIMIT 5");
        $stmt->execute();
        $result = $stmt->fetchAll();
        exit(json_encode($result));
    }
}


BuscadorApi::buscadorGeneral();
