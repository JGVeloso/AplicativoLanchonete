<?php
// controle_pedidos.php
include 'banco.php';

// Busca todos os pedidos
$stmt = $pdo->query("SELECT * FROM pedidos ORDER BY data_pedido DESC");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Controle de Pedidos</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Controle de Pedidos</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Total</th>
                    <th>Usuário</th>
                    <th>Departamento</th>
                    <th>Data do Pedido</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo $pedido['id']; ?></td>
                        <td><?php echo htmlspecialchars($pedido['produto']); ?></td>
                        <td><?php echo $pedido['quantidade']; ?></td>
                        <td>R$ <?php echo number_format($pedido['preco_total'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($pedido['nome']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['departamento']); ?></td>
                        <td><?php echo $pedido['data_pedido']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
