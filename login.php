<!DOCTYPE html>
<?php
        $db = new SQLite3('bd.sqlite');
        $db-> query('CREATE TABLE IF NOT EXISTS "usuarios"(
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "nome" VARCHAR NOT NULL,
                    "departamento" VARCHAR NOT NULL  )
                    ');
        $db-> query('CREATE TABLE IF NOT EXISTS "estoque" (
                    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    "produto" VARCHAR NOT NULL,
                    "preco" INT NOT NULL,
                    "quantidade" INT)'
                    );
                    ?>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
    <title>login - Lanchonete</title>
</head>
<body>
    
</body>
</html>