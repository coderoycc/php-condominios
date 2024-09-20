<?php

use App\Config\Database;

require_once(__DIR__ . '/app/config/database.php');
function insert_new_condominio($name, $city = '', $address, $country = 'Bolivia') {
  $con = Database::master_instance();
  try {
    $dbname = strtolower($name);
    $dbname = str_replace('  ', ' ', $dbname);
    $dbname = str_replace(' ', '_', $dbname);
    $pin = substr($dbname, 0, 6);
    $dbname = 'condominios_' . $dbname;
    $sql = "INSERT INTO [condominios_master].[dbo].[tblCondominiosData](name, pin, dbname, address, city, country) VALUES('$name', '$pin', '$dbname', '$address', '$city', '$country');";
    $stmt = $con->prepare($sql);
    $res = $stmt->execute();
    echo 'Instanciado';
  } catch (Throwable $th) {
    echo 'Ocurrio un error ' . $th->getMessage();
  }
}

function create_db($dbname) {
  $con = Database::master_instance();
  $sql = "CREATE DATABASE $dbname COLLATE Modern_Spanish_CI_AS";
  $stmt = $con->prepare($sql);
  $res = $stmt->execute();
  return $res;
}

function master() {
  $res = create_db('condominios_nuevos_horizontes');
  if ($res) {
    insert_new_condominio('Nuevos Horizontes', 'La Paz', '', 'Bolivia');
  } else {
    echo 'Ocurrio un error al crear la base de datos';
  }
}

// master();
function estructure($dbname) {
  try {
    $sql = "USE $dbname;";
    $sqlContent = file_get_contents(__DIR__ . '/app/utils/queries/estructure.sql');

    // Eliminar las declaraciones 'GO'
    $sqlContent = preg_replace('/\bGO\b/i', ';', $sqlContent);
    $sql_1 = explode('--**separation', $sqlContent);

    $con = Database::master_instance();
    $stmt = $con->prepare($sql . $sql_1[0]);
    $res = $stmt->execute();
    echo $res;
    echo '<br>CREADO';

    $stmt2 = $con->prepare($sql . $sql_1[1]);
    $res2 = $stmt2->execute();
    echo $res2;
    echo '<br>CREADO parte 2';
  } catch (\Throwable $th) {
    var_dump($th);
  }
}
estructure('condominios_nuevos_horizontes');
