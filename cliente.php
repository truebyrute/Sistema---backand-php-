<?php
// Configurações de conexão de resposta da api
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, PUT, GET, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

include("conexao.php");

global $conn;

$method = $_SERVER['REQUEST_METHOD'];

// Captura ID
$id = isset($_GET['id']) ? $_GET['id'] : null;


if ($method == 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        empty($data['nome']) ||
        empty($data['sobrenome']) ||
        empty($data['email']) ||
        empty($data['telefone'])
    ) {
        http_response_code(400);
        echo json_encode([
            "error" => "Todos os campos são obrigatórios."
        ], JSON_UNESCAPED_UNICODE);
        exit();
    }

    $nome = $data['nome'];
    $sobrenome = $data['sobrenome'];
    $email = $data['email'];
    $telefone = $data['telefone'];

    $sql = "INSERT INTO usuarios
    (nome, sobrenome, email, telefone)
    VALUES
    ('$nome', '$sobrenome', '$email', '$telefone')";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        $idCliente = mysqli_insert_id($conn);

        http_response_code(201);

        echo json_encode([
            "message" => "Cliente criado com sucesso.",
            "id" => $idCliente
        ], JSON_UNESCAPED_UNICODE);

    } else {

        http_response_code(500);

        echo json_encode([
            "error" => "Erro ao criar cliente."
        ], JSON_UNESCAPED_UNICODE);
    }

    exit();
}
// GET ALL
if ($method == 'GET' && $id == null) {

    $sql = "SELECT * FROM usuarios";
    $result = mysqli_query($conn, $sql);

    $usuarios = [];

    while ($usuario = mysqli_fetch_assoc($result)) {
        $usuarios[] = $usuario;
    }

    http_response_code(200);

    echo json_encode($usuarios, JSON_UNESCAPED_UNICODE);

    exit();
}

// GET BY ID
if ($method == 'GET' && $id != null) {

    $sql = "SELECT * FROM usuarios WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 0) {

        http_response_code(404);

        echo json_encode([
            "error" => "Usuário não encontrado."
        ], JSON_UNESCAPED_UNICODE);

        exit();
    }

    $usuario = mysqli_fetch_assoc($result);

    http_response_code(200);

    echo json_encode($usuario, JSON_UNESCAPED_UNICODE);

    exit();
}
// PUT - Atualizar usuário
if ($method == 'PUT') {

    if ($id == null) {

        http_response_code(400);

        echo json_encode([
            "error" => "ID obrigatório."
        ], JSON_UNESCAPED_UNICODE);

        exit();
    }

    // Verificar se usuário existe
    $sqlVerifica = "SELECT * FROM usuarios WHERE id = '$id'";
    $resultVerifica = mysqli_query($conn, $sqlVerifica);

    if (mysqli_num_rows($resultVerifica) == 0) {

        http_response_code(404);

        echo json_encode([
            "error" => "Usuário não encontrado."
        ], JSON_UNESCAPED_UNICODE);

        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        empty($data['nome']) ||
        empty($data['sobrenome']) ||
        empty($data['email']) ||
        empty($data['telefone'])
    ) {

        http_response_code(400);

        echo json_encode([
            "error" => "Todos os campos são obrigatórios."
        ], JSON_UNESCAPED_UNICODE);

        exit();
    }

    $nome = $data['nome'];
    $sobrenome = $data['sobrenome'];
    $email = $data['email'];
    $telefone = $data['telefone'];

    $sql = "UPDATE usuarios SET
        nome = '$nome',
        sobrenome = '$sobrenome',
        email = '$email',
        telefone = '$telefone'
        WHERE id = '$id'
    ";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        http_response_code(200);

        echo json_encode([
            "message" => "Usuário atualizado com sucesso."
        ], JSON_UNESCAPED_UNICODE);

    } else {

        http_response_code(500);

        echo json_encode([
            "error" => "Erro ao atualizar usuário."
        ], JSON_UNESCAPED_UNICODE);
    }

    exit();
}

// DELETE

if ($method == 'DELETE') {

    if ($id == null) {

        http_response_code(400);

        echo json_encode([
            "error" => "ID obrigatório."
        ], JSON_UNESCAPED_UNICODE);

        exit();
    }

    // Verifica se existe
    $sqlVerifica = "SELECT * FROM usuarios WHERE id = '$id'";
    $resultVerifica = mysqli_query($conn, $sqlVerifica);

    if (mysqli_num_rows($resultVerifica) == 0) {

        http_response_code(404);

        echo json_encode([
            "error" => "Usuário não encontrado."
        ], JSON_UNESCAPED_UNICODE);

        exit();
    }

    $sql = "DELETE FROM usuarios WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {

        http_response_code(200);

        echo json_encode([
            "message" => "Usuário deletado com sucesso."
        ], JSON_UNESCAPED_UNICODE);

    } else {

        http_response_code(500);

        echo json_encode([
            "error" => "Erro ao deletar usuário."
        ], JSON_UNESCAPED_UNICODE);
    }

    exit();
}
?>