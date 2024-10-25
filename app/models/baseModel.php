<?php

namespace App\Models;

use PDO;
use ReflectionClass;

class BaseModel {
  public function objectNull() {
    $claseReflection = new ReflectionClass($this);
    $propiedades = $claseReflection->getProperties();
    foreach ($propiedades as $propiedad) {
      if ($propiedad->getName() == 'con')
        continue;
      $tipoDato = $propiedad->getType()->getName();
      switch ($tipoDato) {
        case 'int':
          $propiedad->setValue($this, 0);
          break;
        case 'string':
          $propiedad->setValue($this, '');
          break;
        case 'array':
          $propiedad->setValue($this, []);
          break;
        case 'object':
          $propiedad->setValue($this, null);
          break;
        case 'float':
          $propiedad->setValue($this, 0);
          break;
        case 'bool':
          $propiedad->setValue($this, false);
          break;
        default:
          $propiedad->setValue($this, null);
      }
    }
  }
  public function load($row) {
    // Los nombres de las propiedades deben coincidir con los nombres de las columnas de la tabla
    foreach ($row as $propiedad => $valor) {
      if ($valor) {
        $this->$propiedad = $valor;
      }
    }
  }
  /**
   * Actualiza un registro a partir del objeto actual y uno que es el objeto original
   * @param PDO $con
   * @param object $original
   * @param string $table Nombre de la tabla a actualizar
   * @param string $id Nombre de la columna id
   * @return int Número de filas afectadas
   */
  public function update($con, $original, $table, $id = 'id') {
    try {
      $claseReflection = new ReflectionClass($this);
      $propiedades = $claseReflection->getProperties();
      $cadena = "UPDATE $table SET ";
      $valores = [];
      $campos = "";
      $propsCambiadas = 'PROPS -- ';
      foreach ($propiedades as $propiedad) {
        if ($propiedad->isPublic()) {
          $nombrePropiedad = $propiedad->getName();
          $valorPropiedad = $this->$nombrePropiedad;
          if ($valorPropiedad != $original->$nombrePropiedad) {
            $propsCambiadas .= '  x->' . $nombrePropiedad;
            $campos .= "$nombrePropiedad = ?,";
            $valores[] = $valorPropiedad;
          }
        }
      }
      $campos = rtrim($campos, ',');
      $cadena .= $campos . " WHERE $id = ?";
      $valores[] = $original->{$id};
      if (count($valores) > 1) {
        $stmt = $con->prepare($cadena);
        // $log = $propsCambiadas . '-->' . json_encode($valores) . '--------------' . $campos . '-------------------' . $cadena;
        // file_put_contents(__DIR__ . '/Log.txt', $log);
        $stmt->execute($valores);
        return $stmt->rowCount();
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return -1;
  }

  /**
   * Inserta los campos que no son nulos a una tabla y pone en el objeto el ID insertado
   * @param PDO $con
   * @param string $table
   * @param string $id nombre del ID de la tabla
   * @return int
   */
  public function insert($con = null, $table = 'table', $id = 'id') {
    try {
      $claseReflection = new ReflectionClass($this);
      $propiedades = $claseReflection->getProperties();
      $cadena = "INSERT INTO $table  ";
      $valores = [];
      $campos = "";
      $camposValores = '';
      foreach ($propiedades as $propiedad) {
        if ($propiedad->isPublic()) {
          $nombrePropiedad = $propiedad->getName();
          if ($nombrePropiedad == 'con')
            continue;
          $valorPropiedad = $this->$nombrePropiedad;
          if ($valorPropiedad != '' && $valorPropiedad != null) {
            $campos .= "$nombrePropiedad,";
            $valores[] = $valorPropiedad;
            $camposValores .= '?,';
          }
        }
      }
      $campos = rtrim($campos, ',');
      $camposValores = rtrim($camposValores, ', ');
      $cadena .= "($campos) VALUES ($camposValores);";

      $stmt = $con->prepare($cadena);
      $stmt->execute($valores);
      $this->{$id} = $con->lastInsertId();
      return $this->{$id};
    } catch (\Throwable $th) {
      var_dump($th);
    }
    return -1;
  }
  /**
   * Carga los datos de un array al objeto
   * @param array $arr_data datos con las mismas keys que en la base de datos
   * @return void
   */
  public function set_data($arr_data = []) {
    try {
      $claseReflection = new ReflectionClass($this);
      $propiedades = $claseReflection->getProperties();
      foreach ($propiedades as $propiedad) {
        $nombrePropiedad = $propiedad->getName();
        if (isset($arr_data[$nombrePropiedad])) {
          if ($propiedad->isPublic()) { // solo si es publico
            $this->$nombrePropiedad = $arr_data[$nombrePropiedad];
          }
        }
      }
    } catch (\Throwable $th) {
      var_dump($th);
    }
  }
}
