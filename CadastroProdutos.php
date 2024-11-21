<?php
// cadastro_produto.php
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = $_POST['produto'];
    $descricao = $_POST['descricao']; // Captura a descrição do produto
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];
    $imagem = '';

    // Verifica se uma imagem foi enviada
    if (!empty($_FILES['imagem']['name'])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Cria o diretório se não existir
        }

        $imagem = $upload_dir . basename($_FILES['imagem']['name']);
        if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem)) {
            $error = "Erro ao salvar a imagem.";
        }
    }

    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO estoque (produto, descricao, preco, quantidade, imagem) VALUES (:produto, :descricao, :preco, :quantidade, :imagem)");
            $stmt->bindParam(':produto', $produto);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':imagem', $imagem);

            if ($stmt->execute()) {
                $success = "Produto cadastrado com sucesso!";
            } else {
                $error = "Erro ao cadastrar o produto.";
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }
    }
}

$stmt = $pdo->query("SELECT * FROM estoque"); // Consulta ao banco
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Recupera todos os produtos

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        } */
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            width: 100%;
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        form button:hover {
            background: #0056b3;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Produto</h1>

        <?php if (isset($error)) : ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)) : ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php
// Busca as categorias do banco de dados
$stmtCategorias = $pdo->query("SELECT * FROM categorias");
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="cadastro_produto.php" method="POST" enctype="multipart/form-data">
    <label for="produto">Nome do Produto:</label>
    <input type="text" name="produto" id="produto" required>

    <label for="preco">Preço:</label>
    <input type="number" name="preco" id="preco" step="0.01" required>

    <label for="quantidade">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade" required>

    <label for="categoria">Categoria:</label>
    <select name="categoria" id="categoria" required>
        <option value="">Selecione uma categoria</option>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?php echo $categoria['id']; ?>">
                <?php echo htmlspecialchars($categoria['nome']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="imagem">Imagem do Produto:</label>
    <input type="file" name="imagem" id="imagem" accept="image/*">

    <button type="submit">Cadastrar Produto</button>
</form>


        <h2>Produtos Cadastrados</h2>
        <?php foreach ($produtos as $produto): ?>
            <div class="item">
                <p><strong>Produto:</strong> <?php echo htmlspecialchars($produto['produto']); ?></p>
                <p><strong>Descrição:</strong> <?php echo htmlspecialchars($produto['descricao']); ?></p>
                <p><strong>Preço:</strong> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></p>
                <p><strong>Quantidade:</strong> <?php echo htmlspecialchars($produto['quantidade']); ?> unidades</p>
                <a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
