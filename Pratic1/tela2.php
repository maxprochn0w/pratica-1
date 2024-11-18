<?php
$host = "localhost";
$db = "SuporteTecnico";
$user = "root";
$pass = "root";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['chamadoID'])) {
    $chamadoID = $_POST['chamadoID'];
    $status = $_POST['status'];
    $colaboradorID = $_POST['colaboradorID'];

    $sql = "UPDATE Chamados SET Status = '$status', ColaboradorID = " . ($colaboradorID ?: 'NULL') . " WHERE ChamadoID = $chamadoID";
    $conn->query($sql);
}

$statusFiltro = $_GET['status'] ?? '';
$criticidadeFiltro = $_GET['criticidade'] ?? '';

$sql = "SELECT c.ChamadoID, c.Descricao, c.Status, c.Criticidade, c.DataAbertura, 
        cl.Nome AS ClienteNome, co.Nome AS ColaboradorNome
        FROM Chamados c
        LEFT JOIN Clientes cl ON c.ClienteID = cl.ClienteID
        LEFT JOIN Colaboradores co ON c.ColaboradorID = co.ColaboradorID
        WHERE 1";

if ($statusFiltro) {
    $sql .= " AND c.Status = '$statusFiltro'";
}
if ($criticidadeFiltro) {
    $sql .= " AND c.Criticidade = '$criticidadeFiltro'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Chamados</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Gerenciamento de Chamados</h1>
    <style>
        .nav-buttons {
            margin-bottom: 20px;
        }
        .nav-buttons a {
            
            padding: 10px 20px;
             color: black;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="nav-buttons">
        <a href="pratic1.php">Cadastro de clientes</a>
    </div>

    <form method="GET">
        <label for="status">Filtrar por Status:</label>
        <select id="status" name="status">
            <option value="">Todos</option>
            <option value="aberto" <?= $statusFiltro == 'aberto' ? 'selected' : ''; ?>>Aberto</option>
            <option value="em andamento" <?= $statusFiltro == 'em andamento' ? 'selected' : ''; ?>>Em andamento</option>
            <option value="resolvido" <?= $statusFiltro == 'resolvido' ? 'selected' : ''; ?>>Resolvido</option>
        </select>

        <label for="criticidade">Filtrar por Criticidade:</label>
        <select id="criticidade" name="criticidade">
            <option value="">Todas</option>
            <option value="baixa" <?= $criticidadeFiltro == 'baixa' ? 'selected' : ''; ?>>Baixa</option>
            <option value="média" <?= $criticidadeFiltro == 'média' ? 'selected' : ''; ?>>Média</option>
            <option value="alta" <?= $criticidadeFiltro == 'alta' ? 'selected' : ''; ?>>Alta</option>
        </select>

        <button type="submit">Filtrar</button>
        
    </form>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Cliente</th>
                <th>Status</th>
                <th>Criticidade</th>
                <th>Data de Abertura</th>
                <th>Colaborador</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) : ?>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row['ChamadoID']; ?></td>
                        <td><?= $row['Descricao']; ?></td>
                        <td><?= $row['ClienteNome']; ?></td>
                        <td><?= $row['Status']; ?></td>
                        <td><?= $row['Criticidade']; ?></td>
                        <td><?= $row['DataAbertura']; ?></td>
                        <td><?= $row['ColaboradorNome'] ?: 'Não atribuído'; ?></td>
                        <td>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="chamadoID" value="<?= $row['ChamadoID']; ?>">
                                <label for="status">Status:</label>
                                <select name="status">
                                    <option value="aberto" <?= $row['Status'] == 'aberto' ? 'selected' : ''; ?>>Aberto</option>
                                    <option value="em andamento" <?= $row['Status'] == 'em andamento' ? 'selected' : ''; ?>>Em andamento</option>
                                    <option value="resolvido" <?= $row['Status'] == 'resolvido' ? 'selected' : ''; ?>>Resolvido</option>
                                </select>
                                <label for="colaboradorID">Colaborador:</label>
                                <input type="number" name="colaboradorID" value="<?= $row['ColaboradorNome'] ? $row['ColaboradorNome'] : ''; ?>" placeholder="ID do Colaborador">
                                <button type="submit">Atualizar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8">Nenhum chamado encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
