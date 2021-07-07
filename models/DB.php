<?php
class DB {
    private mysqli $mysqli;

    public function connect() {
        $this->mysqli = mysqli_connect('localhost', 'root', '', 'phonthavy');
    }

    public function to_array(array|string $values) : array {
        if (gettype($values) == 'string') {
            $values_arr =  explode(',', $values);
            $return_val = [];
            foreach ($values_arr as $value)
            array_push($return_val, trim($value));
            return $return_val;
        }
        return $values;
    }

    protected function to_string(array|string $values) : string {
        if (gettype($values) == 'array') {
            return implode(', ', $values);
        }
        return $values;
    }

    protected function format(array|string $values) : array|string {
        $values = $this->to_array($values);
        $return_val = [];
        foreach ($values as $value)
            array_push($return_val, "'" . $value . "'");
        if (gettype($values) == 'string')
            return $this->to_string($return_val);
        return $return_val;
    }

    public function insert(string $table, array|string $columns, array|string $values) : int {
        $values = $this->to_string($this->format($values));
        $command = "INSERT INTO $table ($columns) VALUES ($values)";
        $this->mysqli->query($command);
        return $this->mysqli->insert_id;
    }

    public function update(string $table, array|string $columns, array|string $values, string $where = '') {
        $columns = $this->to_array($columns);
        $values = $this->to_array($this->format($values));
        $command = "UPDATE $table SET ";
        for ($index = 0; $index < count($columns); $index++) {
            $column = $columns[$index];
            $value = $values[$index];
            $command .= "$column = $value";
            if ($index < count($columns) -1)
                $command .= ', ';
            else
                $command .= ' ';
        }
        if ($where != '')
            $command .= "WHERE $where";
        $this->mysqli->query($command);
        $this->mysqli->commit();
    }

    public function delete($table, $where) {
        $command = "DELETE FROM $table WHERE $where";
        $this->mysqli->query($command);
        $this->mysqli->commit();
    }

    public function query($command) : mysqli_result {
        return $this->mysqli->query($command);
    }
}