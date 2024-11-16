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
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
