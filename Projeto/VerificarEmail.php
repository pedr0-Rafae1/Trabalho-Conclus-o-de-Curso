<?php
session_start();
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

$email = trim($_GET['email'] ?? $_POST['email'] ?? '');
$erro = '';
$sucesso = isset($_GET['pendente']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo'] ?? '');

    // Reenvio de código (com trava anti-spam de 60 segundos)
    if (isset($_POST['reenviar']) && $email !== '') {
        $intervaloMinimo = 60;
        $ultimoReenvio = (int) ($_SESSION['ultimo_reenvio'] ?? 0);
        $restante = $intervaloMinimo - (time() - $ultimoReenvio);

        if ($restante > 0) {
            $erro = "Aguarde {$restante} segundos antes de pedir um novo código.";
        } else {
            $dao = new UsuarioDao();
            $usuario = $dao->buscarPorEmail($email);
            if ($usuario && (int) ($usuario['email_verificado'] ?? 0) === 0) {
                require_once __DIR__ . '/../app/Servico/EmailService.php';
                $novoCodigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $dao->SalvarCodigoVerificacao($usuario['id_usuario'], $novoCodigo);
                if (EmailService::enviarCodigoCadastro($email, $usuario['nome'], $novoCodigo)) {
                    $_SESSION['ultimo_reenvio'] = time();
                    $sucesso = true;
                    $erro = '';
                } else {
                    $erro = 'Não foi possível reenviar o e-mail agora. Tente novamente em instantes.';
                }
            }
        }
    } elseif ($email === '' || $codigo === '') {
        $erro = 'Informe o e-mail e o código de verificação.';
    } else {
        $dao = new UsuarioDao();
        $resultado = $dao->ConfirmarEmail($email, $codigo);

        if ($resultado === true) {
            header("Location: login.php?cadastro=sucesso");
            exit();
        }

        $erro = match ($resultado) {
            'expirado'  => 'Código expirado (válido por 15 minutos). Reenvie um novo código.',
            'bloqueado' => 'Muitas tentativas incorretas. Reenvie um novo código.',
            default     => 'Código inválido. Verifique e tente novamente.',
        };
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificar E-mail - Pecuária em Rede</title>
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
        <h2>Verificar E-mail</h2>
        <p class="subtitulo">Enviamos um código de 6 dígitos para <strong><?= htmlspecialchars($email) ?></strong></p>

        <?php if ($sucesso): ?>
          <div class="auth-alert auth-alert-success" role="alert">
            <span>Cadastro realizado! Confirme o código enviado ao seu e-mail.</span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php endif; ?>
        <?php if ($erro): ?>
          <div class="auth-alert" role="alert">
            <span><?= htmlspecialchars($erro) ?></span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="VerificarEmail.php">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
          <div class="campo-flutuante">
            <i class="fas fa-shield-halved campo-icone"></i>
            <input type="text" id="codigo" name="codigo" placeholder=" " maxlength="6" pattern="\d{6}" inputmode="numeric" required>
            <label for="codigo">Código de 6 dígitos</label>
          </div>
          <button type="submit">Confirmar Código</button>
        </form>

        <form method="POST" action="VerificarEmail.php" style="margin-top: 10px;" id="formReenviar">
          <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
          <input type="hidden" name="reenviar" value="1">
          <button type="submit" class="btn-reenviar" id="btnReenviar">Reenviar código</button>
        </form>

        <p class="footer-link">Já confirmou? <a href="login.php">Entrar</a></p>
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

    // Trava anti-spam do botão "Reenviar código": desabilita e inicia contagem regressiva
    (function () {
      const form = document.getElementById('formReenviar');
      const btn = document.getElementById('btnReenviar');
      if (!form || !btn) return;

      function iniciarContagem(segundos) {
        btn.disabled = true;
        btn.style.opacity = '0.55';
        btn.style.cursor = 'not-allowed';
        let restante = segundos;
        btn.textContent = 'Aguarde ' + restante + 's...';
        const timer = setInterval(() => {
          restante--;
          if (restante <= 0) {
            clearInterval(timer);
            btn.disabled = false;
            btn.style.opacity = '';
            btn.style.cursor = '';
            btn.textContent = 'Reenviar código';
          } else {
            btn.textContent = 'Aguarde ' + restante + 's...';
          }
        }, 1000);
      }

      form.addEventListener('submit', () => iniciarContagem(60));
    })();
  </script>

</body>
</html>
