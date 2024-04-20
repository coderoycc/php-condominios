<?php

namespace App\Models;

use App\Config\Database;
use App\Config\Accesos;

class Envio {
  private static
    $sql = "SELECT  
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_origen) as origen,
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_destino) as destino,
	  (SELECT nombre FROM tblUsuario WHERE idUsuario = e.id_usuario_envio) as usuario_envio,
	  e.*
  FROM tblEnvio e";
  private static $sql_recibo = "SELECT  
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_origen) as origen,
	  (SELECT lugar FROM tblLugar WHERE idLugar = e.id_lugar_destino) as destino,
	  (SELECT nombre FROM tblUsuario WHERE idUsuario = e.id_usuario_envio) as usuario_envio,
	  e.*
  FROM tblEnvio e
  WHERE e.estado != 'ENTREGADO'";
  public int $idEnvio;
  public string $codigo;
  public int $id_usuario_envio;
  public string $usuario_envio;
  public int $id_usuario_entrega;
  public int $id_usuario_recibe;
  public string $usuario_recibe;
  public string $estado, $detalle_envio, $fecha_envio, $fecha_llegada, $observacion_llegada, $fecha_estimada;
  public string $nombre_origen;
  public string $ci_origen;
  public string $celular_origen;
  public int $id_lugar_origen;
  public string $origen;

  public string $nombre_destino;
  public string $ci_destino;
  public int $id_lugar_destino;
  public string $celular_destino;
  public string $destino;
  public string $fecha_entrega;
  public string $capturas; // nombres de las imagenes separados por |

  public float $costo; // costo de envio
  public string $observacion_envio;
  public float $peso;
  public int $cantidad;
  public string $pagado; // PAGADO | POR PAGAR
  public int $saldado; // 0 = no saldado | 1 = saldado
  public function __construct($idEnvio = 0) {
    if ($idEnvio == 0) {
      $this->objectNull();
    } else {
      $con = Database::getInstace();
      $sql = self::$sql . " WHERE e.idEnvio = $idEnvio";
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch();
      if ($row) {
        $this->objectNull();
        $this->load($row);
      } else {
        $this->objectNull();
      }
    }
  }

  public function insert() { // nuevo envio
    try {
      $fechaenvio = date('Y-m-d\TH:i:s.v', strtotime($this->fecha_envio));
      $fechaestimada = date('Y-m-d\TH:i:s.v', strtotime($this->fecha_estimada));
      $sql = "INSERT INTO tblEnvio(codigo, id_usuario_envio, estado, detalle_envio, fecha_envio, fecha_estimada, nombre_origen, ci_origen, celular_origen, id_lugar_origen, nombre_destino, ci_destino, id_lugar_destino, celular_destino, costo, observacion_envio, peso, cantidad, pagado, saldado)
      VALUES (:codigo, :idUsuarioEnvio, 'ENVIADO', :detalle_envio, :fecha_envio, :fecha_estimada, :nombre_origen, :ci_origen, :celular_origen, :id_lugar_origen, :nombre_destino, :ci_destino, :id_lugar_destino, :celular_destino, :costo, :observacion_envio, :peso, :cantidad, :pagado, :saldado);";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $params = ['codigo' => $this->codigo, 'idUsuarioEnvio' => $this->id_usuario_envio, 'detalle_envio' => $this->detalle_envio, 'fecha_envio' => $fechaenvio, 'fecha_estimada' => $fechaestimada, 'nombre_origen' => $this->nombre_origen, 'ci_origen' => $this->ci_origen, 'celular_origen' => $this->celular_origen, 'id_lugar_origen' => $this->id_lugar_origen, 'nombre_destino' => $this->nombre_destino, 'ci_destino' => $this->ci_destino, 'id_lugar_destino' => $this->id_lugar_destino, 'celular_destino' => $this->celular_destino, 'costo' => $this->costo, 'observacion_envio' => $this->observacion_envio, 'peso' => $this->peso, 'cantidad' => $this->cantidad, 'pagado' => $this->pagado, 'saldado' => $this->saldado];
      $res = $stmt->execute($params);
      if ($res) {
        $idEnvio = $con->lastInsertId();
        return $idEnvio;
      }
    } catch (\Exception $e) {
      print_r($e);
    }
    return 0;
  }

  public function update($anterior) {
    try {
      $cadena = "";
      $params = [];
      foreach ($this as $name => $value) {
        if ($this->$name != $anterior->$name) {
          $cadena .= "$name = :$name, ";
          // sabemos si es fecha
          if ($name == 'fecha_envio' || $name == 'fecha_estimada' || $name == 'fecha_llegada' || $name == 'fecha_entrega')
            $params[$name] = date('Y-m-d\TH:i:s.v', strtotime($this->$name));
          else
            $params[$name] = $this->$name;
        }
      }
      if ($cadena != "") {
        $cadena = substr($cadena, 0, -2);
        $sql = "UPDATE tblEnvio SET $cadena WHERE idEnvio = :idEnvio";
        $con = Database::getInstace();
        $stmt = $con->prepare($sql);
        $params['idEnvio'] = $this->idEnvio;
        $res = $stmt->execute($params);
        return $res;
      }
    } catch (\Throwable $th) {
      print_r($th);
    }
    return 0;
  }

  public function delete() {
    try {
      $sql = "DELETE FROM tblEnvio WHERE idEnvio = :idEnvio";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $params = ['idEnvio' => $this->idEnvio];
      $res = $stmt->execute($params);
      if ($res) {
        return 10;
      }
    } catch (\Throwable $th) {
      //throw $th;
      print_r($th);
    }
    return -1;
  }
  public function objectNull() {
    $this->idEnvio = 0;
    $this->codigo = "";
    $this->id_usuario_envio = 0;
    $this->usuario_envio = "";
    $this->id_usuario_recibe = 0;
    $this->usuario_recibe = "";
    $this->estado = "";
    $this->detalle_envio = "";
    $this->fecha_envio = "";
    $this->fecha_llegada = "";
    $this->observacion_llegada = "";
    $this->fecha_estimada = "";
    $this->nombre_origen = "";
    $this->ci_origen = "";
    $this->celular_origen = "";
    $this->id_lugar_origen = 0;
    $this->origen = "";
    $this->nombre_destino = "";
    $this->ci_destino = "";
    $this->id_lugar_destino = 0;
    $this->celular_destino = "";
    $this->destino = "";
    $this->fecha_entrega = "";
    $this->id_usuario_entrega = 0;
    $this->capturas = '';
    $this->costo = 0.0;
    $this->observacion_envio = '';
    $this->peso = 0.0;
    $this->cantidad = 0;
    $this->pagado = '';
    $this->saldado = 0;
  }
  public function load($row) {
    foreach ($this as $nombre => $valor) {
      if (isset($row[$nombre])) {
        $this->$nombre = $row[$nombre];
      }
    }
  }
  public function genCode() {
    return strtoupper(substr(uniqid(), -6));
  }

  public function saveImages($files) {
    $dominio = Accesos::dominio();
    if ($dominio) {
      $carpeta = '../public/' . $dominio;
      if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
      }
      $cadNombreCaps = '';
      for ($i = 1; $i <= 3; $i++) { // solo se aceptan 3 capturas
        if (isset($files['captura_' . $i])) {
          $nombre = 'captura_' . $this->idEnvio . $this->codigo . '_' . $i . '.png';
          if (!is_dir($carpeta . '/capturas')) {
            mkdir($carpeta . '/capturas', 0777, true);
          }
          // guardar imagen de base64
          list($tipo, $data) = explode(';', $files['captura_' . $i]);
          list(, $data) = explode(',', $data);
          $image_data = base64_decode($data);
          file_put_contents($carpeta . '/capturas/' . $nombre, $image_data);
          // concatenamos nombre del archivo
          $cadNombreCaps .= $nombre . '|';
        }
      }
      if ($cadNombreCaps != '') {
        $cadNombreCaps = substr($cadNombreCaps, 0, -1);
        $clon = clone $this;
        $this->capturas = $cadNombreCaps;
        if ($this->update($clon)) {
          return 1;
        }
        return -1;
      } else {
        return 1;
      }
    } else { // no existe sesion
      return -1;
    }
  }

  public function existeImagen($nombre_img) {
    $dominio = Accesos::dominio();
    if ($dominio) {
      $carpeta = '../public/' . $dominio;
      if (file_exists($carpeta . '/capturas/' . $nombre_img)) {
        return $carpeta . '/capturas/' . $nombre_img;
      } else {
        return '';
      }
    } else { // no existe sesion
      return '';
    }
  }

  public static function getRecibir($idDestino, $estado = null) {
    try {
      $sql = self::$sql_recibo . " AND e.id_lugar_destino = $idDestino";
      if ($estado != null) {
        $sql .= " AND e.estado = '$estado'";
      }
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
      print_r($th);
    }
    return [];
  }
  public static function getEntregados($idUsuario) {
    try {
      $sql = self::$sql . " WHERE e.id_usuario_entrega = $idUsuario";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
  public static function get_mis_envios($idUsuario, $rol) {
    try {
      $sql = self::$sql;
      $sql .= $rol != 'ADMIN' ? " WHERE e.id_usuario_envio = $idUsuario" : '';
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute();
      $rows = $stmt->fetchAll();
      return $rows;
    } catch (\Throwable $th) {
      //throw $th;
    }
    return [];
  }
  /**
   * Envio con sonsulta externa su propia base de datos
   * @param \PDO $con
   * @param int $idEnvio
   * @return array
   */
  public static function getEnvioExterno($con, $idEnvio) {
    $response = [];
    try {
      $consulta = self::$sql . " WHERE e.idEnvio = $idEnvio";
      $stmt = $con->prepare($consulta);
      $stmt->execute();
      $response = $stmt->fetch();
    } catch (\Throwable $th) {
      //throw $th;
      print_r($th);
    }
    return $response;
  }
}
