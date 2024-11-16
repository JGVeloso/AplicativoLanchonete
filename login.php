<?php
include 'banco.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>login - Lanchonete</title>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $password = $_POST['password'];

    // Consulta o usuário no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = :nome");
    $stmt->bindParam(':nome', $nome);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Login bem-sucedido
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        echo "Login realizado com sucesso!";
        // Redirecionar para a página principal
        header("Location: home.php");
        exit();
    } else {
        // Falha no login
        $error = "Usuário ou senha inválidos.";
    }
}
?>

    <h2>Login</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="nome">Usuário:</label>
        <input type="text" name="nome" id="nome" required><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="register.php">Crie uma conta!</a>.</p>
   

    
</body>
</html>