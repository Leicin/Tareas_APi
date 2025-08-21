<?php
class TaskController
{
    private $task;

    public function __construct($db)
    {
        $this->task = new Task($db);
    }

    public function obtenerDatos()
    {
        $stmt = $this->task->leertareas();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks, JSON_UNESCAPED_UNICODE);
    }

    public function creartarea($data)
    {

        try {


            if (empty($data["titulo"]) || empty($data["descripcion"]) || empty($data["estado"])) {
                http_response_code(400);
                echo json_encode([
                    "error" => true,
                    "message" => "Todos los campos (titulo, descripcion, estado) son obligatorios"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->task->titulo = $data["titulo"];
            $this->task->descripcion = $data["descripcion"];
            $this->task->estado = strtolower(trim($data["estado"]));

            $estadosValidos = ["pendiente", "completada"];
            if (!in_array($this->task->estado, $estadosValidos)) {
                http_response_code(400);
                echo json_encode([
                    "error" => true,
                    "message" => "El campo estado solo puede ser 'pendiente' o 'completada'"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            if ($this->task->creartareamodelo()) {
                http_response_code(200);
                echo json_encode([
                    "error" => false,
                    "message" => "Tarea creada correctamente"
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(500);
                echo json_encode([
                    "error" => true,
                    "message" => "Error al crear la tarea"
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => true,
                "message" => "Excepción capturada: " . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function actualizarTareas($id, $data)
    {
        try {

            if (empty($id)) {
                http_response_code(400);
                echo json_encode([
                    "error" => true,
                    "message" => "El ID de la tarea es obligatorio"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }


            if (empty($data)) {
                http_response_code(400);
                echo json_encode([
                    "error" => true,
                    "message" => "Debe enviar al menos un campo para actualizar"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->task->id = $id;
            if (!empty($data["titulo"])) {
                $this->task->titulo = trim($data["titulo"]);
            }
            if (!empty($data["descripcion"])) {
                $this->task->descripcion = trim($data["descripcion"]);
            }
            if (!empty($data["estado"])) {
                $estado = strtolower(trim($data["estado"]));
                $estadosValidos = ["pendiente", "completada"];
                if (!in_array($estado, $estadosValidos)) {
                    http_response_code(400);
                    echo json_encode([
                        "error" => true,
                        "message" => "El campo estado solo puede ser 'pendiente' o 'completada'"
                    ], JSON_UNESCAPED_UNICODE);
                    return;
                }
                $this->task->estado = $estado;
            }


            if ($this->task->actualizarmodelo()) {
                http_response_code(200);
                echo json_encode([
                    "error" => false,
                    "message" => "Tarea actualizada correctamente"
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(500);
                echo json_encode([
                    "error" => true,
                    "message" => "Error al actualizar la tarea"
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => true,
                "message" => "Excepción capturada: " . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }



    public function eliminarTarea($id)
    {
        try {
            if (empty($id)) {
                http_response_code(400);
                echo json_encode([
                    "error" => true,
                    "message" => "El ID de la tarea es obligatorio"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->task->id = $id;


            if ($this->task->eliminartareamodelo()) {
                http_response_code(200);
                echo json_encode([
                    "error" => false,
                    "message" => "Tarea eliminada correctamente"
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    "error" => true,
                    "message" => "No se encontró la tarea o no se pudo eliminar"
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => true,
                "message" => "Excepción capturada: " . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }


    public function obtenerTarea($id)
    {
        try {
            if (empty($id)) {
                http_response_code(400);
                echo json_encode([
                    "error" => true,
                    "message" => "El ID de la tarea es obligatorio"
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $this->task->id = $id;
            $stmt = $this->task->obtenerPorId(); // Llamamos al modelo

            if ($stmt) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    http_response_code(200);
                    echo json_encode([
                        "error" => false,
                        "data" => $row
                    ], JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(404);
                    echo json_encode([
                        "error" => true,
                        "message" => "No se encontró la tarea con ID $id"
                    ], JSON_UNESCAPED_UNICODE);
                }
            } else {
                http_response_code(500);
                echo json_encode([
                    "error" => true,
                    "message" => "Error en la consulta"
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "error" => true,
                "message" => "Excepción capturada: " . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}
