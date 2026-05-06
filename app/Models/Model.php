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

    protected string $select = "*";
    protected string $where;
    protected array $values = [];

    protected string $orderBy = "";

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

        // ESTABLECER UTF-8 AQUÍ
        $this->connection->set_charset("utf8mb4"); // utf8mb4 es recomendado sobre utf8
    }

    public function query(string $sql, array $data = [], ?string $params = null)
    {
        if ($data) {

            if ($params == null) {
                $params = str_repeat('s', count($data));
            }

            $stmt = $this->connection->prepare($sql);
            // echo "SQL: " . $sql;
            // echo "Params Count: " . count($data);
            $stmt->bind_param($params, ...$data);
            $stmt->execute();

            $this->query = $stmt->get_result();
        } else {
            $this->query = $this->connection->query($sql);
        }

        return $this;
    }

    public function select(...$columns)
    {
        $this->select = implode(', ', $columns);

        return $this;
    }

    public function orderBy(string $column, $order = 'ASC')
    {
        if (empty($this->orderBy)) {
            $this->orderBy = "{$column} {$order}";
        } else {
            $this->orderBy .= ", {$column} {$order}";
        }

        return $this;
    }

    public function first()
    {
        if (empty($this->query)) {

            $sql = "SELECT {$this->select} FROM {$this->table}";

            if (isset($this->where) && !empty($this->where)) {
                $sql .= " WHERE {$this->where}";
            }

            if ($this->orderBy) {
                $sql .= " ORDER BY {$this->orderBy}";
            }

            // Para depurar
            // return $sql;

            $this->query($sql, $this->values);
        }

        return $this->query->fetch_assoc();
    }

    public function get()
    {
        if (empty($this->query)) {

            $sql = "SELECT {$this->select} FROM {$this->table}";

            if (isset($this->where) && !empty($this->where)) {
                $sql .= " WHERE {$this->where}";
            }

            if ($this->orderBy) {
                $sql .= " ORDER BY {$this->orderBy}";
            }

            // Para depurar
            // return $sql;

            $this->query($sql, $this->values);
        }

        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function paginate($cant = 15)
    {
        $page = $_GET['page'] ?? 1;

        if (empty($this->query)) {

            $sql = "SELECT {$this->select} FROM {$this->table}";

            if (isset($this->where) && !empty($this->where)) {
                $sql .= " WHERE {$this->where}";
            }

            if ($this->orderBy) {
                $sql .= " ORDER BY {$this->orderBy}";
            }

            $sql .= " LIMIT " . ($page - 1) * $cant . ",{$cant}";

            // Para depurar
            // return $sql;

            $data = $this->query($sql, $this->values)->get();
        }


        $total = $this->query("SELECT FOUND_ROWS() as total")->first()['total'];

        // Limpiar la URI
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = str_replace(['\\', '/public'], ['/', ''], dirname($_SERVER['SCRIPT_NAME']));

        $uri = trim(str_replace($basePath, '', $uri), '/');

        if (strpos($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        $last_page = ceil($total / $cant);

        return [
            'total' => $total,
            'from' => ($page - 1) * $cant + 1,
            'to' => ($page - 1) * $cant + count($data),
            'current_page' => $page,
            'last_page' => $last_page,
            'next_page_url' => $page < $last_page ? "/" . $uri . '?page=' . $page + 1 : "/" . $uri . '?page=' . $last_page,
            'prev_page_url' => $page > 1 ? "/" . $uri . '?page=' . $page - 1 : "/" . $uri . '?page=1',
            'data' => $data,
        ];
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

        if (isset($this->where) && !empty($this->where)) {
            $this->where .= " AND {$column} {$operator} ?";
        } else {
            $this->where = "{$column} {$operator} ?";
        }

        $this->values[] = $value;

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
