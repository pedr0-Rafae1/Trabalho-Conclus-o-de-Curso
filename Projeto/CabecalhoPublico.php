<?php
$paginaNaRaiz = basename(dirname($_SERVER['SCRIPT_FILENAME'])) !== 'Projeto';
$prefixoProjeto = $paginaNaRaiz ? 'Projeto/' : '';
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
            <a href="<?= $prefixoProjeto ?>login.php" class="btn-entrar">Entrar</a>
            <a href="<?= $prefixoProjeto ?>CadastroUsuario.php" class="btn-cadastrar">Cadastrar</a>
        </div>
    </div>
</header>
