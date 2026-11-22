<?php
session_start();
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

$email = trim($_GET['email'] ?? $_POST['email'] ?? '');
$erro = '';

$dao = new UsuarioDao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo    = trim($_POST['codigo'] ?? '');
    $senha     = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail válido.';
    } elseif (!preg_match('/^\d{6}$/', $codigo)) {
        $erro = 'O código deve ter exatamente 6 dígitos.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não coincidem.';
    } else {
        $usuario = $dao->BuscarPorCodigoRecuperacao($email, $codigo);

        if (!$usuario) {
            $erro = 'Código inválido ou expirado. Solicite um novo código.';
        } else {
            // Código correto: atualiza a senha (hash) e invalida o código usado
            $dao->RedefinirSenha($usuario['id_usuario'], $senha);
            header("Location: login.php?senha=redefinida");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redefinir Senha - Pecuária em Rede</title>
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
        <h2>Redefinir Senha</h2>
        <p class="subtitulo">Digite o código enviado ao seu e-mail e crie uma nova senha</p>

        <?php if ($erro): ?>
          <div class="auth-alert" role="alert">
            <span><?= htmlspecialchars($erro) ?></span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="RedefinirSenha.php">
          <div class="campo-flutuante">
            <i class="fas fa-envelope campo-icone"></i>
            <input type="email" id="email" name="email" placeholder=" " value="<?= htmlspecialchars($email) ?>" required>
            <label for="email">E-mail</label>
          </div>
          <div class="campo-flutuante">
            <i class="fas fa-shield-halved campo-icone"></i>
            <input type="text" id="codigo" name="codigo" placeholder=" " maxlength="6" pattern="\d{6}" inputmode="numeric" required>
            <label for="codigo">Código de 6 dígitos</label>
          </div>
          <div class="campo-flutuante">
            <i class="fas fa-lock campo-icone"></i>
            <input type="password" id="senha" name="senha" placeholder=" " minlength="6" required>
            <label for="senha">Nova senha (mín. 6 caracteres)</label>
          </div>
          <div class="campo-flutuante">
            <i class="fas fa-lock campo-icone"></i>
            <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder=" " minlength="6" required>
            <label for="confirmar_senha">Confirme a nova senha</label>
          </div>
          <button type="submit">Salvar Nova Senha</button>
          <p class="footer-link">Não recebeu o código? <a href="EsqueciSenha.php">Solicitar novo código</a></p>
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
