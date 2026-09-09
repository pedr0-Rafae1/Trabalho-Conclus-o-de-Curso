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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../CSS/Variaveis.css?v=1.3">
  <link rel="stylesheet" href="../CSS/Cadastro.css?v=2.0">
</head>
<body>

  <main class="auth-wrapper">
    <div class="auth-card">

      <div class="auth-branding">
        <i class="fas fa-user-plus branding-icon"></i>
        <h1>Pecuária em Rede</h1>
        <p>Crie sua conta e comece a organizar o manejo do seu rebanho hoje mesmo.</p>
      </div>

      <div class="auth-form">
        <h2>Criar Conta</h2>
        <p class="subtitulo">Leva menos de um minuto</p>

        <form action="Processar_Usuario.php" method="POST">

          <div class="input-icone">
            <i class="fas fa-user"></i>
            <input type="text" id="nome" name="nome" placeholder="Nome completo" required>
          </div>

          <div class="input-icone">
            <i class="fas fa-birthday-cake"></i>
            <input type="number" id="idade" name="idade" placeholder="Idade" required>
          </div>

          <div class="input-icone">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="seuemail@exemplo.com" required>
          </div>

          <div class="input-icone">
            <i class="fas fa-lock"></i>
            <input type="password" id="Senha" name="senha" placeholder="Crie uma senha" required>
            <i class="fas fa-eye toggle-senha" id="toggleSenha"></i>
          </div>

          <label class="rotulo-tipo">Você é:</label>
          <div class="opcoes-tipo">
            <label class="opcao-tipo">
              <input type="radio" name="tipo_usuario" value="Pecuarista" checked>
              <span><i class="fas fa-tractor"></i> Pecuarista / Produtor</span>
            </label>
            <label class="opcao-tipo">
              <input type="radio" name="tipo_usuario" value="Veterinario">
              <span><i class="fas fa-stethoscope"></i> Veterinário</span>
            </label>
          </div>
              <small class="aviso-homologacao">Veterinários aguardam homologação manual antes de acessar as ferramentas profissionais.</small>

          <button type="submit">Cadastrar</button>

          <p class="footer-link">
            Já tem uma conta? <a href="login.php">Entrar</a>
          </p>
        </form>
      </div>

    </div>
  </main>

  <script>
    const toggleSenha = document.getElementById('toggleSenha');
    const campoSenha = document.getElementById('Senha');
    toggleSenha.addEventListener('click', () => {
      const tipo = campoSenha.getAttribute('type') === 'password' ? 'text' : 'password';
      campoSenha.setAttribute('type', tipo);
      toggleSenha.classList.toggle('fa-eye');
      toggleSenha.classList.toggle('fa-eye-slash');
    });
  </script>
  
</body>
</html>