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
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label for="nome">Nome de Usuário:</label>
        <input type="text" name="nome" id="nome" required><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required><br>

        <label for="departamento">Departamento:</label>
        <input type="text" name="departamento" id="departamento" required><br>

        <button type="submit">Registrar</button>
    </form>

    <p>Já tem uma conta? <a href="login.php">Faça login</a>.</p>
</body>
</html>