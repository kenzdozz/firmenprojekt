<?php
namespace DB;

class Model
{
  private $table = '';
  private $db;

  public function __construct(String $table) {
    $this->table = $table;
    try{
      $this->db = new \PDO('mysql:host='.$_ENV['DB_HOST'].';port='.$_ENV['DB_PORT'].';dbname='.$_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
      $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    } catch (\PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
  }

  public function create(Array $data)
  {
    $columns = array_keys($data);
    $valHolder = " VALUES (";
    $query = "INSERT INTO {$this->table} (";
    foreach($columns as $column) {
      $query .= $column;
      $valHolder .= "?";
      if ($column != $columns[count($columns) - 1]) {
        $query .= ", ";
        $valHolder .= ", ";
      }
    }
    $query .= ")" .$valHolder .")";
    $this->query($query, array_values($data), false);
    $inserted = $this->query("SELECT * FROM {$this->table} WHERE id = LAST_INSERT_ID()");
    return $inserted[0];
  }

  public function update(Int $id, Array $data){
    $columns = array_keys($data);
    $query = "UPDATE {$this->table} SET ";
    foreach($columns as $column) {
      $query .= "{$column} = ?";
      if ($column != $columns[count($columns) - 1]) {
        $query .= ", ";
      }
    }
    $query .= " WHERE id = ?";
    $values = array_values($data);
    $values[] = $id;
    $this->query($query, $values);
    return $this->find($id);
  }

  public function delete(Int $id){
    $query = "DELETE FROM {$this->table} WHERE id = ?";
    return $this->query($query, array($id), false);
  }

  public function find(Int $id){
    $query = "SELECT * FROM {$this->table} WHERE id = ?";
    $items = $this->query($query, array($id));
    return $items[0];
  }

  public function findAll(Array $where = null){
    $query = "SELECT * FROM {$this->table}";
    $values = array();
    if ($where) {
      $conditions = is_array($where[0]) ? $where : [$where];
      $query .= " WHERE";
      foreach ($conditions as $key => $condition) {
        if (!is_array($condition) || count($condition) !== 3) {
          throw ('where param must be array containing column operator and value');
        }
        $column = $condition[0];
        $opr = $condition[1];
        $values[] = $condition[2];
        
        $query .= " {$column} {$opr} ?";
        
        if ($key != count($conditions) - 1) {
          $query .= " AND";
        }
      }
    }
    $query .= " ORDER BY created_at DESC";
    $items = $this->query($query, $values);
    return $items;
  }

  private function query($query, $values = [], $valueReturn = true) {
    try {
      $prepare = $this->db->prepare($query);
      $execute = $prepare->execute($values);
      if (!$valueReturn) return $execute;
      return $prepare->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\Exception $err) {
      print $err;
      return [];
    }
  }
}