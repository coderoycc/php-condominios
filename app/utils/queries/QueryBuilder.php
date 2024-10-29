<?php

namespace App\Utils\Queries;

use App\Config\Database;
use Exception;
use PDO;

/**
 * Class QueryBuilder hace consultas a las bases de datos usando todas las bases de datos registradas en la base de datos master
 * @package App\Utils\Queries
 */
class QueryBuilder {
  private $con;
  private array $db_names;
  private string $main_table;
  private string $main_alias;

  /**
   * Array de tablas a unir con formato [['tableName' => 'value', 'alias' => 'aliasValue', 'on' => '', 'type' => 'LEFT|RIGHT|FULL'],...] 
   * @var array 
   */
  private array $joins = [];
  private string $wheres = '';
  private string $orderBy = '';
  public function __construct() {
    $this->con = Database::master_instance();
    $sql = "SELECT idCondominio, name, pin, dbname FROM [condominios_master].[dbo].[tblCondominiosData]";
    $stmt = $this->con->prepare($sql);
    $stmt->execute();
    $this->db_names = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($this->db_names) == 0) {
      throw new Exception('No hay selección');
    }
  }
  /**
   * Es la primera consulta que se debe hacer para indicar la tabla primaria
   * @param string $table nombre de la tabla principal
   * @param string $alias alias de la tabla principal
   * @return static
   */
  public function select($table, $alias) {
    $this->main_table = $table;
    $this->main_alias = $alias;
    return $this;
  }
  /**
   * @param string $table nombre de la tabla para unir
   * @param string $alias alias de la tabla 
   * @param string $on ON a.Tabla = c.Tabla2
   * @return static
   */
  public function leftJoin($table, $alias, $on) {
    $this->joins[] = ['tableName' => $table, 'alias' => $alias, 'on' => $on, 'type' => 'LEFT'];
    return $this;
  }
  /**
   * @param string $table nombre de la tabla para unir
   * @param string $alias alias de la tabla
   * @param string $on ON a.Tabla = c.Tabla2
   * @return static
   */
  public function rightJoin($table, $alias, $on) {
    $this->joins[] = ['tableName' => $table, 'alias' => $alias, 'on' => $on, 'type' => 'RIGHT'];
    return $this;
  }
  /**
   * @param string $table nombre de la tabla para unir
   * @param string $alias alias de la tabla
   * @param string $on ON a.Tabla = c.Tabla2
   * @return static
   */
  public function innerJoin($table, $alias, $on) {
    $this->joins[] = ['tableName' => $table, 'alias' => $alias, 'on' => $on, 'type' => 'INNER'];
    return $this;
  }
  /**
   * Cadena completa del where con todos los alias
   * @param string $cad
   * @return static
   */
  public function where($cad) {
    $this->wheres = $cad;
    return $this;
  }
  /**
   * Cadena completa del order by con todos los alias
   * @param string $cad
   * @return static
   */
  public function orderBy($cad) {
    $this->orderBy = $cad;
    return $this;
  }
  /**
   * Ejecuta la consulta y recibe una cadena de todos los selects
   * @param string $select todos los campos que se quieren seleccionar, por defecto todo (*)
   * @return array
   */
  public function get($select = '*') {
    $sqls = $this->get_query($select);
    $sqlunion = join(" UNION ", $sqls);
    // var_dump($sqlunion);
    if ($this->orderBy != '') {
      $sqlunion .= " ORDER BY {$this->orderBy} ";
    }
    $stmt = $this->con->prepare($sqlunion);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Genera las consulta master (todas las bases de datos) a partir de una consulta, reemplaza "[]" con las base de datos, y reemplaza "[*]" como la seleccion master para poner el  nombre de condominio y el pin
   * Devuelve la ejecuccion de la consulta generada
   * @param string $sql consulta custom sql con los caracteres mencionados ([],[*])
   */
  public function get_custom($sql) {
    $matchNameDb = '[]';
    $matchSelectHeader = '[*]';
    $sqls = [];
    foreach ($this->db_names as $datadb) {
      $dbname = $datadb['dbname'];
      $name = $datadb['name'];
      $pin = $datadb['pin'];
      $sqlCustom = str_replace($matchNameDb, "[{$dbname}].[dbo].", $sql);
      $sqlCustom = str_replace($matchSelectHeader, "'{$name}' AS 'Condominio', '{$pin}' AS 'key', ", $sqlCustom);
      $sqls[] = $sqlCustom;
    }
    $sqlunion = join(" UNION ", $sqls);
    $stmt = $this->con->prepare($sqlunion);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * @param string $select
   * @return string[]
   */
  private function get_query($select) {
    $sqls = [];
    foreach ($this->db_names as $datadb) {
      $dbname = $datadb['dbname'];
      $name = $datadb['name'];
      $pin = $datadb['pin'];
      $structure = "SELECT $select, '{$name}' AS 'Condominio', '{$pin}' AS 'key' FROM [{$dbname}].[dbo].[{$this->main_table}] {$this->main_alias} ";
      foreach ($this->joins as $join) {
        $structure .= " {$join['type']} JOIN [{$dbname}].[dbo].[{$join['tableName']}] {$join['alias']} ON {$join['on']} ";
      }
      if ($this->wheres != '') {
        $structure .= " WHERE {$this->wheres} ";
      }
      $sqls[] = $structure;
    }
    return $sqls;
  }
}
