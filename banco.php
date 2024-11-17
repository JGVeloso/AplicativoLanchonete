<?php
        $db = new SQLite3('bd.sqlite');
        $db-> query('CREATE TABLE IF NOT EXISTS "usuarios"(
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "nome" VARCHAR NOT NULL,
                    "departamento" VARCHAR NOT NULL,
                    "password" TEXT NOT NULL,
                    "vendedor"  INT NOT NULL DEFAULT 0 )'
                    );
        $db-> query('CREATE TABLE IF NOT EXISTS "estoque" (
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "produto" VARCHAR NOT NULL,
                    "preco" INT NOT NULL,
                    "quantidade" INT)'
                    );
        
        


?>
<?php
try {
    $pdo = new PDO('sqlite:bd.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verifica se a coluna "imagem" já existe
    $stmt = $pdo->query("PRAGMA table_info(estoque)");
    $colunas = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);
    if (!in_array('imagem', $colunas)) {
        $pdo->exec("ALTER TABLE estoque ADD COLUMN imagem TEXT");
    }
    $pdo->exec("CREATE TABLE IF NOT EXISTS estoque (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        produto TEXT NOT NULL,
        preco REAL NOT NULL,
        quantidade INTEGER NOT NULL,
        imagem TEXT
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS pedidos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    departamento TEXT NOT NULL,
    produto TEXT NOT NULL,
    quantidade INTEGER NOT NULL,
    preco_total REAL NOT NULL,
    metodo_pagamento TEXT NOT NULL 
);");
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>


