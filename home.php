<!DOCTYPE html>
<html lang="pt-br">
    <?php
        $db = new SQLite3('bd.sqlite');
        $db-> query('CREATE TABLE IF NOT EXISTS "usuarios"(
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "nome" VARCHAR,
                    "departamento" VARCHAR  )
                    ');
        $db-> query('CREATE TABLE IF NOT EXISTS "estoque" (
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "produto" VARCHAR,
                    "preco" INT,
                    "quantidade" INT)'
                    );
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>Lanchonete</title>
</head>
<body>
                    
    
</body>
</html>