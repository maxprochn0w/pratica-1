<?php
$conn = new mysqli('localhost', 'root', 'root', 'SuporteTecnico');

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$mensagemCliente = "";
$mensagemChamado = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_cliente'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);

    if (!empty($nome) && !empty($email)) {
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $mensagemCliente = "O e-mail informado já está cadastrado. Por favor, use outro.";
        } else {
            $stmt->close();

            $stmt = $conn->prepare("INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $telefone);

            if ($stmt->execute()) {
                $mensagemCliente = "Cliente cadastrado com sucesso!";
            } else {
                $mensagemCliente = "Erro ao cadastrar cliente: " . $stmt->error;
            }
        }
        $stmt->close();
    } else {
        $mensagemCliente = "Por favor, preencha todos os campos obrigatórios.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cadastrar_chamado'])) {
    $descricao = trim($_POST['descricao']);
    $status = trim($_POST['status']);
    $criticidade = trim($_POST['criticidade']);
    $cliente_id = intval($_POST['cliente_id']);

    if (!empty($cliente_id)) {
        $stmt = $conn->prepare("SELECT id FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $cliente_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $mensagemChamado = "Cliente não encontrado. Por favor, selecione um cliente válido.";
        } else {
            if (!empty($descricao) && !empty($status) && !empty($criticidade)) {
                $stmt->close();

                $stmt = $conn->prepare("INSERT INTO chamados (Descricao, Status, Criticidade, cliente_id, DataAbertura) 
                                        VALUES (?, ?, ?, ?, NOW())");
                $stmt->bind_param("sssi", $descricao, $status, $criticidade, $cliente_id);

                if ($stmt->execute()) {
                    $mensagemChamado = "Chamado registrado com sucesso!";
                } else {
                    $mensagemChamado = "Erro ao registrar chamado: " . $stmt->error;
                }
            } else {
                $mensagemChamado = "Por favor, preencha todos os campos obrigatórios para o chamado.";
            }
        }
        $stmt->close();
    } else {
        $mensagemChamado = "Por favor, forneça um ID de cliente válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset ="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes e Chamados</title>
    <style>
        .message, .success {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .message { background-color: #f8d7da; color: #721c24; }
        .success { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="nav-buttons">
        <a href="tela2.php">Gerenciar Chamados</a>
    </div>

    <h1>Cadastro de Clientes e Chamados</h1>

    <div class="form-container">
        <h2>Cadastro de Cliente</h2>
        <?php if (!empty($mensagemCliente)): ?>
            <div class="message"><?= $mensagemCliente; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="telefone">Telefone:</label><br>
            <input type="text" id="telefone" name="telefone"><br><br>

            <button type="submit" name="cadastrar_cliente">Cadastrar Cliente</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Cadastro de Chamado</h2>
        <?php if (!empty($mensagemChamado)): ?>
            <div class="success"><?= $mensagemChamado; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="descricao">Descrição do Chamado:</label><br>
            <input type="text" id="descricao" name="descricao" required><br><br>

            <label for="status">Status:</label><br>
            <select id="status" name="status" required>
                <option value="aberto">Aberto</option>
                <option value="em andamento">Em andamento</option>
                <option value="resolvido">Resolvido</option>
            </select><br><br>

            <label for="criticidade">Criticidade:</label><br>
            <select id="criticidade" name="criticidade" required>
                <option value="baixa">Baixa</option>
                <option value="média">Média</option>
                <option value="alta">Alta</option>
            </select><br><br>

            <label for="cliente_id">ID do Cliente:</label><br>
            <input type="number" id="cliente_id" name="cliente_id" required><br><br>

            <button type="submit" name="cadastrar_chamado">Registrar Chamado</button>
        </form>
    </div>
</body>
</html>