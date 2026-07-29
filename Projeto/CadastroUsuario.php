<?php
require_once __DIR__ .  '/../app/Dao/UsuarioDao.php';
$hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/Cadastro.css?v = 1.2">
  <link rel="stylesheet" href="../CSS/Variaveis.css">
</head>
<body>

 
  <main class="card">
    <h2>Cadastro de Usuário</h2>
    <p>Faça o cadastro para pode acessa a plataforma</p>
    <form action="Processar_Usuario.php" method="POST">

      <label for="nome">Nome Completo:</label>
      <input type="text" id="nome" name="nome" class="form-control" placeholder="Ex: pedro" required>

      <label for="idade">Idade:</label>
      <input type="number" id="idade" name="idade" class="form-control" placeholder="Ex: 18" required>

      <label for="email">E-mail:</label>
      <input type="email" id="email" name="email" class="form-control" placeholder="Ex: xxxx@xxxx" required>

      <label for="Senha">Senha:</label>
      <input type="password" id="Senha" name="senha" class="form-control" placeholder="Ex: asbft7890" required>

      <label for="tipo_usuario">Você é:</label>
      <select id="tipo_usuario" name="tipo_usuario" class="form-control" required>
        <option value="Pecuarista">Pecuarista / Produtor</option>
        <option value="Veterinario">Veterinário</option>
      </select>
      <small>Cadastros de Veterinário passam por homologação antes de poder responder dúvidas.</small>

      <button type="submit">Cadastrar</button>
    </form>

</body>
</html>