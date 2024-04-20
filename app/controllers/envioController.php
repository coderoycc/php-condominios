<?php

namespace App\Controllers;

use App\Models\Envio;

class EnvioController {
  public function create($data, $files) {
    $envio = new Envio();
    $envio->codigo = $envio->genCode();
    $envio->id_usuario_envio = $data['id_usuario_envio'];
    $envio->detalle_envio = $data['detalle_envio'];
    $envio->nombre_origen = $data['nombre_origen'];
    $envio->ci_origen = $data['ci_origen'];
    $envio->celular_origen = $data['celular_origen'];
    $envio->id_lugar_origen = $data['origen'];
    $envio->fecha_envio = $data['fecha_envio'] . 'T' . date('H:i');
    $envio->fecha_estimada = str_replace('T', ' ', $data['fecha_estimada']);
    $envio->nombre_destino = $data['nombre_destino'];
    $envio->ci_destino = $data['ci_destino'];
    $envio->celular_destino = $data['celular_destino'];
    $envio->id_lugar_destino = $data['destino'];
    $envio->costo = $data['costo'];
    $envio->observacion_envio = $data['observaciones'];
    $envio->peso = $data['peso'] == '' ? 0 : $data['peso'];
    $envio->cantidad = $data['cantidad'] == '' ? 0 : $data['cantidad'];
    $envio->pagado = $data['pagado'];
    $envio->saldado = $data['pagado'] == 'PAGADO' ? 1 : 0;
    $res = $envio->insert();
    if ($res > 0) {
      $envio->idEnvio = $res;
      $res = $envio->saveImages($data);
      if ($res > 0) {
        echo json_encode(['status' => 'success', 'mensaje' => 'Envio registrado exitosamente', 'envio' => $envio]);
      } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar las imagenes del envio']);
      }
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar el envio']);
    }
  }

  public function update($data, $files) {
    $envio = new Envio($data['idEnvio']);
    $envioAnt = clone $envio;
    if ($envio->idEnvio) {
      $envio->detalle_envio = $data['detalle_envio'];
      $envio->nombre_origen = $data['nombre_origen'];
      $envio->ci_origen = $data['ci_origen'];
      $envio->celular_origen = $data['celular_origen'];
      $envio->id_lugar_destino = $data['destino'];
      $envio->fecha_envio = $data['fecha_envio'] . 'T' . date('H:i');
      $envio->fecha_estimada = $data['fecha_estimada'];
      $envio->nombre_destino = $data['nombre_destino'];
      $envio->ci_destino = $data['ci_destino'];
      $envio->celular_destino = $data['celular_destino'];
      $envio->costo = $data['costo'];
      $envio->observacion_envio = $data['observaciones'];
      $envio->peso = $data['peso'] == '' ? 0 : $data['peso'];
      $envio->cantidad = $data['cantidad'] == '' ? 0 : $data['cantidad'];
      $envio->pagado = $data['pagado'];
      $envio->saldado = $data['pagado'] == 'PAGADO' ? 1 : 0;
      $res = $envio->update($envioAnt);
      if ($res) {
        echo json_encode(['status' => 'success', 'mensaje' => 'Envio actualizado exitosamente', 'envio' => $envio]);
      } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar el envio']);
      }
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Envio no encontrado']);
    }
  }
  public function delete($data) {
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      if ($envio->idEnvio) {
        $res = $envio->delete();
        if ($res > 0) {
          echo json_encode(['status' => 'success', 'message' => 'Envio eliminado']);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el envio']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Envio no encontrado']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Necesario datos id de envio']);
    }
  }

  public function lista_envios_a_recibir($data) {
    if (isset($data['idLugar'])) {
      $estado = $data['estado'] ?? null;
      $envios = Envio::getRecibir($data['idLugar'], $estado);
      echo json_encode(['status' => 'success', 'envios' => $envios]);
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Necesario datos de usuario COOKIES']);
    }
  }
  public function lista_entregados($data) {
    if (isset($data['idUsuario'])) {
      $envios = Envio::getEntregados($data['idUsuario']);
      echo json_encode(['status' => 'success', 'envios' => $envios]);
    } else {
      echo json_encode(['status' => 'error', 'mensaje' => 'Necesario datos de usuario COOKIES']);
    }
  }
  public function envio($data) {
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      if ($envio->idEnvio) {
        echo json_encode(['status' => 'success', 'envio' => $envio]);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Envio no encontrado']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Necesario datos id de envio']);
    }
  }
  public function registra_llegada($data) {
    date_default_timezone_set('America/La_Paz');
    $user = json_decode($_COOKIE['user_obj']);
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      $anterior = clone $envio;
      if ($envio->idEnvio) {
        $envio->estado = "EN ALMACEN";
        $envio->fecha_llegada = date('Y-m-d H:i:s');
        $envio->id_usuario_recibe = $user->idUsuario;
        $res = $envio->update($anterior);
        if ($res > 0) {
          echo json_encode(['status' => 'success', 'message' => 'Envio registrado como recibido']);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al registrar el envio']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Envio no encontrado']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Necesario datos id de envio']);
    }
  }
  public function registra_entrega($data) {
    $user = json_decode($_COOKIE['user_obj']);
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      $anterior = clone $envio;
      if ($envio->idEnvio) {
        $envio->estado = "ENTREGADO";
        $envio->fecha_entrega = date('Y-m-d H:i:s');
        $envio->id_usuario_entrega = $user->idUsuario;
        $envio->observacion_llegada = $data['obs'];
        $res = $envio->update($anterior);
        if ($res > 0) {
          echo json_encode(['status' => 'success', 'message' => 'Envio registrado como entregado']);
        } else {
          echo json_encode(['status' => 'error', 'message' => 'Error al registrar el envio']);
        }
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Envio no encontrado']);
      }
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Necesario datos id de envio']);
    }
  }
  public function obtenercapturas($data) {
    $html = '';
    if (isset($data['idEnvio'])) {
      $envio = new Envio($data['idEnvio']);
      if ($envio->idEnvio) {
        $caps = $envio->capturas ?? '';
        if ($caps != '') {
          $arrCaps = explode('|', $caps);
          $html = '
          <div id="carrusel_caps" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">';
          $htmlInidicators = '';
          $htmlItems = '';
          $count = 0;
          foreach ($arrCaps as $cap) {
            $rutaCompleta = $envio->existeImagen($cap);
            if ($rutaCompleta != '') {
              $htmlInidicators .= $count == 0 ?
                '<button type="button" data-bs-target="#carrusel_caps" data-bs-slide-to="' . $count . '" class="active" aria-current="true" aria-label="Slide ' . ($count + 1) . '"></button>' :
                '<button type="button" data-bs-target="#carrusel_caps" data-bs-slide-to="' . $count . '" aria-label="Slide ' . ($count + 1) . '"></button>';
              $htmlItems .= $count == 0 ?
                '<div class="carousel-item active">
                  <img src="' . $rutaCompleta . '" class="d-block w-100" alt="Captura envio ' . ($count + 1) . '">
                </div>' :
                '<div class="carousel-item active">
                  <img src="' . $rutaCompleta . '" class="d-block w-100" alt="Captura envio ' . ($count + 1) . '">
                </div>';
              $count++;
            }
          }
          $html .= $htmlInidicators .
            '</div>
            <div class="carousel-inner">' . $htmlItems . '</div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carrusel_caps" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Ant.</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carrusel_caps" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Sig.</span>
            </button>
          </div>';
          if ($count == 0) { // no existen las imagenes en servidor pero si en la BD
            $html = '<div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">No hay capturas</h4>
              <p>Las capturas no existen en el servidor</p>
            </div>';
          }
        } else {
          $html = '<div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">No hay capturas</h4>
            <p>Al parecer el envio no tiene capturas.</p>
          </div>';
        }
      } else {
        $html = '<div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Ocurrió un error</h4>
            <p>Al parecer el envio no existe, vuelva a intentarlo.</p>
          </div>';
      }
    } else {
      $html = '<div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Ocurrió un error (No ID envio)</h4>
            <p>Al parecer el envio no existe, vuelva a intentarlo.</p>
          </div>';
    }
    echo $html;
  }
}
