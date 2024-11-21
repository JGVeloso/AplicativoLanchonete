<?php
include 'banco.php';
session_start();

// Verifica se o usuário é vendedor
if (!isset($_SESSION['nome']) || $_SESSION['vendedor'] != 1) {
    header('Location: login.php');
    exit();
}

// Verifica se o ID do produto foi enviado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Produto não encontrado.');
}

$id = $_GET['id'];

try {
    // Busca os detalhes do produto
    $stmt = $pdo->prepare("SELECT * FROM estoque WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        die('Produto não encontrado.');
    }

    // Busca as categorias do banco
    $stmtCategorias = $pdo->query("SELECT * FROM categorias");
    $categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

    // Atualiza o produto no banco se o formulário for enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
        if ($_POST['acao'] === 'salvar') {
            $nome_produto = $_POST['nome_produto'];
            $descricao = $_POST['descricao']; // Agora é opcional
            $preco = $_POST['preco'];
            $quantidade = $_POST['quantidade'];
            $categoria_id = $_POST['categoria'];
            $imagem = $produto['imagem'];

            // Verifica se uma nova imagem foi enviada
            if (!empty($_FILES['imagem']['name'])) {
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true); // Cria o diretório se não existir
                }
                $nova_imagem = $upload_dir . basename($_FILES['imagem']['name']);
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $nova_imagem)) {
                    $imagem = $nova_imagem;
                } else {
                    $error = "Erro ao salvar a nova imagem.";
                }
            }

            // Atualiza os dados no banco
            if (empty($error)) {
                // Se a descrição não foi fornecida, mantemos a descrição atual
                $descricao = empty($descricao) ? $produto['descricao'] : $descricao;

                $stmt = $pdo->prepare("
                    UPDATE estoque 
                    SET produto = :nome_produto, descricao = :descricao, preco = :preco, quantidade = :quantidade, categoria_id = :categoria_id, imagem = :imagem 
                    WHERE id = :id
                ");
                $stmt->execute([
                    'nome_produto' => $nome_produto,
                    'descricao' => $descricao,
                    'preco' => $preco,
                    'quantidade' => $quantidade,
                    'categoria_id' => $categoria_id,
                    'imagem' => $imagem,
                    'id' => $id,
                ]);

                $success = "Produto atualizado com sucesso!";
            }
        } elseif ($_POST['acao'] === 'excluir') {
            // Exclui o produto do banco
            $stmt = $pdo->prepare("DELETE FROM estoque WHERE id = :id");
            $stmt->execute(['id' => $id]);
            header('Location: cadastroprodutos.php?success=Produto excluído com sucesso!');
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Erro ao acessar o banco de dados: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="pedidos">
    <h1>Editar Produto</h1>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="editar_produto.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="salvar">

        <label for="nome_produto">Nome do Produto:</label>
        <input type="text" id="nome_produto" name="nome_produto" value="<?php echo htmlspecialchars($produto['produto']); ?>" required>
        <br>

        <label for="descricao">Descrição (opcional):</label>
        <textarea id="descricao" name="descricao" rows="4"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
        <br>

        <label for="preco">Preço:</label>
        <input type="number" step="0.01" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required>
        <br>

        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" value="<?php echo htmlspecialchars($produto['quantidade']); ?>" required>
        <br>

        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?php echo $categoria['id']; ?>" <?php if ($categoria['id'] == $produto['categoria_id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($categoria['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>

        <label for="imagem">Imagem (opcional):</label>
        <input type="file" id="imagem" name="imagem">
        <br>
        <?php if (!empty($produto['imagem'])): ?>
            <p>Imagem Atual:</p>
            <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" alt="Imagem do Produto" width="100">
        <?php endif; ?>
        <br>
        <button type="submit">Salvar Alterações</button>
    </form>

    <form action="editar_produto.php?id=<?php echo $id; ?>" method="POST" style="margin-top: 10px;">
        <input type="hidden" name="acao" value="excluir">
        <button type="submit" style="background-color: red; color: white;">Excluir Produto</button>
    </form>

    <a href="cadastroprodutos.php">Voltar</a>
    </div>
</body>
</html>
