<?php
include_once 'models/DB.php';

class Model {
    protected DB $db;
    public string $table = '';
    public string $primary_key = 'id';
    public string $columns = '';

    function __construct() {
        $this->db = new DB();
        $this->db->connect();

        $columns = $this->to_array($this->columns);
        foreach ($columns as $column) {
            $this->{$column} = '';
        }
    }

    protected static function to_array(array|string $columns) : array {
        $array_columns = [];
        if (gettype($columns) == 'string') {
            $columns = str_replace(' ', '', $columns);
            $array_columns = explode(',', $columns);
        }
        return $array_columns;
    }

    public function find(int $value) {
        $rows = $this->db->query("SELECT * FROM $this->table WHERE $this->primary_key = $value");
        $row = $rows->fetch_assoc();

        $columns = $this->to_array($this->columns);
        if ($row) {
            foreach ($columns as $column) {
                $this->{$column} = $row[$column];
            }
        }
    }

    public function insert() : int {
        $columns = $this->to_array($this->columns);
        $values = [];
        foreach ($columns as $column)
            array_push($this->{$column});
        return $this->db->insert(
            $this->table,
            $this->columns,
            $values
        );
    }

    public function update() {
        $columns = $this->to_array($this->columns);
        $values = [];
        foreach ($columns as $column)
            array_push($values, $this->{$column});
        $primary_key_value = $this->{$this->primary_key};
        $this->db->update(
            $this->table,
            $this->columns,
            $values,
            "$this->primary_key = '$primary_key_value'"
        );
    }

    public function delete() {
        $value = $this->{$this->primary_key};
        $this->db->delete($this->table, "$this->primary_key = '$value'");
    }

    public function all(string $select = '*') : mysqli_result {
        $command = "SELECT $select FROM $this->table";
        return $this->db->query($command);
    }

    public function index(string $select = '*', string $join = '', string $where = '', int $page = 1, int $row_per_page = 10) : mysqli_result {
        $offset = ($page-1) * $row_per_page;
        $join_string = empty($join) ? '' : "JOIN $join";
        $where_string = empty($where) ? '' : "WHERE $where";
        $command = "SELECT $select FROM $this->table $join_string $where_string LIMIT $row_per_page OFFSET $offset";
        return $this->db->query($command);
    }

    public function show(string $select = '*', string $join = '', string $where = '') : array {
        $where_and_string = empty($where) ? '' : "AND $where";
        $where_string = "$this->primary_key = '" . $this->{$this->primary_key} . "' $where_and_string";
        return $this->index($select, $join, $where_string)->fetch_assoc();
    }
}