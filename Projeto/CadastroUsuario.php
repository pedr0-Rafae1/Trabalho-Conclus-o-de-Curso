<?php

require_once __DIR__ .  '/../app/Dao/UsuarioDao.php';
$hoje = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - Pecuária em Rede</title>
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
        <h2>Criar Conta</h2>
        <p class="subtitulo">Leva menos de um minuto</p>

        <?php if (isset($_GET['erro'])): ?>
          <div class="auth-alert" role="alert">
            <span>
              <?php
                $mensagens = [
                  'campos_vazios' => 'Preencha todos os campos obrigatórios.',
                  'nome_invalido' => 'Nome inválido: use apenas letras e espaços (mínimo 3 caracteres).',
                  'senhas_diferentes' => 'As senhas não conferem. Digite novamente.',
                  'email_invalido' => 'E-mail inválido. Verifique o endereço informado.',
                  'idade_invalida' => 'A idade deve ser um número inteiro maior que zero.',
                  'email_duplicado' => 'Este e-mail já está cadastrado. Faça login ou recupere sua senha.',
                  'email_falhou' => 'Não foi possível enviar o e-mail de verificação. Tente novamente.',
                  'banco' => 'Erro ao salvar no banco de dados. Tente novamente.'
                ];
                echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Erro ao processar o cadastro.');
              ?>
            </span>
            <button type="button" class="auth-alert-fechar" aria-label="Fechar"><i class="fas fa-times"></i></button>
          </div>
        <?php endif; ?>

        <form action="Processar_Usuario.php" method="POST" id="formCadastro">

          <div class="campo-flutuante">
            <i class="fas fa-user campo-icone"></i>
            <input type="text" id="nome" name="nome" placeholder=" " pattern="[A-Za-zÀ-ÖØ-öø-ÿ ]{3,}" title="Apenas letras e espaços, mínimo 3 caracteres" required>
            <label for="nome">Nome completo</label>
            <i class="fas fa-check campo-check"></i>
          </div>

          <div class="campo-flutuante">
            <i class="fas fa-birthday-cake campo-icone"></i>
            <input type="number" id="idade" name="idade" placeholder=" " min="16" max="120" step="1" required>
            <label for="idade">Idade</label>
          </div>

          <div class="campo-flutuante">
            <i class="fas fa-envelope campo-icone"></i>
            <input type="email" id="email" name="email" placeholder=" " required>
            <label for="email">E-mail</label>
            <i class="fas fa-check campo-check"></i>
          </div>

          <div class="campo-flutuante">
            <i class="fas fa-lock campo-icone"></i>
            <input type="password" id="Senha" name="senha" placeholder=" " required>
            <label for="Senha">Crie uma senha</label>
            <i class="fas fa-eye toggle-senha" id="toggleSenha"></i>
          </div>

          <div class="forca-senha" id="forcaSenha" data-nivel="" aria-hidden="true">
            <div class="forca-senha-barra"></div>
          </div>
          <span class="forca-senha-texto" id="forcaSenhaTexto"></span>

          <div class="campo-flutuante">
            <i class="fas fa-lock campo-icone"></i>
            <input type="password" id="senhaConfirmacao" name="senha_confirmacao" placeholder=" " required>
            <label for="senhaConfirmacao">Confirme a senha</label>
            <i class="fas fa-check campo-check"></i>
          </div>

          <label class="rotulo-tipo">Você é:</label>
          <div class="opcoes-tipo">
            <label class="opcao-tipo">
              <input type="radio" name="tipo_usuario" value="Pecuarista" checked>
              <span><i class="fas fa-tractor"></i> Pecuarista</span>
            </label>
            <label class="opcao-tipo">
              <input type="radio" name="tipo_usuario" value="Veterinario">
              <span><i class="fas fa-stethoscope"></i> Veterinário</span>
            </label>
          </div>
          <small class="aviso-homologacao">Veterinários aguardam homologação manual antes de acessar as ferramentas profissionais.</small>

          <button type="submit" id="btnCadastrar">Cadastrar</button>

          <p class="footer-link">Já tem uma conta? <a href="login.php">Entrar</a></p>
        </form>
      </div>
    </div>

  </main>

  <script src="../Javascript/ThemeToggle.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      inicializarThemeToggle('themeToggle');
    });

    // Fechar banners de alerta (X)
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.auth-alert-fechar');
      if (btn) btn.closest('.auth-alert').remove();
    });

    // Mostrar/ocultar senha
    const toggleSenha = document.getElementById('toggleSenha');
    const campoSenha = document.getElementById('Senha');
    toggleSenha.addEventListener('click', () => {
      const tipo = campoSenha.getAttribute('type') === 'password' ? 'text' : 'password';
      campoSenha.setAttribute('type', tipo);
      toggleSenha.classList.toggle('fa-eye');
      toggleSenha.classList.toggle('fa-eye-slash');
    });

    // Validação inline em tempo real
    const campoNome = document.getElementById('nome');
    const campoEmail = document.getElementById('email');
    const campoConfirmacao = document.getElementById('senhaConfirmacao');

    campoNome.addEventListener('input', () => {
      const valido = /^[A-Za-zÀ-ÖØ-öø-ÿ ]{3,}$/.test(campoNome.value.trim());
      campoNome.closest('.campo-flutuante').classList.toggle('valido', valido);
    });

    campoEmail.addEventListener('input', () => {
      const valido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(campoEmail.value.trim());
      campoEmail.closest('.campo-flutuante').classList.toggle('valido', valido);
    });

    function checarConfirmacao() {
      const confere = campoConfirmacao.value.length > 0 && campoConfirmacao.value === campoSenha.value;
      campoConfirmacao.closest('.campo-flutuante').classList.toggle('valido', confere);
    }
    campoConfirmacao.addEventListener('input', checarConfirmacao);
    campoSenha.addEventListener('input', checarConfirmacao);

    // Barra de força de senha
    const forcaSenha = document.getElementById('forcaSenha');
    const forcaTexto = document.getElementById('forcaSenhaTexto');
    campoSenha.addEventListener('input', () => {
      const s = campoSenha.value;
      let pontos = 0;
      if (s.length >= 8) pontos++;
      if (/[a-z]/.test(s) && /[A-Z]/.test(s)) pontos++;
      if (/\d/.test(s)) pontos++;
      if (/[^A-Za-z0-9]/.test(s)) pontos++;

      let nivel = '', texto = '';
      if (s.length > 0) {
        if (pontos <= 1) { nivel = 'fraca'; texto = 'Senha fraca'; }
        else if (pontos <= 3) { nivel = 'media'; texto = 'Senha média'; }
        else { nivel = 'forte'; texto = 'Senha forte'; }
      }
      forcaSenha.dataset.nivel = nivel;
      forcaTexto.textContent = texto;
    });

    // Estado de carregamento do botão (sem preventDefault)
    document.getElementById('formCadastro').addEventListener('submit', () => {
      const btn = document.getElementById('btnCadastrar');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cadastrando...';
    });
  </script>

</body>
</html>
