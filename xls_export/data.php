<?php

/**
* Contains functions to read content from "Inventario" database for subsequent export
*
* @author cabrera-dcc (http://cabrera-dcc/github.io)
* @copyright Copyright (c) 2015, Daniel Cabrera Cebrero
* @license GNU General Public License (GPLv3 - https://www.gnu.org/copyleft/gpl.html)
* @version Beta-1 (rev. 20150508)
*/

class Data {
	private function connectDB() {
		require('../config.php');

		try {
			$db = new PDO("mysql:host=localhost;dbname=$bd", $usuario,$passwd); 
			$db -> exec("set names utf8");
			$db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			$db->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true); 
			return($db);
		} catch (PDOxception $e) {
			print"<p>Error: No puede conectarse con la base de datos</p>";
			exit();
		}
	}

	function getRows($target) {
		$connection = $this->connectDB();
		$query = "SELECT * FROM $target";
		$rows = $connection->query($query);
		return $rows;
	}

	function getHTML($rows,$target) {
		$result = "<html>";
		$result .= "<head>";
		$result .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
		$result .= "</head>";
		$result .= "<body>";
		$result .= '<table>';
		$result = $this->setTitles($result,$target);
		
		if($rows!=null)
			foreach($rows as $row) {
				$result .= '<tr>';
				$result = $this->setFields($result,$target,$row);
				$result .= '</tr>';
			}

		$result .= '</body>';
		$result .= '</html>';

		return $result;
	}

	function setTitles($result,$target)
	{
		switch($target){
			case "categorias":
				$result .= "<tr> <th>ID</th> <th>Nombre </th> <th>Descripcion</th> <th>Marca</th> <th>Familia</th><th>Observaciones1</th>"
					. "<th>Observaciones2</th> <th>Observaciones3</th> <th>Fecha Alta</th> <th>Imagen</th></tr>";
				break;
			case "elementos":
				$result .= "<tr><th>ID</th><th>Unidades</th><th>ID Categoría</th><th>Nombre Categoría</th><th>ID Procedencia</th><th>Nombre Procedencia</th>"
					. "<th>ID Departamento</th><th>Nombre Departamento</th><th>ID Ubicación</th><th>Nombre Ubicación</th><th>Estado</th><th>Fecha Alta</th><th>Fecha Baja</th><th>Tlno Resp.</th><th>Fecha Fin Gar.</th><th>Nº Serie</th>"
					. "<th>Nº Serie Ext.</th><th>Observaciones1</th><th>Observaciones2</th><th>Observaciones3</th><th>Observaciones4</th>"
					. "</tr>";
				break;
			case "unidades_organizativas":
				$result .= '<tr><th>ID</th><th>Nombre</th><th>Función</th></tr>';
				break;
			case "ubicaciones":
				$result .= '<tr><th>ID</th><th>Nombre</th><th>Función</th></tr>';
				break;
			case "familias":
				$result .= '<tr><th>ID</th><th>Nombre</th></tr>';
				break;
			case "procedencias":
				$result .= '<tr><th>ID</th><th>Nombre</th></tr>';
				break;
		}

		return $result;
	}

	function getName($ref,$table)
	{
		$connection = $this->connectDB();
		$query = "SELECT * FROM $table WHERE id=$ref";
		$rows = $connection->query($query);

		foreach ($rows as $row) {
			return $row["nombre"];
		}
	}

	function setFields($result,$target,$row)
	{
		switch($target){
			case "categorias":
				$result .= "<td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["descripcion"] . "</td><td>"
				. $row["marca"] . "</td><td>" . $row["id_familia"] . "</td><td>" . $row["observaciones1"] . "</td><td>"
				. $row["observaciones2"] . "</td><td>" . $row["observaciones3"] . "</td><td>" . $row["fecha_alta"] . "</td><td>"
				. $row["imagen"] . "</td>";
				break;
			case "elementos":
				$result .= "<td>" . $row["nref"] . "</td><td>" . $row["unidades"] . "</td><td>" . $row["id_categoria"] . "</td><td>"
					. $this->getName($row["id_categoria"],"categorias") . "</td><td>"
					. $row["id_procedencia"] . "</td><td>" . $this->getName($row["id_procedencia"],"procedencias") . "</td><td>"
					. $row["id_pertenencia"] . "</td><td>" . $this->getName($row["id_pertenencia"],"unidades_organizativas") . "</td><td>"
					. $row["id_ubicacion"] . "</td><td>" . $this->getName($row["id_ubicacion"],"ubicaciones") . "</td><td>"
					. $row["estado"] . "</td><td>" . $row["fecha_alta"] . "</td><td>" . $row["fecha_baja"] . "</td><td>"
					. $row["tfno_resp"] . "</td><td>" . $row["fecha_fin_gar"] . "</td><td>" . $row["n_serie"] . "</td><td>"
					. $row["n_serie_ext"] . "</td><td>" . $row["observaciones1"] . "</td><td>" . $row["observaciones2"]. "</td><td>"
					. $row["observaciones3"] . "</td><td>" . $row["observaciones4"] . "</td>";
				break;
			case "unidades_organizativas":
				$result .= "<td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["funcion"] . "</td>";
				break;
			case "ubicaciones":
				$result .= "<td>" . $row["id"] . "</td><td>" . $row["nombre"] . "</td><td>" . $row["funcion"] . "</td>";
				break;
			case "familias":
				$result .= "<td>" . $row["id"] . "</td><td>" . $row["nombre"];
				break;
			case "procedencias":
				$result .= "<td>" . $row["id"] . "</td><td>" . $row["nombre"];
				break;
		}

		return $result;
	}

	
}