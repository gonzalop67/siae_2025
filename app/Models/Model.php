<?php

namespace App\Models;

use mysqli;

class Model
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected static ?mysqli $sharedConnection = null;
    protected mysqli $connection;
    protected mixed $query = null;
    protected string $select = "*";
    protected string $where = "";
    protected array $values = [];
    protected string $orderBy = "";
    public array $errors = [];

    public function __construct()
    {
        $this->connection();
    }

    public function connection()
    {
        // Desactiva el reporte de errores interno de Mysqli para controlarlo tú mismo
        mysqli_report(MYSQLI_REPORT_OFF);

        // Si la conexión ya fue creada por otro modelo, la reutilizamos
        if (self::$sharedConnection === null) {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($conn->connect_error) {
                die('Error de conexión: ' . $conn->connect_error);
            }
            $conn->set_charset('utf8mb4');
            self::$sharedConnection = $conn;
        }

        // Asignamos la conexión compartida a la propiedad del modelo actual
        $this->connection = self::$sharedConnection;
    }

    public function query(string $sql, array $data = [], ?string $params = null)
    {
        if ($data) {
            if ($params == null) {
                $params = '';
                foreach ($data as $val) {
                    if (is_int($val)) $params .= 'i';
                    elseif (is_double($val)) $params .= 'd';
                    else $params .= 's';
                }
            }

            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                die('Error en la preparación SQL: ' . $this->connection->error . ' | SQL: ' . $sql);
            }

            $stmt->bind_param($params, ...$data);

            // CORRECCIÓN CLAVE: Verificar si la ejecución realmente tuvo éxito
            if (!$stmt->execute()) {
                die('Error al ejecutar la consulta: ' . $stmt->error . ' | Datos: ' . json_encode($data));
            }

            // CONTROL SEGURO DE RESULTADOS
            if ($stmt->field_count > 0) {
                $this->query = $stmt->get_result();
            } else {
                // Para INSERT/UPDATE almacenamos el número de filas afectadas
                $this->query = $stmt->affected_rows;
            }

            // Cerrar el stmt libera el proceso y obliga al motor MySQL a consolidar el INSERT
            $stmt->close();
        } else {
            $this->query = $this->connection->query($sql);
            if (!$this->query) {
                die('Error en consulta directa: ' . $this->connection->error);
            }
        }
        return $this;
    }

    public function select(...$columns)
    {
        // Seguridad básica ante inyecciones en los nombres de columnas
        $sanitized = array_map(fn($col) => trim(str_replace('`', '', $col)), $columns);
        $this->select = implode(', ', $sanitized);
        return $this;
    }

    public function orderBy(string $column, $order = 'ASC')
    {
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $column = trim(str_replace('`', '', $column));

        if (empty($this->orderBy)) {
            $this->orderBy = "{$column} {$order}";
        } else {
            $this->orderBy .= ", {$column} {$order}";
        }
        return $this;
    }

    protected function buildSelectSql(): string
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";
        if (!empty($this->where)) {
            $sql .= " WHERE {$this->where}";
        }
        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY {$this->orderBy}";
        }
        return $sql;
    }

    protected function resetQuery()
    {
        $this->select = "*";
        $this->where = "";
        $this->values = [];
        $this->orderBy = "";
        $this->query = null;
    }

    public function first()
    {
        if (empty($this->query)) {
            $sql = $this->buildSelectSql();
            $this->query($sql, $this->values);
        }

        $result = null;
        if ($this->query instanceof \mysqli_result) {
            $result = $this->query->fetch_assoc();
        }

        $this->resetQuery();
        return $result;
    }

    public function get()
    {
        if (empty($this->query)) {
            $sql = $this->buildSelectSql();
            $this->query($sql, $this->values);
        }

        $result = [];
        if ($this->query instanceof \mysqli_result) {
            $result = $this->query->fetch_all(MYSQLI_ASSOC);
        }

        $this->resetQuery();
        return $result;
    }

    public function paginate($cant = 15)
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        // MEJORA: Reemplazo de SQL_CALC_FOUND_ROWS (Obsoleto en MySQL 8.0+) por un COUNT alternativo.
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        if (!empty($this->where)) {
            $countSql .= " WHERE {$this->where}";
        }

        $countQuery = $this->connection->prepare($countSql);
        if ($this->values) {
            $params = str_repeat('s', count($this->values)); // Ajuste rápido string genérico para el conteo
            $countQuery->bind_param($params, ...$this->values);
        }
        $countQuery->execute();
        $total = $countQuery->get_result()->fetch_assoc()['total'] ?? 0;

        // Ejecutar consulta de datos paginados
        $sql = $this->buildSelectSql();
        $offset = ($page - 1) * $cant;
        $sql .= " LIMIT {$offset}, {$cant}";

        $this->query($sql, $this->values);
        $data = ($this->query instanceof \mysqli_result) ? $this->query->fetch_all(MYSQLI_ASSOC) : [];
        $this->resetQuery();

        // URLs y Enlaces
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = str_replace(['\\', '/public'], ['/', ''], dirname($scriptName));
        $uri = trim(str_replace($basePath, '', $uri), '/');

        $last_page = (int)ceil($total / $cant);
        if ($last_page < 1) $last_page = 1;

        return [
            'total'        => $total,
            'from'         => $total > 0 ? $offset + 1 : 0,
            'to'           => $offset + count($data),
            'current_page' => $page,
            'last_page'    => $last_page,
            'next_page_url' => $page < $last_page ? "/{$uri}?page=" . ($page + 1) : null,
            'prev_page_url' => $page > 1 ? "/{$uri}?page=" . ($page - 1) : null,
            'data'         => $data,
        ];
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }

    public function pluck($value, $key = null)
    {
        $columns = $key ? "{$key}, {$value}" : $value;
        $sql = "SELECT {$columns} FROM {$this->table}";
        $data = $this->query($sql)->get();
        if (empty($data)) return [];
        return is_null($key) ? array_column($data, $value) : array_column($data, $value, $key);
    }

    public function find(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->query($sql, [$id], 'i')->first();
    }

    public function where(string $column, string $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = "=";
        }

        // Seguridad contra manipulación de columnas en el WHERE
        $column = trim(str_replace('`', '', $column));

        if (!empty($this->where)) {
            $this->where .= " AND {$column} {$operator} ?";
        } else {
            $this->where = "{$column} {$operator} ?";
        }
        $this->values[] = $value;
        return $this;
    }

    public function orWhere(string $column, string $operator, $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = "=";
        }

        // Seguridad contra manipulación de nombres de columnas
        $column = trim(str_replace('`', '', $column));

        if (!empty($this->where)) {
            $this->where .= " OR {$column} {$operator} ?";
        } else {
            // Si es la primera condición, actúa como un WHERE normal
            $this->where = "{$column} {$operator} ?";
        }

        $this->values[] = $value;
        return $this;
    }

    public function exists(string $column, $value, $excludeId = null, string $idColumn = 'id'): bool
    {
        // 1. Limpiar y sanitizar el nombre de la columna
        $column = trim(str_replace('`', '', $column));
        $idColumn = trim(str_replace('`', '', $idColumn));

        // 2. Construcción base de la consulta
        $sql = "SELECT 1 FROM {$this->table} WHERE {$column} = ?";
        $params = [$value];

        // 3. Excluir el registro actual si se proporciona un ID (Útil para actualizaciones)
        if ($excludeId !== null) {
            $sql .= " AND {$idColumn} != ?";
            $params[] = $excludeId;
        }

        // 4. Agregar límite para optimizar rendimiento de la base de datos
        $sql .= " LIMIT 1";

        // 5. Ejecutar consulta
        $result = $this->query($sql, $params)->first();
        return !empty($result);
    }

    public function create(array $data)
    {
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        // return array_values($data);

        $this->query($sql, array_values($data));
        return $this->find($this->connection->insert_id);
    }

    public function update(int $id, array $data)
    {
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        $fields = [];
        foreach (array_keys($data) as $key) {
            $fields[] = "{$key} = ?";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = ?";
        $values = array_values($data);
        $values[] = $id;

        $this->query($sql, $values);
        return $this->find($id);
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $this->query($sql, [$id], 'i');
        $this->resetQuery();
    }

    // Inicia la transacción desactivando el autocommit
    public function beginTransaction(): bool
    {
        return $this->connection->begin_transaction();
    }

    // Confirma todos los cambios realizados en la transacción
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    // Revierte todos los cambios si algo falló
    public function rollBack(): bool
    {
        return $this->connection->rollback();
    }
}
