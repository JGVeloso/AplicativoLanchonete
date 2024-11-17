<?php
session_start();

// Verifica se o usuário é um vendedor
if (!isset($_SESSION['nome']) || $_SESSION['vendedor'] != 1) {
    header('Location: login.php');
    exit();
}

$nome = $_SESSION['nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Área do Vendedor</title>
</head>
<body>
    <h1>Bem-vindo  <?php echo htmlspecialchars($nome); ?> !</h1>
    <p>Escolha uma das opções abaixo:</p>
    <ul>
        <li><a href="cadastroprodutos.php">Cadastrar Produtos</a></li>
        <li><a href="controle_pedidos.php">Controle de Pedidos</a></li>
        <li><a href="logout.php">Sair</a></li>
    </ul>
</body>
</html>
