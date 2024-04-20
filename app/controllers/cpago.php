<?php

namespace App\Controllers;

use App\Models\Pago;

class CPago {
  public function createEgreso($data, $files = null) {
    if (!isset($_COOKIE['user_obj'])) {
      echo json_encode(['status' => 'error', 'message' => 'Necesario cookies de sesion']);
    } else {
      $user = json_decode($_COOKIE['user_obj']);
      $pago = new Pago();
      // guardamos los archivos enviados, si es que existen.
      $tipo = isset($data['tipo_file']) ? $data['tipo_file'] : '';
      $file = $pago->saveFile($tipo, $files, $data);
      if ($file != null || $file == '') {
        $pago->concepto = $data['concepto'];
        $pago->monto = $data['monto'];
        $pago->idProyecto = $data['idProyecto'];
        $pago->idRecibidoPor = $data['idAfiliado'];
        $pago->modoPago = $data['modoPago'];
        $pago->nameFile = $file;
        $pago->idPagadoPor = $user->idUsuario;
        $pago->nroNotaFact = isset($data['nro']) ? $data['nro'] : '';
        $pago->lugar = isset($data['lugar']) ? $data['lugar'] : '';
        $pago->referencia = isset($data['referencia']) ? $data['referencia'] : '';
        $pago->adelanto = $data['adelanto'] ?? 'PAGO';
        $res = $pago->save();
        if ($res) {
          $pago->idPago = $res;
          echo json_encode(['status' => 'success', 'message' => 'Pago creado', 'pago' => json_encode($pago)]);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al crear afiliado']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'No se ha podido crear el archivo del comprobante']);
      }
    }
  }

  public function createIngreso($data, $files = null) {
    if (!isset($_COOKIE['user_obj'])) {
      echo json_encode(['status' => 'error', 'message' => 'Necesario cookies de sesion']);
    } else {
      $user = json_decode($_COOKIE['user_obj']);
      $pago = new Pago();
      // guardamos los archivos enviados, si es que existen.
      $tipo = isset($data['tipo_file']) ? $data['tipo_file'] : '';
      $file = $pago->saveFile($tipo, $files, $data);
      if ($file != null || $file == '') {
        $pago->concepto = $data['concepto'];
        $pago->monto = $data['monto'];
        $pago->idProyecto = $data['idProyecto'];
        $pago->idPagadoPor = $data['idAfiliado'];
        $pago->modoPago = $data['modoPago'];
        $pago->nameFile = $file;
        $pago->idRecibidoPor = $user->idUsuario;
        $pago->nroNotaFact = isset($data['nro']) ? $data['nro'] : '';
        $pago->lugar = isset($data['lugar']) ? $data['lugar'] : '';
        $pago->referencia = isset($data['referencia']) ? $data['referencia'] : '';
        $pago->adelanto = $data['adelanto'] ?? 'PAGO';
        $res = $pago->save();
        if ($res) {
          $pago->idPago = $res;
          echo json_encode(['status' => 'success', 'message' => 'Pago creado', 'pago' => json_encode($pago)]);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al crear afiliado']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'No se ha podido crear el archivo del comprobante']);
      }
    }
  }

  public function getXtipo($data) {
    try {
      $datos = Pago::getAll($data['tipo']);
      echo json_encode(['status' => 'success', 'data' => json_encode($datos)]);
    } catch (\Throwable $th) {
      // print_r($th);
      echo json_encode(['status' => 'error', 'message' => 'Error al obtener datos']);
    }
  }
  public function deletefile($data) {
    $domain = 'domain';
    try {
      $pago = new Pago($data['idPago']);
      $url = dirname(dirname(__DIR__));
      $url = $url . '/public/' . $domain . '/' . $pago->nameFile;
      if (unlink($url)) {
        $pago->nameFile = '';
        $res = $pago->save();
        if ($res != -1) {
          echo json_encode(['status' => 'success', 'message' => 'Archivo eliminado']);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Se elimino el archivo pero no se actualizo en nombre de archivo']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el archivo']);
      }
    } catch (\Throwable $th) {
      echo json_encode(['status' => 'error', 'message' => json_encode($th), 'error' => 'Error: ' . $th->getMessage()]);
    }
  }
  public function delete($data) {
    $domain = 'domain';
    $user = json_decode($_COOKIE['user_obj']);
    if ($user->password == hash('sha256', $data['pass'])) {
      try {
        $pago = new Pago($data['idPago']);
        if ($pago->nameFile != '') {
          $url = dirname(dirname(__DIR__));
          $url = $url . '/public/' . $domain . '/' . $pago->nameFile;
          if (file_exists($url)) {
            if (unlink($url)) {
              $res = $pago->delete();
              if ($res > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Pago eliminado']);
              } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al eliminar pago']);
              }
            } else {
              echo json_encode(['status' => 'error', 'message' => 'Error al eliminar pago (file)']);
            }
          } else {
            $res = $pago->delete();
            if ($res > 0) {
              echo json_encode(['status' => 'success', 'message' => 'Pago eliminado']);
            } else {
              echo json_encode(['status' => 'error', 'message' => 'Error al eliminar pago']);
            }
          }
        } else {
          $res = $pago->delete();
          if ($res > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Pago eliminado']);
          } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar pago']);
          }
        }
      } catch (\Throwable $th) {
        echo json_encode(['status' => 'error', 'message' => json_encode($th), 'error' => $th->getMessage()]);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta']);
    }
  }
  public function update($data, $files) { // Egreso 
    try {
      $pago = new Pago($data['idPago']);
      $pago->concepto = $data['concepto'];
      $pago->monto = $data['monto'];
      $pago->idProyecto = $data['idProyecto'];
      $pago->idRecibidoPor = $data['idAfiliado'];
      $pago->modoPago = $data['modoPago'];
      $pago->nroNotaFact = isset($data['nro']) ? $data['nro'] : '';
      $pago->lugar = $data['lugar'] ?? '';
      $pago->referencia = $data['referencia'] ?? '';
      $pago->adelanto = $data['adelanto'] ?? 'PAGO';
      if ($data['tipo_file'] == 'file') { // existe archivo
        $res = $pago->save();
        if ($res) {
          echo json_encode(['status' => 'success', 'message' => 'Pago actualizado', 'pago' => json_encode($pago)]);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al crear pago']);
        }
      } else {
        $tipo = $data['tipo_file'] == '' ? '' : $data['tipo_file'];
        $file = $pago->saveFile($tipo, $files, $data);
        if ($file != null || $file == '') {
          $pago->nameFile = $file;
          $res = $pago->save();
          if ($res) {
            echo json_encode(['status' => 'success', 'message' => 'Pago actualizado', 'pago' => json_encode($pago)]);
          } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear pago']);
          }
        } else {
          echo json_encode(['status' => 'error', 'message' => 'No se ha podido crear el archivo del comprobante']);
        }
      }
    } catch (\Throwable $th) {
      echo json_encode(['status' => 'error', 'message' => json_encode($th)]);
    }
  }

  public function updateIngreso($data, $files) {
    try {
      $pago = new Pago($data['idPago']);
      $pago->concepto = $data['concepto'];
      $pago->monto = $data['monto'];
      $pago->idProyecto = $data['idProyecto'];
      $pago->idPagadoPor = $data['idAfiliado'];
      $pago->modoPago = $data['modoPago'];
      $pago->nroNotaFact = isset($data['nro']) ? $data['nro'] : '';
      $pago->lugar = $data['lugar'] ?? '';
      $pago->referencia = $data['referencia'] ?? '';
      $pago->adelanto = $data['adelanto'] ?? 'PAGO';
      if ($data['tipo_file'] == 'file') { // existe archivo
        $res = $pago->save();
        if ($res) {
          echo json_encode(['status' => 'success', 'message' => 'Pago actualizado', 'pago' => json_encode($pago)]);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al crear pago']);
        }
      } else {
        $tipo = $data['tipo_file'] == '' ? '' : $data['tipo_file'];
        $file = $pago->saveFile($tipo, $files, $data);
        if ($file != null || $file == '') {
          $pago->nameFile = $file;
          $res = $pago->save();
          if ($res) {
            echo json_encode(['status' => 'success', 'message' => 'Pago actualizado', 'pago' => json_encode($pago)]);
          } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear pago']);
          }
        } else {
          echo json_encode(['status' => 'error', 'message' => 'No se ha podido crear el archivo del comprobante']);
        }
      }
    } catch (\Throwable $th) {
      echo json_encode(['status' => 'error', 'message' => json_encode($th)]);
    }
  }
}
