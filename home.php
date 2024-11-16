<?php
// home.php
include 'banco.php';

// Busca todos os produtos do estoque
$stmt = $pdo->query("SELECT * FROM estoque");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Escolha sua comida</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .item {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fafafa;
        }
        .item h3 {
            margin: 0;
            font-size: 20px;
        }
        .item p {
            margin: 5px 0;
            color: #555;
        }
        .item button {
            margin-top: 10px;
            padding: 10px 15px;
            font-size: 14px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .item button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Escolha seu lanche</h1>
        <?php foreach ($produtos as $produto): ?>
            <div class="item">
                <h3><?php echo htmlspecialchars($produto['produto']); ?></h3>
                <p><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <p><strong>Quantidade:</strong> <?php echo htmlspecialchars($produto['quantidade']); ?> unidades</p>
                <button 
                    onclick="alert('Você escolheu: <?php echo htmlspecialchars($produto['produto']); ?>')">
                    Escolher
                </button>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>