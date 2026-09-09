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
    <link rel="stylesheet" href="../CSS/Variaveis.css?v=1.4">
    <link rel="stylesheet" href="../CSS/MeuPerfil.css?v=4.1">
    <script>
        (function () {
            const tema = localStorage.getItem('rpp-tema') === 'dark' ? 'dark' : 'light';
            document.documentElement.dataset.theme = tema;
            document.addEventListener('DOMContentLoaded', function () {
                document.body.dataset.theme = tema;
            });
        })();
    </script>
</head>
<body>

<main class="perfil-pagina">
    <div class="perfil-wrapper">

        <div class="perfil-banner">
            <a href="<?= $usuario['tipo_usuario'] === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php' ?>" class="perfil-voltar">
                <i class="fas fa-arrow-left" aria-hidden="true"></i> Voltar ao painel
            </a>
            <span class="badge-tipo">
                <i class="fas fa-shield-halved" aria-hidden="true"></i> <?= $usuario['tipo_usuario'] === 'Veterinario' ? 'Veterinário' : 'Pecuarista' ?>
            </span>
            <button class="perfil-theme-toggle" id="perfilThemeToggle" type="button" aria-label="Ativar modo escuro" title="Ativar modo escuro">
                <i class="fas fa-moon" aria-hidden="true"></i>
            </button>
        </div>

        <div class="perfil-corpo">

            <?php if ($temFoto): ?>
                <img src="../imagem/<?= htmlspecialchars($usuario['foto_perfil']) ?>" class="perfil-avatar-flutuante" alt="Foto de perfil">
            <?php else: ?>
                <div class="perfil-avatar-flutuante"><i class="fas fa-user"></i></div>
            <?php endif; ?>

            <div class="perfil-nome-area">
                <div>
                    <span class="perfil-overline">Perfil da conta</span>
                    <h1><?= htmlspecialchars($usuario['nome']) ?></h1>
                    <p><?= $usuario['tipo_usuario'] === 'Veterinario' ? 'Profissional de saúde animal' : 'Produtor e gestor do rebanho' ?></p>
                </div>

                <?php if ($usuario['tipo_usuario'] === 'Veterinario'): ?>
                    <?php if ((int) $usuario['homologado'] === 1): ?>
                        <span class="perfil-status-homologacao status-ok"><i class="fas fa-check-circle me-1"></i> Homologado</span>
                    <?php else: ?>
                        <span class="perfil-status-homologacao status-pendente"><i class="fas fa-hourglass-half me-1"></i> Aguardando homologação</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="perfil-alerta sucesso">
                    <i class="fas fa-check-circle" aria-hidden="true"></i> Perfil atualizado com sucesso!
                </div>
            <?php elseif (isset($_GET['erro'])): ?>
                <div class="perfil-alerta erro">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i> Não foi possível atualizar. Verifique os campos.
                </div>
            <?php endif; ?>

            <div class="perfil-grid">
                
                <div class="perfil-info-card">
                    <h3><i class="fas fa-id-card" aria-hidden="true"></i> Resumo da conta</h3>

                    <div class="perfil-info-item destaque">
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

                    <div class="perfil-info-item">
                        <i class="fas fa-shield-halved"></i>
                        <div>
                            <span class="rotulo">Acesso</span>
                            <span class="perfil-chip"><i class="fas fa-check"></i> Conta ativa</span>
                        </div>
                    </div>

                    <div class="perfil-nota">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <span>Seus dados ficam vinculados somente ao seu acesso.</span>
                    </div>
                </div>

                <div class="perfil-form">
                    <h3><i class="fas fa-sliders" aria-hidden="true"></i> Editar informações</h3>

                    <form action="ProcessarAtualizarUsuario.php" method="POST" enctype="multipart/form-data">

                        <label for="foto">Foto de perfil</label>
                        <div class="input-icone upload-field">
                            <i class="fas fa-camera"></i>
                            <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/webp">
                        </div>
                        <small class="campo-ajuda">JPG, PNG ou WEBP · até 3 MB</small>

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
                        <div class="input-icone campo-bloqueado">
                            <i class="fas fa-envelope"></i>
                            <input type="email" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
                        </div>

                        <label for="senha">Nova Senha</label>
                        <div class="input-icone campo-senha">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="senha" name="senha" placeholder="Deixe em branco para manter a atual">
                            <i class="fas fa-eye toggle-senha" id="toggleSenha" role="button" tabindex="0" aria-label="Mostrar senha"></i>
                        </div>

                        <button type="submit" class="btn-salvar-perfil">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>

            <div class="danger-zone">
                <div>
                    <h4><i class="fas fa-triangle-exclamation" aria-hidden="true"></i> Zona de perigo</h4>
                    <p>A exclusão é permanente e remove os dados da sua conta.</p>
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
    const perfilThemeToggle = document.getElementById('perfilThemeToggle');
    const temaInicial = document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';

    function atualizarTemaPerfil(tema) {
        const temaNormalizado = tema === 'dark' ? 'dark' : 'light';
        document.documentElement.dataset.theme = temaNormalizado;
        document.body.dataset.theme = temaNormalizado;
        localStorage.setItem('rpp-tema', temaNormalizado);

        if (perfilThemeToggle) {
            const escuro = temaNormalizado === 'dark';
            perfilThemeToggle.innerHTML = escuro
                ? '<i class="fas fa-sun" aria-hidden="true"></i>'
                : '<i class="fas fa-moon" aria-hidden="true"></i>';
            perfilThemeToggle.setAttribute('aria-label', escuro ? 'Ativar modo claro' : 'Ativar modo escuro');
            perfilThemeToggle.title = escuro ? 'Ativar modo claro' : 'Ativar modo escuro';
        }
    }

    atualizarTemaPerfil(temaInicial);
    perfilThemeToggle?.addEventListener('click', function () {
        atualizarTemaPerfil(document.body.dataset.theme === 'dark' ? 'light' : 'dark');
    });

    const toggleSenha = document.getElementById('toggleSenha');
    const campoSenha = document.getElementById('senha');
    if (toggleSenha && campoSenha) {
        const alternarSenha = () => {
            const tipo = campoSenha.getAttribute('type') === 'password' ? 'text' : 'password';
            campoSenha.setAttribute('type', tipo);
            toggleSenha.classList.toggle('fa-eye');
            toggleSenha.classList.toggle('fa-eye-slash');
            toggleSenha.setAttribute('aria-label', tipo === 'password' ? 'Mostrar senha' : 'Ocultar senha');
        };
        toggleSenha.addEventListener('click', alternarSenha);
        toggleSenha.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                alternarSenha();
            }
        });
    }
</script>

</body>
</html>