<?php
session_start();
include 'pages/banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $password = $_POST['password'];

    // Valida os campos
    if (empty($nome) || empty($password)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            // Consulta o banco de dados pelo nome do usuário
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = :nome");
            $stmt->execute(['nome' => $nome]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verifica a senha
                if (password_verify($password, $usuario['password'])) {
                    // Salva as informações do usuário na sessão
                    $_SESSION['nome'] = $usuario['nome'];
                    $_SESSION['departamento'] = $usuario['departamento'];
                    $_SESSION['vendedor'] = $usuario['vendedor'];

                    // Redireciona com base no tipo de usuário
                    if ($usuario['vendedor'] == 1) {
                        header('Location: pages/vendedor.php');
                    } else {
                        header('Location: pages/home.php');
                    }
                    exit();
                } else {
                    $erro = "Senha inválida.";
                }
            } else {
                $erro = "Nome de usuário não encontrado.";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao acessar o banco de dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($erro)): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="register.php">Crie uma conta!</a>.</p>
</body>
</html>
