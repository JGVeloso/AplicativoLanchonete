<?php
// CadastroProdutos.php
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto = $_POST['produto'];
    $preco = $_POST['preco'];
    $quantidade = $_POST['quantidade'];

    // Verifica se todos os campos foram preenchidos
    if (empty($produto) || empty($preco) || empty($quantidade)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        // Insere o produto no banco de dados
        try {
            $stmt = $pdo->prepare("INSERT INTO estoque (produto, preco, quantidade) VALUES (:produto, :preco, :quantidade)");
            $stmt->bindParam(':produto', $produto);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':quantidade', $quantidade);

            if ($stmt->execute()) {
                $success = "Produto cadastrado com sucesso!";
            } else {
                $error = "Erro ao cadastrar o produto. Tente novamente.";
            }
        } catch (PDOException $e) {
            $error = "Erro no banco de dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
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
        form input {
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

        <form action="CadastroProdutos.php" method="POST">
            <label for="produto">Nome do Produto:</label>
            <input type="text" name="produto" id="produto" required>

            <label for="preco">Preço:</label>
            <input type="number" name="preco" id="preco" step="0.01" required>

            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" required>

            <button type="submit">Cadastrar Produto</button>
        </form>
    </div>
</body>
</html>
