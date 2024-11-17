<?php
include 'banco.php';
session_start();
// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    header('Location: login.php');
    exit();
}

$nome = $_SESSION['nome'];
$departamento = $_SESSION['departamento'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $nome = $_SESSION['nome'];
    $departamento = $_SESSION['departamento'];

    // Obtém os detalhes do produto
    $stmt = $pdo->prepare("SELECT produto, preco, quantidade FROM estoque WHERE id = :id");
    $stmt->execute(['id' => $id_produto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto && $produto['quantidade'] >= $quantidade) {
        $preco_total = $produto['preco'] * $quantidade;

        // Insere o pedido na tabela
        $stmt = $pdo->prepare("
            INSERT INTO pedidos (produto, quantidade, preco_total, nome, departamento) 
            VALUES (:produto, :quantidade, :preco_total, :nome, :departamento)
        ");
        $stmt->execute([
            'produto' => $produto['produto'],
            'quantidade' => $quantidade,
            'preco_total' => $preco_total,
            'nome' => $nome,
            'departamento' => $departamento,
        ]);

        // Atualiza o estoque
        $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE id = :id");
        $stmt->execute(['quantidade' => $quantidade, 'id' => $id_produto]);

        $success = "Pedido registrado com sucesso!";
    } else {
        $error = "Quantidade solicitada não disponível no estoque.";
    }
}

// Busca os produtos
$stmt = $pdo->query("SELECT * FROM estoque");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca os produtos
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
        img{
            width: 80px;
        }
    </style>
</head>
<body>
<h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?> (<?php echo htmlspecialchars($departamento); ?>)</h1>
<a href="logout.php">Sair</a>
<div class="container">
        <h1>Estoque de Produtos</h1>

        <?php if (isset($success)) : ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if (isset($error)) : ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php foreach ($produtos as $produto): ?>
            <div class="item">
                <?php if (!empty($produto['imagem'])): ?>
                    <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="<?php echo htmlspecialchars($produto['produto']); ?>">
                <?php else: ?>
                    <img src="placeholder.png" alt="Imagem não disponível">
                <?php endif; ?>
                <div>
                    <h3><?php echo htmlspecialchars($produto['produto']); ?></h3>
                    <p><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                    <p><strong>Quantidade em Estoque:</strong> <?php echo htmlspecialchars($produto['quantidade']); ?> unidades</p>

                    <form action="home.php" method="POST">
                        <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
                        <label for="quantidade_<?php echo $produto['id']; ?>">Quantidade:</label>
                        <input type="number" name="quantidade" id="quantidade_<?php echo $produto['id']; ?>" min="1" max="<?php echo $produto['quantidade']; ?>" required>
                        <button type="submit">Fazer Pedido</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
</body>
</html>