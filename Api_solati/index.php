<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . "/configuracion/conexion.php";
require_once __DIR__ . "/modelo/Tarea.php";
require_once __DIR__ . "/controlador/controlador.php";

// 🔹 Manejar preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$database = new Database();
$db = $database->ObtenerConexion();

$task = new Task($db);
$controller = new TaskController($task);

$method = $_SERVER["REQUEST_METHOD"];
$requestUri = explode("/", trim($_SERVER["REQUEST_URI"], "/"));

$taskIndex = array_search("tasks", $requestUri);
$resource = $taskIndex !== false ? "tasks" : null;
$id = ($taskIndex !== false && isset($requestUri[$taskIndex + 1])) ? $requestUri[$taskIndex + 1] : null;

switch ($method) {
    case "GET":
        if ($resource === "tasks" && is_numeric($id)) {
            $controller->obtenerTarea($id);
        } elseif ($resource === "tasks") {
            $controller->obtenerDatos();
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Ruta no encontrada"]);
        }
        break;

    case "POST":
        if ($resource === "tasks") {
            $data = json_decode(file_get_contents("php://input"), true);
            $controller->creartarea($data);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Ruta no encontrada"]);
        }
        break;

    case "PUT":
        if ($resource === "tasks" && is_numeric($id)) {
            $data = json_decode(file_get_contents("php://input"), true);
            $controller->actualizarTareas($id, $data);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Ruta no encontrada"]);
        }
        break;

    case "DELETE":
        if ($resource === "tasks" && is_numeric($id)) {
            $controller->eliminarTarea($id);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Ruta no encontrada"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
