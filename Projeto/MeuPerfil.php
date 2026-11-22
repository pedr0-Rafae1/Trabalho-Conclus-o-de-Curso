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
    <link rel="stylesheet" href="../CSS/Variaveis.css?v=1.5">
    <link rel="stylesheet" href="../CSS/MeuPerfil.css?v=2.0">
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

            <div class="perfil-avatar-flutuante" id="avatarPreview" role="button" tabindex="0" title="Trocar foto de perfil" aria-label="Trocar foto de perfil">
                <?php if ($temFoto): ?>
                    <img src="../imagem/<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto de perfil">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
                <span class="avatar-overlay"><i class="fas fa-camera"></i></span>
            </div>

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
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                    <?php
                        $mensagens = [
                            'idade_invalida' => 'A idade deve estar entre 16 e 120 anos.',
                            'senhas_diferentes' => 'As senhas não conferem. Nada foi alterado.',
                        ];
                        echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Não foi possível atualizar. Verifique os campos.');
                    ?>
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

                        <label for="foto" class="sr-only">Foto de perfil</label>
                        <input type="file" id="foto" name="foto" accept="image/png, image/jpeg, image/webp" class="sr-only">
                        <small class="campo-ajuda">Clique na foto acima para trocar · JPG, PNG ou WEBP · até 3 MB</small>

                        <label for="nome">Nome Completo</label>
                        <div class="input-icone">
                            <i class="fas fa-user"></i>
                            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                        </div>

                        <label for="idade">Idade</label>
                        <div class="input-icone">
                            <i class="fas fa-birthday-cake"></i>
                            <input type="number" id="idade" name="idade" value="<?= (int) $usuario['idade'] ?>" min="16" max="120" required>
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

                        <div class="forca-senha" id="forcaSenha" data-nivel="" aria-hidden="true" style="display:none;">
                            <div class="forca-senha-barra"></div>
                        </div>
                        <span class="forca-senha-texto" id="forcaSenhaTexto"></span>

                        <label for="senha_confirmacao">Confirme a nova senha</label>
                        <div class="input-icone campo-senha">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="senha_confirmacao" name="senha_confirmacao" placeholder="Repita a nova senha">
                            <i class="fas fa-check campo-check-senha" id="checkSenha" aria-hidden="true"></i>
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
                <form action="ExcluirConta.php" method="POST" id="formExcluirConta">
                    <button type="submit" class="btn-excluir">
                        <i class="fas fa-trash-alt"></i> Excluir Conta
                    </button>
                </form>
                <script>
                    document.getElementById('formExcluirConta').addEventListener('submit', function (event) {
                        if (!confirm('Tem certeza absoluta que deseja excluir sua conta permanentemente? Esta ação não pode ser desfeita.')) {
                            event.preventDefault();
                        }
                    });
                </script>
            </div>

        </div>
    </div>
</main>

<script src="../Javascript/ThemeToggle.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        inicializarThemeToggle('perfilThemeToggle');
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

    // Avatar clicável: abre o seletor de arquivo e pré-visualiza antes de salvar
    const avatar = document.getElementById('avatarPreview');
    const inputFoto = document.getElementById('foto');
    const conteudoOriginalAvatar = avatar.innerHTML;

    function abrirSeletorFoto() { inputFoto.click(); }
    avatar.addEventListener('click', abrirSeletorFoto);
    avatar.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); abrirSeletorFoto(); }
    });

    inputFoto.addEventListener('change', function () {
        const arquivo = this.files && this.files[0];
        if (!arquivo) {
            avatar.innerHTML = conteudoOriginalAvatar; // cancelou: reverte
            return;
        }
        const leitor = new FileReader();
        leitor.onload = (e) => {
            avatar.innerHTML = '<img src="' + e.target.result + '" alt="Prévia da nova foto">' +
                               '<span class="avatar-overlay"><i class="fas fa-camera"></i></span>';
        };
        leitor.readAsDataURL(arquivo);
    });

    // Força de senha + confirmação (só aparecem se o usuário digitar algo)
    const campoSenhaNova = document.getElementById('senha');
    const campoConfirmacao = document.getElementById('senha_confirmacao');
    const forcaSenha = document.getElementById('forcaSenha');
    const forcaTexto = document.getElementById('forcaSenhaTexto');
    const checkSenha = document.getElementById('checkSenha');

    campoSenhaNova.addEventListener('input', () => {
        const s = campoSenhaNova.value;
        forcaSenha.style.display = s.length > 0 ? 'block' : 'none';

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
        checarConfirmacao();
    });

    function checarConfirmacao() {
        const confere = campoSenhaNova.value.length > 0 && campoConfirmacao.value === campoSenhaNova.value;
        checkSenha.style.opacity = confere ? '1' : '0';
    }
    campoConfirmacao.addEventListener('input', checarConfirmacao);
</script>

</body>
</html>