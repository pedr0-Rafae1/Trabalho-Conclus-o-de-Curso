<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Pecuária em Rede</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/Login.css?v = 1.2">
</head>
<body>

  <main class="auth-wrapper">
    <div class="auth-card">

      <div class="auth-branding">
        <i class="fas fa-horse branding-icon"></i>
        <h1>Pecuária em Rede</h1>
        <p>Gestão simples e conectada do seu rebanho, direto do curral até a nuvem.</p>
      </div>

      <div class="auth-form">
        <h2>Bem-vindo de volta</h2>
        <p class="subtitulo">Entre com sua conta para continuar</p>

        <?php if (isset($_GET['erro'])): ?>
          <div class="auth-alert">
            <?php
              $mensagens = [
                'senha_incorreta' => 'Senha incorreta. Tente novamente.',
                'usuario_nao_encontrado' => 'Não encontramos uma conta com esse e-mail.',
              ];
              echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Não foi possível entrar. Verifique os dados.');
            ?>
          </div>
        <?php endif; ?>

        <form action="ProcessarLogin.php" method="POST">
          <div class="input-icone">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>
          </div>

          <div class="input-icone">
            <i class="fas fa-lock"></i>
            <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
            <i class="fas fa-eye toggle-senha" id="toggleSenha"></i>
          </div>

          <button type="submit">Entrar no Sistema</button>

          <p class="footer-link">
            Não possui uma conta? <a href="CadastroUsuario.php">Cadastre-se</a>
          </p>
        </form>
      </div>

    </div>
  </main>

  <script>
    const toggleSenha = document.getElementById('toggleSenha');
    const campoSenha = document.getElementById('senha');
    toggleSenha.addEventListener('click', () => {
      const tipo = campoSenha.getAttribute('type') === 'password' ? 'text' : 'password';
      campoSenha.setAttribute('type', tipo);
      toggleSenha.classList.toggle('fa-eye');
      toggleSenha.classList.toggle('fa-eye-slash');
    });
  </script>

</body>
</html>