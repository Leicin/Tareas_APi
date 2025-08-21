<?php


class Task
{
    private $conn;
    private $table_name = "task";

    public $id;
    public $titulo;
    public $descripcion;
    public $estado;


    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->ObtenerConexion(); // ✅ Llama al método
    }

    public function creartareamodelo()
    {
        $query = "INSERT INTO " . $this->table_name . " (titulo, descripcion, estado) 
                  VALUES (:titulo, :descripcion, :estado)";

        $stmt = $this->conn->prepare($query);  // ✅ aquí usamos $this->conn, no $this

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function leertareas()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function obtenerPorId()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function actualizarmodelo()
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET titulo = :titulo, descripcion = :descripcion, estado = :estado 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function eliminartareamodelo()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
