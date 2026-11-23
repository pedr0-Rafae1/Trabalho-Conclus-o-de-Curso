<?php
$paginaNaRaiz = basename(dirname($_SERVER['SCRIPT_FILENAME'])) !== 'Projeto';
$prefixoProjeto = $paginaNaRaiz ? 'Projeto/' : '';
$usuarioPublicoLogado = isset($_SESSION['id_usuario']);
$linkPainelPublico = $prefixoProjeto . ((int) ($_SESSION['admin'] ?? 0) === 1
    ? 'PainelAdmin.php'
    : (($_SESSION['tipo_usuario'] ?? '') === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php'));
?>
<header class="landing-header">
    <div class="landing-nav">
        <a href="<?= $paginaNaRaiz ? 'index.php' : '../index.php' ?>" class="landing-logo">
            <i class="fas fa-cow" aria-hidden="true"></i>
            <span>Pecuária em Rede</span>
        </a>
        <div class="landing-acoes">
            <button class="theme-toggle" type="button" id="themeToggle" title="Alternar modo claro e escuro" aria-label="Alternar modo claro e escuro">
                <i class="fas fa-moon" aria-hidden="true"></i>
            </button>
            <?php if ($usuarioPublicoLogado): ?>
                <a href="<?= htmlspecialchars($linkPainelPublico, ENT_QUOTES, 'UTF-8') ?>" class="btn-entrar">Acessar painel</a>
                <a href="<?= $prefixoProjeto ?>CanalDuvidas.php" class="btn-cadastrar">Canal de Dúvidas</a>
            <?php else: ?>
                <a href="<?= $prefixoProjeto ?>login.php" class="btn-entrar">Entrar</a>
                <a href="<?= $prefixoProjeto ?>CadastroUsuario.php" class="btn-cadastrar">Cadastrar</a>
            <?php endif; ?>
        </div>
    </div>
</header>
