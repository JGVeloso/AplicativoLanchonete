<?php
// controle_pedidos.php
include 'banco.php';

// Busca todos os pedidos
// $stmt = $pdo->query("SELECT * FROM pedidos ORDER BY data_pedido DESC");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <script>
        
        setTimeout(() => {
            window.location.reload();
        }, 60000); 
    </script>
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
        <table border="1">
    <tr>
        <th>ID</th>
        <th>Usuário</th>
        <th>Departamento</th>
        <th>Produto</th>
        <th>Quantidade</th>
        <th>Preço Total</th>
        <th>Método de Pagamento</th> <!-- Nova coluna -->
    </tr>
    <?php
    $stmt = $pdo->query("SELECT * FROM pedidos");
    while ($pedido = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>
                <td>{$pedido['id']}</td>
                <td>{$pedido['nome']}</td>
                <td>{$pedido['departamento']}</td>
                <td>{$pedido['produto']}</td>
                <td>{$pedido['quantidade']}</td>
                <td>R$ {$pedido['preco_total']}</td>
                <td>{$pedido['metodo_pagamento']}</td> <!-- Exibe o método de pagamento -->
              </tr>";
    }
    ?>
</table>
    </div>
</body>
</html>
