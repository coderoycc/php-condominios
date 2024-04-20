<?php

namespace App\Models;

use App\Config\Database;

class Pago {
  public int $idPago;
  public int $idProyecto;
  public string $concepto;
  public float $monto;
  public string $modoPago;
  public int $idPagadoPor; // usuario
  public int $idRecibidoPor; // afiliado
  public string $fechaRegistro;
  public string $nameFile;
  public $nroNotaFact;
  public string $lugar;
  public string $referencia;  // ingreso (idVenta)
  public string $adelanto; // ADELANTO || PAGO
  public function __construct($idPago = null) {
    $con = Database::getInstace();
    if ($idPago != null) {
      $sql = "SELECT * FROM tblPago WHERE idPago = $idPago";
      $stmt = $con->prepare($sql);
      $stmt->execute([]);
      $pagoRow = $stmt->fetch(); // Utiliza fetch en lugar de fetchAll
      if ($pagoRow) {
        $this->idPago = $pagoRow['idPago'];
        $this->idProyecto = $pagoRow['idProyecto'];
        $this->concepto = $pagoRow['concepto'];
        $this->monto = $pagoRow['monto'];
        $this->modoPago = $pagoRow['modoPago'];
        $this->idPagadoPor = $pagoRow['idPagadoPor'];
        $this->idRecibidoPor = $pagoRow['idRecibidoPor'];
        $this->fechaRegistro = $pagoRow['fechaRegistro'];
        $this->nameFile = $pagoRow['namefile'];
        $this->nroNotaFact = $pagoRow['nroNotaFact'];
        $this->lugar = $pagoRow['lugar'] ?? '';
        $this->referencia = $pagoRow['referencia'] ?? '';
        $this->adelanto = $pagoRow['adelanto'] ?? 'PAGO';
      } else {
        $this->objectNull();
      }
    } else {
      $this->objectNull();
    }
  }
  public function objectNull() {
    $this->idPago = 0;
    $this->monto = 0.0;
    $this->idProyecto = 0;
    $this->concepto = '';
    $this->modoPago = '';
    $this->idPagadoPor = 0;
    $this->idRecibidoPor = 0;
    $this->fechaRegistro = '';
    $this->nameFile = '';
    $this->nroNotaFact = '';
    $this->lugar = '';
    $this->referencia = '';
    $this->adelanto = '';
  }
  public function save() {
    $res = -1;
    try {
      $con = Database::getInstace();
      if ($this->idPago == 0) { //insert
        $sql = "INSERT INTO tblPago(concepto, monto, idProyecto, modoPago, idPagadoPor, idRecibidoPor, namefile, nroNotaFact, lugar, referencia, adelanto) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $params = [$this->concepto, $this->monto, $this->idProyecto, $this->modoPago, $this->idPagadoPor, $this->idRecibidoPor, $this->nameFile, $this->nroNotaFact, $this->lugar, $this->referencia, $this->adelanto];
        $stmt = $con->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
          $this->idPago = $con->lastInsertId();
          $res = $this->idPago;
        }
      } else { // update
        $sql = "UPDATE tblPago SET idProyecto = ?, concepto = ?, modoPago = ?, idPagadoPor = ?, idRecibidoPor = ?, monto = ?, namefile = ?, nroNotaFact = ?, lugar = ?, referencia = ?, adelanto = ? WHERE idPago = ?";
        $params = [$this->idProyecto, $this->concepto, $this->modoPago, $this->idPagadoPor, $this->idRecibidoPor, $this->monto, $this->nameFile, $this->nroNotaFact, $this->lugar, $this->referencia, $this->adelanto, $this->idPago];
        $stmt = $con->prepare($sql);
        $stmt->execute($params);
        $res = 1;
      }
    } catch (\Throwable $th) {
      print_r($th);
    }
    return $res;
  }
  public function delete() {
    $res = -1;
    try {
      $con = Database::getInstace();
      $sql = "DELETE FROM tblPago WHERE idPago = ?";
      $stmt = $con->prepare($sql);
      $stmt->execute([$this->idPago]);
      $res = 1;
    } catch (\Throwable $th) {
      print_r($th);
      $res = -1;
    }
    return $res;
  }
  public function saveFile($tipo, $files, $image64) {
    $nameFile = '';
    if ($tipo == '' || (!isset($files['audio']) && !isset($image64['imagen']))) {
      return $nameFile;
    } else {
      try {
        $domain = 'domain';
        $path = dirname(dirname(__DIR__));
        if (!is_dir($path . "/public/$domain")) {
          mkdir($path . "/public/$domain", 0777);
        }
        if ($tipo == 'audio') {
          $nameFile = $tipo . '_' . time() . '.webm';
          $tmp_name = $files['audio']['tmp_name'];
          move_uploaded_file($tmp_name, "$path/public/$domain/$nameFile");
          return $nameFile;
        } else if ($tipo == 'imagen') {
          $nameFile = $tipo . '_' . time() . '.jpg';
          $imageData = file_get_contents($image64['imagen']);
          $image = imagecreatefromstring($imageData);
          imagejpeg($image, "$path/public/$domain/$nameFile");
        }
        return $nameFile;
      } catch (\Throwable $th) {
        print_r($th);
        return null;
      }
    }
  }
  public function pagadoPorEgreso() {
    try {
      $con = Database::getInstace();
      $sql = "SELECT * FROM tblUsuario WHERE idUsuario = ?";
      $stmt = $con->prepare($sql);
      $stmt->execute([$this->idPagadoPor]);
      $res = $stmt->fetch();
      return $res;
    } catch (\Throwable $th) {
      return null;
    }
  }

  public function recibidoPorEgreso() {
    try {
      $con = Database::getInstace();
      $sql = "SELECT * FROM tblAfiliado WHERE idAfiliado = ?";
      $stmt = $con->prepare($sql);
      $stmt->execute([$this->idRecibidoPor]);
      $res = $stmt->fetch();
      return $res;
    } catch (\Throwable $th) {
      return null;
    }
  }
  public function pagadoPorIngreso() {
    try {
      $con = Database::getInstace();
      $sql = "SELECT * FROM tblAfiliado WHERE idAfiliado = ?";
      $stmt = $con->prepare($sql);
      $stmt->execute([$this->idPagadoPor]);
      $res = $stmt->fetch();
      return $res;
    } catch (\Throwable $th) {
      return null;
    }
  }

  public function recibidoPorIngreso() {
    try {
      $con = Database::getInstace();
      $sql = "SELECT * FROM tblUsuario WHERE idUsuario = ?";
      $stmt = $con->prepare($sql);
      $stmt->execute([$this->idRecibidoPor]);
      $res = $stmt->fetch();
      return $res;
    } catch (\Throwable $th) {
      return null;
    }
  }

  public static function getByProject($idProyecto) { //Egreso
    try {
      $sql = "SELECT pa.*, UPPER(us.alias) usuario, UPPER(af.nombre) afiliado  FROM tblPago pa INNER JOIN tblAfiliado af ON pa.idRecibidoPor = af.idAfiliado INNER JOIN tblUsuario us ON pa.idPagadoPor = us.idUsuario WHERE idProyecto = ?;";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute([$idProyecto]);
      $res = $stmt->fetchAll();
      return $res;
    } catch (\Throwable $th) {
      //throw $th;
      return [];
    }
  }

  public static function getByProjectIngreso($idProyecto) {
    try {
      $sql = "SELECT pa.*, UPPER(us.alias) usuario, UPPER(af.nombre) afiliado  FROM tblPago pa INNER JOIN tblAfiliado af ON pa.idPagadoPor = af.idAfiliado INNER JOIN tblUsuario us ON pa.idRecibidoPor = us.idUsuario WHERE pa.idProyecto = ?;";
      $con = Database::getInstace();
      $stmt = $con->prepare($sql);
      $stmt->execute([$idProyecto]);
      $res = $stmt->fetchAll();
      return $res;
    } catch (\Throwable $th) {
      //throw $th;
      return [];
    }
  }

  public static function getAll($tipo) {
    try { // obtener los valores por fecha del ultimo mes
      $con = Database::getInstace();
      $hoy = date('Y-m-d', strtotime('+1 days'));
      $mesAnterior = date('Y-m-d', strtotime('-1 month'));
      $sql = "SELECT t.*, UPPER(concat(p.proyecto,' | ',t.concepto)) as detalle FROM tblPago t
      INNER JOIN tblProyecto p ON t.idProyecto = p.idProyecto
      WHERE p.tipo LIKE '$tipo' AND
      t.fechaRegistro between '$mesAnterior' AND '$hoy'";
      $stmt = $con->prepare($sql);
      $stmt->execute([]);
      $res = $stmt->fetchAll();
      return $res;
    } catch (\Throwable $th) {
      print_r($th);
      return [];
    }
  }
}
