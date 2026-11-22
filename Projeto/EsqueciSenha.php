<?php
session_start();
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';
require_once __DIR__ . '/../app/Servico/EmailService.php';

$erro = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail válido.';
    } else {
        $dao = new UsuarioDao();
        $usuario = $dao->buscarPorEmail($email);

        if ($usuario) {
            // Código de 6 dígitos com zeros à esquerda preservados (ex.: "004821")
            $codigo = EmailService::gerarCodigo6Digitos();
            $dao->SalvarCodigoRecuperacao($email, $codigo);
            EmailService::enviarCodigoRecuperacao($email, $usuario['nome'], $codigo);

            // Segue para a etapa 2 (código + nova senha)
            header("Location: RedefinirSenha.php?email=" . urlencode($email));
            exit();
        }

        // Resposta genérica para não revelar se o e-mail existe
        $sucesso = true;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar Senha - Pecuária em Rede</title>
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
        <h2>Recuperar Senha</h2>
        <p class="subtitulo">Informe seu e-mail para receber o código de recuperação</p>

        <?php if ($sucesso): ?>
          <div class="auth-alert auth-alert-success" role="alert">
            <span>Se o e-mail estiver cadastrado, você receberá um código de recuperação em instantes.</span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php endif; ?>
        <?php if ($erro): ?>
          <div class="auth-alert" role="alert">
            <span><?= htmlspecialchars($erro) ?></span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="EsqueciSenha.php">
          <div class="campo-flutuante">
            <i class="fas fa-envelope campo-icone"></i>
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">E-mail</label>
          </div>
          <button type="submit">Enviar Código de Recuperação</button>
          <p class="footer-link">Lembrou a senha? <a href="login.php">Voltar para o Login</a></p>
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
  </script>

</body>
</html>
