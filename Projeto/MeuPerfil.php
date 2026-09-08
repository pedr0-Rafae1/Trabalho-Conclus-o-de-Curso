<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();
$usuario = $usuarioDao->BuscarPorId($_SESSION['id_usuario']);

$temFoto = !empty($usuario['foto_perfil']) && file_exists(__DIR__ . '/../imagem/' . $usuario['foto_perfil']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Pecuária em Rede</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/MeuPerfil.css?v=2.5">
</head>
<body style="background-color: #f4f7f6; margin: 0; padding: 40px 20px;">

<main>
    <div class="perfil-wrapper shadow-sm">

        <div class="perfil-banner">
            <a href="home.php" style="position: absolute; top: 20px; left: 20px; color: white; text-decoration: none; background: rgba(0,0,0,0.2); padding: 6px 12px; border-radius: 20px; font-size: 0.85rem;">
                <i class="fas fa-arrow-left me-1"></i> Voltar ao Início
            </a>
            <span class="badge-tipo">
                <i class="fas fa-shield-alt me-1"></i> <?= $usuario['tipo_usuario'] === 'Veterinario' ? 'Veterinário' : 'Pecuarista' ?>
            </span>
        </div>

        <div class="perfil-corpo">

            <?php if ($temFoto): ?>
                <img src="../imagem/<?= htmlspecialchars($usuario['foto_perfil']) ?>" class="perfil-avatar-flutuante" alt="Foto de perfil">
            <?php else: ?>
                <div class="perfil-avatar-flutuante"><i class="fas fa-user"></i></div>
            <?php endif; ?>

            <div class="perfil-nome-area">
                <h1><?= htmlspecialchars($usuario['nome']) ?></h1>

                <?php if ($usuario['tipo_usuario'] === 'Veterinario'): ?>
                    <?php if ((int) $usuario['homologado'] === 1): ?>
                        <span class="perfil-status-homologacao status-ok"><i class="fas fa-check-circle me-1"></i> Homologado</span>
                    <?php else: ?>
                        <span class="perfil-status-homologacao status-pendente"><i class="fas fa-hourglass-half me-1"></i> Aguardando homologação</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['sucesso'])): ?>
                <div style="background: #d1e7dd; color: #0f5132; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                    <i class="fas fa-check-circle me-2"></i> Perfil atualizado com sucesso!
                </div>
            <?php elseif (isset($_GET['erro'])): ?>
                <div style="background: #f8d7da; color: #842029; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle me-2"></i> Não foi possível atualizar. Verifique os campos.
                </div>
            <?php endif; ?>

            <div class="perfil-grid">
                
                <div class="perfil-info-card">
                    <h3><i class="fas fa-info-circle me-2" style="color: #2e7d32;"></i> Informações da Conta</h3>

                    <div class="perfil-info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <span class="rotulo">E-mail</span>
                            <span><?= htmlspecialchars($usuario['email']) ?></span>
                        </div>
                    </div>

                    <div class="perfil-info-item">
                        <i class="fas fa-id-badge"></i>
                        <div>
                            <span class="rotulo">Tipo de conta</span>
                            <span><?= $usuario['tipo_usuario'] === 'Veterinario' ? 'Veterinário' : 'Pecuarista / Produtor' ?></span>
                        </div>
                    </div>

                    <div class="perfil-info-item">
                        <i class="fas fa-birthday-cake"></i>
                        <div>
                            <span class="rotulo">Idade cadastrada</span>
                            <span><?= (int) $usuario['idade'] ?> anos</span>
                        </div>
                    </div>
                </div>

                <div class="perfil-form">
                    <h3><i class="fas fa-user-edit me-2" style="color: #2e7d32;"></i> Editar Perfil</h3>

                    <form action="ProcessarAtualizarUsuario.php" method="POST" enctype="multipart/form-data">

                        <label>Alterar Foto de Perfil</label>
                        <div class="input-icone">
                            <i class="fas fa-camera"></i>
                            <input type="file" name="foto" accept="image/png, image/jpeg, image/webp">
                        </div>
                        <small style="color: #6c757d; font-size: 0.78rem; display: block; margin-bottom: 15px;">Formatos: JPG, PNG ou WEBP (Máx. 3MB).</small>

                        <label for="nome">Nome Completo</label>
                        <div class="input-icone">
                            <i class="fas fa-user"></i>
                            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                        </div>

                        <label for="idade">Idade</label>
                        <div class="input-icone">
                            <i class="fas fa-birthday-cake"></i>
                            <input type="number" id="idade" name="idade" value="<?= (int) $usuario['idade'] ?>" required>
                        </div>

                        <label>E-mail (Não editável)</label>
                        <div class="input-icone" style="margin-bottom: 20px;">
                            <i class="fas fa-envelope"></i>
                            <input type="email" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
                        </div>

                        <label for="senha">Nova Senha</label>
                        <div class="input-icone" style="position: relative; margin-bottom: 25px;">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="senha" name="senha" placeholder="Deixe em branco para manter a atual" style="padding-right: 45px;">
                            <i class="fas fa-eye toggle-senha" id="toggleSenha" style="cursor: pointer; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                        </div>

                        <button type="submit" class="btn-salvar-perfil">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>

            <div class="danger-zone">
                <div>
                    <h4 style="color: #dc3545; margin: 0 0 5px 0; font-size: 1.1rem;"><i class="fas fa-exclamation-triangle me-2"></i>Zona de Perigo</h4>
                    <p style="color: #666; margin: 0; font-size: 0.9rem;">A exclusão da conta é permanente e apagará todos os seus dados.</p>
                </div>
                <form action="ExcluirConta.php" method="POST" onsubmit="return confirm('Tem certeza absoluta que deseja excluir sua conta permanentemente?');">
                    <button type="submit" class="btn-excluir">
                        <i class="fas fa-trash-alt"></i> Excluir Conta
                    </button>
                </form>
            </div>

        </div>
    </div>
</main>

<script>
    const toggleSenha = document.getElementById('toggleSenha');
    const campoSenha = document.getElementById('senha');
    if (toggleSenha && campoSenha) {
        toggleSenha.addEventListener('click', () => {
            const tipo = campoSenha.getAttribute('type') === 'password' ? 'text' : 'password';
            campoSenha.setAttribute('type', tipo);
            toggleSenha.classList.toggle('fa-eye');
            toggleSenha.classList.toggle('fa-eye-slash');
        });
    }
</script>

</body>
</html>