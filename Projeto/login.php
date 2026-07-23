<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="../CSS/Login.css">
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