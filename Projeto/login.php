<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/Login.css">
  <link rel="stylesheet" href="../CSS/Variaveis.css?v = 1.2">
</head>
<body>

  <main class="card">
    <h2>Login</h2>
    <form action="ProcessarLogin.php" method="POST">

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" placeholder="Ex: usuario@email.com" required>

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" placeholder="*********" required>

      <button type="submit">Entrar no Sistema</button>
      
      <p class="footer-link">
        Não possui uma conta? <a href="CadastroUsuario.php">Cadastre-se</a>
      </p>
    </form>
  </main> </body>
</html>