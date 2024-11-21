<?php
include 'banco.php';
session_start();

// Verifica se o usuário é vendedor
if (!isset($_SESSION['nome']) || $_SESSION['vendedor'] != 1) {
    header('Location: login.php');
    exit();
}

$success = $error = "";

// Atualiza o status de um pedido se o botão "Confirmar" for clicado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pedido'])) {
    $id_pedido = $_POST['id_pedido'];
    try {
        $stmt = $pdo->prepare("UPDATE pedidos SET confirmado = 1 WHERE id = :id");
        $stmt->execute(['id' => $id_pedido]);
        $success = "Pedido confirmado com sucesso!";
    } catch (PDOException $e) {
        $error = "Erro ao confirmar o pedido: " . $e->getMessage();
    }
}

// Busca todos os pedidos no banco de dados
$stmt = $pdo->query("SELECT * FROM pedidos");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Controle de Pedidos</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        button {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style> -->
    <script>
        // Atualiza a página a cada 60 segundos
        setTimeout(() => {
            window.location.reload();
        }, 30000); 
    </script>
</head>
<body>
<div class="pedidos">
    <h1>Controle de Pedidos</h1>

    <?php if (!empty($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>Produto</th>
            <th>QTD</th>
            <th>Preço Total</th>
            <th>Usuário</th>
            <th>Método de pagamento</th>
            <th>Departamento</th>
            <th>Data do Pedido</th>
            <th>Status</th>
            <th>Ação</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?php echo htmlspecialchars($pedido['produto']); ?></td>
                <td><?php echo htmlspecialchars($pedido['quantidade']); ?></td>
                <td>R$ <?php echo number_format($pedido['preco_total'], 2, ',', '.'); ?></td>
                <td><?php echo htmlspecialchars($pedido['nome']); ?></td>
                <td><?php echo htmlspecialchars($pedido['metodo_pagamento']); ?></td>
                <td><?php echo htmlspecialchars($pedido['departamento']); ?></td>
                <td><?php echo htmlspecialchars($pedido['data_pedido']); ?></td>
                <td><?php echo $pedido['confirmado'] ? 'Confirmado' : 'Pendente'; ?></td>
                <td>
                    <?php if (!$pedido['confirmado']): ?>
                        <form method="POST" action="">
                            <input type="hidden" name="id_pedido" value="<?php echo $pedido['id']; ?>">
                            <button type="submit">Confirmar</button>
                        </form>
                    <?php else: ?>
                        <button class="disabled" disabled>Confirmado</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
