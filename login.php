<?php
session_start();
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $password = $_POST['password'];

    
    if (empty($nome) || empty($password)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        try {
            
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = :nome");
            $stmt->execute(['nome' => $nome]);
            $nome = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($nome) {
                
                if (password_verify($password, $nome['password'])) {
                    
                    $_SESSION['nome'] = $nome['nome'];
                    $_SESSION['departamento'] = $nome['departamento'];
                    header('Location: home.php');
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
        <label for="password">Password:</label> 
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem uma conta? <a href="register.php">Crie uma conta!</a>.</p>
</body>
</html>