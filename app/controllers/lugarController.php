<?php

namespace App\Controllers;

use App\Models\Lugar;

class LugarController {
  public function create($data, $files = null) {
    $lugar = new Lugar();
    $lugar->lugar = $data['lugar'];
    $res = $lugar->save();
    if ($res > 0) {
      echo json_encode(['status' => 'success', 'message' => 'Lugar creado correctamente']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Error al crear el lugar']);
    }
  }

  public function delete($data) {
    $lugar = new Lugar($data['idLugar']);
    if ($lugar->idLugar == 0) {
      echo json_encode(['status' => 'error', 'message' => 'El lugar no existe']);
    } else {
      if ($lugar->delete()) {
        echo json_encode(['status' => 'success', 'message' => 'Lugar eliminado correctamente']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el lugar']);
      }
    }
  }

  public function update($data) {
    $lugar = new Lugar($data['idLugar']);
    if ($lugar->idLugar == 0) {
      echo json_encode(['status' => 'error', 'message' => 'El lugar no existe']);
    } else {
      $lugar->lugar = $data['lugar'];
      if ($lugar->save() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Lugar actualizado correctamente']);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el lugar']);
      }
    }
  }
  public function getAll() {
    $lugares = Lugar::all();
    echo json_encode($lugares);
  }
}
