<?php
require '../model/header.php';
require '../model/conexion.php';

class CitasControlador
{
	public static function ctrMostrarCita()
	{

		
		if (isset($_GET["id_cita"])) {
			$stmt = Conexion::conectar()->prepare("SELECT T1.* , CONCAT_WS(' ', T2.nombre_paciente , T2.apaterno_paciente , T3.amaterno_medico) AS nombre_paciente,
				CONCAT_WS(' ' , T3.nombre_medico , T3.apaterno_medico, T3.amaterno_medico) AS nombre_medico
				FROM tbl_citas T1 
				INNER JOIN tbl_pacientes T2 ON T1.id_paciente = T2.id_paciente
				INNER JOIN tbl_medicos T3 ON T1.id_medico = T3.id_medico

				WHERE T1.id_cita = :id_cita");
			$stmt->bindParam(":id_cita", $_GET["id_cita"], PDO::PARAM_INT);
			$stmt->execute();
			$response = $stmt->fetch();
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
	public static function ctrAgregarCita($datos)
	{
		$stmt = Conexion::conectar()->prepare("INSERT INTO tbl_citas (id_paciente, id_medico, fechaProgramada_cita, horaInicio_cita, horaFin_cita, asunto_cita , enfermedad_cita,estado_cita,  medicamentos_cita , nota_cita, pago_cita, sinstomas_cita , estatus_pago) 
		VALUES  (:id_paciente, :id_medico, :fechaProgramada_cita, :horaInicio_cita, :horaFin_cita, :asunto_cita , :enfermedad_cita, :estado_cita,  :medicamentos_cita , :nota_cita, :pago_cita, :sinstomas_cita, :estatus_pago) ;");
		$stmt->bindParam(":id_paciente", $datos->id_paciente, PDO::PARAM_STR);
		$stmt->bindParam(":id_medico", $datos->id_medico, PDO::PARAM_STR);
		$stmt->bindParam(":fechaProgramada_cita", $datos->fechaProgramada_cita, PDO::PARAM_STR);
		$stmt->bindParam(":horaInicio_cita", $datos->horaInicio_cita, PDO::PARAM_STR);
		$stmt->bindParam(":horaFin_cita", $datos->horaFin_cita, PDO::PARAM_STR);
		$stmt->bindParam(":asunto_cita", $datos->asunto_cita, PDO::PARAM_STR);
		$stmt->bindParam(":enfermedad_cita", $datos->enfermedad_cita, PDO::PARAM_STR);
		$stmt->bindParam(":estado_cita", $datos->estado_cita, PDO::PARAM_STR);
		$stmt->bindParam(":medicamentos_cita", $datos->medicamentos_cita, PDO::PARAM_STR);
		$stmt->bindParam(":nota_cita", $datos->nota_cita, PDO::PARAM_STR);
		$stmt->bindParam(":pago_cita", $datos->pago_cita, PDO::PARAM_STR);
		$stmt->bindParam(":sinstomas_cita", $datos->sinstomas_cita, PDO::PARAM_STR);
		$stmt->bindParam(":estatus_pago", $datos->estatus_pago, PDO::PARAM_STR);

		if ($stmt->execute()) {
			$stmt2 = Conexion::conectar()->prepare("SELECT MAX(id_cita) AS id_cita FROM tbl_citas");
			$stmt2->execute();
			$response = $stmt2->fetch();
			$codigo = "CT00" . $response["id_cita"];
			$stmt3 = Conexion::conectar()->prepare("UPDATE tbl_citas SET folio_cita = :codigo WHERE  id_cita= :id_cita;");
			$stmt3->bindParam(':codigo', $codigo, PDO::PARAM_STR);
			$stmt3->bindParam(':id_cita', $response["id_cita"], PDO::PARAM_STR);
			$response = ($stmt3->execute()) ? true : false;
			echo json_encode(["response" =>  $response, "ultimo_id" => $codigo]);
		} else {
			echo json_encode(["response" =>  false]);
		}
	}
	public static function ctrActulizarCita($datos)
	{
		$stmt = Conexion::conectar()->prepare("UPDATE tbl_citas SET id_paciente = :id_paciente, id_medico = :id_medico, fechaProgramada_cita = :fechaProgramada_cita, 
			horaInicio_cita = :horaInicio_cita, horaFin_cita = :horaFin_cita, asunto_cita = :asunto_cita, enfermedad_cita = :enfermedad_cita, estado_cita = :estado_cita,
			medicamentos_cita = :medicamentos_cita, nota_cita = :nota_cita, pago_cita = :pago_cita, sinstomas_cita = :sinstomas_cita, estatus_pago = :estatus_pago
			WHERE  id_cita =:id_cita");
		$stmt->bindParam(":id_cita", $datos->id_cita, PDO::PARAM_INT);
		$stmt->bindParam(":id_paciente", $datos->id_paciente, PDO::PARAM_STR);
		$stmt->bindParam(":id_medico", $datos->id_medico, PDO::PARAM_STR);
		$stmt->bindParam(":fechaProgramada_cita", $datos->fechaProgramada_cita, PDO::PARAM_STR);
		$stmt->bindParam(":horaInicio_cita", $datos->horaInicio_cita, PDO::PARAM_STR);
		$stmt->bindParam(":horaFin_cita", $datos->horaFin_cita, PDO::PARAM_STR);
		$stmt->bindParam(":asunto_cita", $datos->asunto_cita, PDO::PARAM_STR);
		$stmt->bindParam(":enfermedad_cita", $datos->enfermedad_cita, PDO::PARAM_STR);
		$stmt->bindParam(":estado_cita", $datos->estado_cita, PDO::PARAM_STR);
		$stmt->bindParam(":medicamentos_cita", $datos->medicamentos_cita, PDO::PARAM_STR);
		$stmt->bindParam(":nota_cita", $datos->nota_cita, PDO::PARAM_STR);
		$stmt->bindParam(":pago_cita", $datos->pago_cita, PDO::PARAM_STR);
		$stmt->bindParam(":sinstomas_cita", $datos->sinstomas_cita, PDO::PARAM_STR);
		$stmt->bindParam(":estatus_pago", $datos->estatus_pago, PDO::PARAM_STR);
		$response = ($stmt->execute()) ? true : false;
		echo json_encode(["response" =>  $response]);
	}
	public static function ctrEliminarCita()
	{
		$stmt = Conexion::conectar()->prepare("DELETE FROM tbl_citas WHERE id_cita = :id_cita");
		$stmt->bindParam(":id_cita", $_GET["id_cita"], PDO::PARAM_INT);
		$results = ($stmt->execute())  ? true : false;
		echo json_encode(["response" =>  $results]);
	}
}
switch ($_SERVER["REQUEST_METHOD"]) {
	case 'GET':
		CitasControlador::ctrMostrarCita();
		break;
	case 'POST':
		$datos = json_decode(file_get_contents('php://input'));
		CitasControlador::ctrAgregarCita($datos);
		break;
	case 'PUT':
		$datos = json_decode(file_get_contents('php://input'));
		CitasControlador::ctrActulizarCita($datos);
		break;
	case 'DELETE':
		CitasControlador::ctrEliminarCita();
		break;
	default:
		echo json_encode(["Error" => "Accion no requerida"]);
		break;
}
