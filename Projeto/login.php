<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Pecuária em Rede</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <script>
    (function () {
      const tema = localStorage.getItem('rpp-tema') === 'dark' ? 'dark' : 'light';
      document.documentElement.dataset.theme = tema;
      document.documentElement.style.backgroundColor = tema === 'dark' ? '#111b16' : '#f4f8f4';
    })();
  </script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/Variaveis.css?v=2.2">
  <link rel="stylesheet" href="../CSS/Login.css?v=3.0">
</head>
<body>

  <button class="theme-toggle" type="button" id="themeToggle" title="Alternar modo claro e escuro" aria-label="Alternar modo claro e escuro">
    <i class="fas fa-moon" aria-hidden="true"></i>
  </button>

  <main class="auth-wrapper">

    <aside class="auth-branding">
      <a href="../index.php" class="branding-home" title="Voltar para a página inicial">
        <i class="fas fa-cow branding-icon"></i>
        <h1>Pecuária em Rede</h1>
      </a>
      <p class="branding-frase">Gestão inteligente para o seu rebanho.</p>
      <ul class="branding-vantagens">
        <li><i class="fas fa-check-circle"></i> Controle simplificado</li>
        <li><i class="fas fa-check-circle"></i> Acesso em qualquer dispositivo</li>
        <li><i class="fas fa-check-circle"></i> Suporte especializado</li>
      </ul>
      <span class="branding-tag">TCC - IFRN Nova Cruz Campus</span>
    </aside>

    <div class="auth-form">
      <div class="auth-card">
        <h2>Fazer Login</h2>
        <p class="subtitulo">Acesse sua conta para continuar</p>

        <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso'): ?>
          <div class="auth-alert auth-alert-success" role="alert">
            <span>Conta criada com sucesso! Entre com seus dados para continuar.</span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php elseif (isset($_GET['senha']) && $_GET['senha'] === 'redefinida'): ?>
          <div class="auth-alert auth-alert-success" role="alert">
            <span>Senha redefinida com sucesso! Faça login com a nova senha.</span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php elseif (isset($_GET['motivo']) && $_GET['motivo'] === 'acesso_restrito'): ?>
          <div class="auth-alert" role="alert">
            <span>Entre na sua conta ou cadastre-se para acessar esse recurso.</span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php elseif (isset($_GET['erro'])): ?>
          <div class="auth-alert" role="alert">
            <span>
              <?php
                $mensagens = [
                  'senha_incorreta' => 'Senha incorreta. Tente novamente.',
                  'usuario_nao_encontrado' => 'Não encontramos uma conta com esse e-mail.',
                  'conta_bloqueada' => 'Muitas tentativas incorretas. Conta bloqueada por 1 minuto.',
                ];
                echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Não foi possível entrar. Verifique os dados.');
              ?>
            </span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php endif; ?>

        <form action="ProcessarLogin.php" method="POST" id="formLogin">
          <div class="campo-flutuante">
            <i class="fas fa-envelope campo-icone"></i>
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">E-mail</label>
            <i class="fas fa-check campo-check"></i>
          </div>

          <div class="campo-flutuante">
            <i class="fas fa-lock campo-icone"></i>
            <input type="password" id="senha" name="senha" placeholder=" " required>
            <label for="senha">Senha</label>
            <i class="fas fa-eye toggle-senha" id="toggleSenha"></i>
          </div>

          <button type="submit" id="btnEntrar">Entrar</button>

          <p class="footer-link"><a href="EsqueciSenha.php">Esqueci minha senha</a></p>
          <p class="footer-link">Não possui uma conta? <a href="CadastroUsuario.php">Cadastre-se</a></p>
        </form>
      </div>
    </div>

  </main>

  <script src="../Javascript/ThemeToggle.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      inicializarThemeToggle('themeToggle');
    });

    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.auth-alert-fechar');
      if (btn) btn.closest('.auth-alert').remove();
    });

    const toggleSenha = document.getElementById('toggleSenha');
    const campoSenha = document.getElementById('senha');
    toggleSenha.addEventListener('click', () => {
      const tipo = campoSenha.getAttribute('type') === 'password' ? 'text' : 'password';
      campoSenha.setAttribute('type', tipo);
      toggleSenha.classList.toggle('fa-eye');
      toggleSenha.classList.toggle('fa-eye-slash');
    });

    const campoEmail = document.getElementById('email');
    campoEmail.addEventListener('input', () => {
      const valido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campoEmail.value.trim());
      campoEmail.closest('.campo-flutuante').classList.toggle('valido', valido);
    });

    document.getElementById('formLogin').addEventListener('submit', () => {
      const btn = document.getElementById('btnEntrar');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Entrando...';
    });
  </script>

</body>
</html>
