<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página não encontrada</title>
    <link rel="stylesheet" href="../CSS/pecuaria.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .error-container {
            text-align: center;
            padding: 80px 20px;
            font-family: Arial, sans-serif;
        }
        .error-container i {
            font-size: 64px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .error-container h1 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .error-container p {
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .btn-home {
            background-color: #27ae60;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .btn-home:hover {
            background-color: #219653;
        }
    </style>
</head>
<body>

    <?php include "Cabecalho.php"; ?>

    <main class="error-container">
        <i class="fas fa-exclamation-triangle"></i>
        <h1>Página não encontrada (404)</h1>
        <p>A página que você está tentando acessar não existe ou foi removida.</p>
        <a href="home.php" class="btn-home"><i class="fas fa-home me-2"></i> Voltar à Página Inicial</a>
    </main>

</body>
</html>