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

// Processa o pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $data_entrega = $_POST['data_entrega']; // Captura a data de entrega
    $hora_entrega = $_POST['hora_entrega']; // Captura a hora de entrega

    // Validação da data e hora (opcional)
    if (strtotime($data_entrega) < strtotime('today')) {
        $error = "A data de entrega não pode ser anterior à data atual.";
    } else {
        // Obtém os detalhes do produto
        $stmt = $pdo->prepare("SELECT produto, preco, quantidade FROM estoque WHERE id = :id");
        $stmt->execute(['id' => $id_produto]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($produto && $produto['quantidade'] >= $quantidade) {
            $preco_total = $produto['preco'] * $quantidade;

            // Insere o pedido na tabela
            $stmt = $pdo->prepare("
                INSERT INTO pedidos (produto, quantidade, preco_total, nome, departamento, metodo_pagamento, data_entrega, hora_entrega) 
                VALUES (:produto, :quantidade, :preco_total, :nome, :departamento, :metodo_pagamento, :data_entrega, :hora_entrega)
            ");
            $stmt->execute([
                'produto' => $produto['produto'],
                'quantidade' => $quantidade,
                'preco_total' => $preco_total,
                'nome' => $nome,
                'departamento' => $departamento,
                'metodo_pagamento' => $metodo_pagamento,
                'data_entrega' => $data_entrega,
                'hora_entrega' => $hora_entrega,
            ]);

            // Atualiza o estoque
            $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE id = :id");
            $stmt->execute(['quantidade' => $quantidade, 'id' => $id_produto]);

            $success = "Pedido registrado com sucesso! Entrega agendada para $data_entrega às $hora_entrega.";
        } else {
            $error = "Quantidade solicitada não disponível no estoque.";
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_produto'])) {
        $id_produto = $_POST['id_produto'];
        $quantidade = $_POST['quantidade'];
        $metodo_pagamento = $_POST['metodo_pagamento'];
        $tipo_entrega = $_POST['tipo_entrega'];
        $data_entrega = isset($_POST['data_entrega']) ? $_POST['data_entrega'] : null;
        $hora_entrega = isset($_POST['hora_entrega']) ? $_POST['hora_entrega'] : null;
    
        // Validação adicional (apenas para entrega)
        if ($tipo_entrega === 'entrega' && (empty($data_entrega) || empty($hora_entrega))) {
            $error = "Por favor, preencha a data e a hora de entrega.";
        } else {
            $stmt = $pdo->prepare("SELECT produto, preco, quantidade FROM estoque WHERE id = :id");
            $stmt->execute(['id' => $id_produto]);
            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($produto && $produto['quantidade'] >= $quantidade) {
                $preco_total = $produto['preco'] * $quantidade;
    
                $stmt = $pdo->prepare("
                    INSERT INTO pedidos (produto, quantidade, preco_total, nome, departamento, metodo_pagamento, tipo_entrega, data_entrega, hora_entrega) 
                    VALUES (:produto, :quantidade, :preco_total, :nome, :departamento, :metodo_pagamento, :tipo_entrega, :data_entrega, :hora_entrega)
                ");
                $stmt->execute([
                    'produto' => $produto['produto'],
                    'quantidade' => $quantidade,
                    'preco_total' => $preco_total,
                    'nome' => $nome,
                    'departamento' => $departamento,
                    'metodo_pagamento' => $metodo_pagamento,
                    'tipo_entrega' => $tipo_entrega,
                    'data_entrega' => $data_entrega,
                    'hora_entrega' => $hora_entrega,
                ]);
    
                $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE id = :id");
                $stmt->execute(['quantidade' => $quantidade, 'id' => $id_produto]);
    
                $success = "Pedido registrado com sucesso!";
            } else {
                $error = "Quantidade solicitada não disponível no estoque.";
            }
        }
    }
    
}

// Busca as categorias
$stmtCategorias = $pdo->query("SELECT * FROM categorias");
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

// Busca os produtos, agrupando por categoria
$stmtProdutos = $pdo->query("SELECT * FROM estoque ORDER BY categoria_id");
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);

// Organiza os produtos por categoria
$produtosPorCategoria = [];
foreach ($produtos as $produto) {
    $produtosPorCategoria[$produto['categoria_id']][] = $produto;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Escolha sua comida</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="cabecalho">
    <img class="logotipohome" src="logo.jpg" alt="imagem do logotipo da lanchonete">
    <h1 class="bemvindo">Bem-vindo, <?php echo htmlspecialchars($nome); ?> (<?php echo htmlspecialchars($departamento); ?>)</h1>
    <a href="logout.php">Sair</a>
    </div>
<div class="container">
    <h1>Escolha seu Lanche! </h1>

    <?php if (isset($success)) : ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php foreach ($categorias as $categoria): ?>
        <h2><?php echo htmlspecialchars($categoria['nome']); ?></h2>

        <?php if (isset($produtosPorCategoria[$categoria['id']])): ?>
            <?php foreach ($produtosPorCategoria[$categoria['id']] as $produto): ?>
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
                        <p><strong>Descrição:</strong> <?php echo htmlspecialchars($produto['descricao']); ?></p>

                        <form action="home.php" method="POST">
    <input type="hidden" name="id_produto" value="<?php echo $produto['id']; ?>">
    <label for="quantidade_<?php echo $produto['id']; ?>">Quantidade:</label>
    <input type="number" name="quantidade" id="quantidade_<?php echo $produto['id']; ?>" min="1" max="<?php echo $produto['quantidade']; ?>" required>
    <br>
    <label for="metodo_pagamento_<?php echo $produto['id']; ?>">Método de Pagamento:</label>
    <select name="metodo_pagamento" id="metodo_pagamento_<?php echo $produto['id']; ?>" required>
        <option value="pix">Pix</option>
        <option value="dinheiro">Dinheiro</option>
        <option value="cartao">Cartão</option>
    </select>
    <br>
    <label for="tipo_entrega_<?php echo $produto['id']; ?>">Entrega ou Retirada:</label>
    <select name="tipo_entrega" id="tipo_entrega_<?php echo $produto['id']; ?>" required>
        <option value="entrega">Entrega</option>
        <option value="retirada">Retirada na Loja</option>
    </select>
    <br>
    <div id="entrega_<?php echo $produto['id']; ?>" style="display: none;">
        <label for="data_entrega_<?php echo $produto['id']; ?>">Data de Entrega:</label>
        <input type="date" name="data_entrega" id="data_entrega_<?php echo $produto['id']; ?>">
        <br>
        <label for="hora_entrega_<?php echo $produto['id']; ?>">Hora de Entrega:</label>
        <input type="time" name="hora_entrega" id="hora_entrega_<?php echo $produto['id']; ?>">
        <br>
    </div>
    <button type="submit">Fazer Pedido</button>
</form>

<script>
    // Exibe/oculta campos de entrega com base na seleção
    document.getElementById('tipo_entrega_<?php echo $produto['id']; ?>').addEventListener('change', function () {
        const entregaDiv = document.getElementById('entrega_<?php echo $produto['id']; ?>');
        if (this.value === 'entrega') {
            entregaDiv.style.display = 'block';
        } else {
            entregaDiv.style.display = 'none';
        }
    });
</script>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não há produtos disponíveis nesta categoria.</p>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
</body>
</html>
