<?php

namespace App\Models;

use mysqli;

class Model
{
    protected string $table;
    protected array $fillable = [];
    protected mysqli $db;

    protected string $db_host = DB_HOST;
    protected string $db_user = DB_USER;
    protected string $db_pass = DB_PASS;
    protected string $db_name = DB_NAME;

    protected mysqli $connection;
    protected mixed $query = null;

    public array $errors      = [];

    public function __construct()
    {
        $this->connection();
    }

    public function connection()
    {
        $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if ($this->connection->connect_error) {
            die('Error de conexión: ' . $this->connection->connect_error);
        }
    }

    public function query(string $sql, array $data = [], ?string $params = null)
    {
        if ($data) {

            if ($params == null) {
                $params = str_repeat('s', count($data));
            }

            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param($params, ...$data);
            $stmt->execute();

            $this->query = $stmt->get_result();
        } else {
            $this->query = $this->connection->query($sql);
        }

        return $this;
    }

    public function first()
    {
        return $this->query->fetch_assoc();
    }

    public function get()
    {
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    // Consultas
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }

    public function find(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->query($sql, [$id], 'i')->first();
    }

    public function where(string $column, string $operator, string|int|null $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = "=";
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";

        $this->query($sql, [$value]);

        return $this;
    }

    public function exists(string $column, string $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = '{$value}'";
        $result = $this->query($sql)->get();
        return count($result) > 0;
    }

    public function create(array $data)
    {
        // Remove unwanted data
        if (!empty($this->fillable)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->fillable)) {
                    unset($data[$key]);
                }
            }
        }

        $columns = array_keys($data);
        $columns = implode(', ', $columns);

        $values = array_values($data);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES (" . str_repeat('?, ', count($values) - 1) . "?)";

        $this->query($sql, $values);

        $insert_id = $this->connection->insert_id;

        return $this->find($insert_id);
    }

    public function update(int $id, array $data)
    {
        $fields = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $fields[] = "{$key} = ?";
            }
        }

        $fields = implode(', ', $fields);

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";

        $values = array_values($data);
        $values[] = $id;

        $this->query($sql, $values);

        return $this->find($id);
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";

        $this->query($sql, [$id], 'i');
    }
}
