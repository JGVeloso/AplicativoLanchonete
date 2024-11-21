<?php
include 'banco.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $password = $_POST['password'];
    $departamento = $_POST['departamento'];

    // Validação básica
    if (empty($nome) || empty($password) || empty($departamento)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        // Verifica se o nome já existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nome = :nome");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();
        $userExists = $stmt->fetchColumn(); 

        if ($userExists) {
            $error = "Nome de usuário já existe. Escolha outro.";
        } else {
            // Criptografa a senha e insere o usuário no banco de dados
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, password, departamento) VALUES (:nome, :password, :departamento)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':departamento', $departamento);

            if ($stmt->execute()) {
                // Usuário registrado com sucesso
                $success = "Usuário registrado com sucesso! Agora você pode fazer login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Erro ao registrar o usuário. Tente novamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2 class="login">Cadastro de Usuário</h2>
    <div class="login">
    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label for="nome">Nome de Usuário:</label>
        <input type="text" name="nome" class="form" id="nome" required><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" class="form" id="password" required><br>

        <label for="departamento">Departamento:</label>
        <input type="text" name="departamento" class="form" id="departamento" required><br>

        <button type="submit" class="entrar">Registrar</button>
    </form>
        </div>
    <p class="login">Já tem uma conta? </p>
    <a class="crie" href="login.php">Faça login</a>
</body>
</html>