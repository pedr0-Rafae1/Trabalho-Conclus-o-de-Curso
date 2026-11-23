<?php
/**
 * Rodape.php — "Fat Footer" institucional do Pecuária em Rede
 * Incluído pelas páginas públicas e internas para manter a navegação consistente.
 * Detecta automaticamente o diretório da página que o incluiu para
 * montar os caminhos relativos corretos.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Links e folha de estilos partem de diretórios diferentes em cada página.
$paginaNoProjeto = basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'Projeto';
$rodapeBase = $paginaNoProjeto ? '' : 'Projeto/';
$rodapeCss = $paginaNoProjeto ? '../CSS/Rodape.css?v=1.1' : 'CSS/Rodape.css?v=1.1';

$rodapeLogado = isset($_SESSION['id_usuario']);
$rodapeTipo   = $_SESSION['tipo_usuario'] ?? 'Pecuarista';

if ($rodapeLogado) {
    // O painel varia conforme o tipo de conta; os documentos abrem em nova guia.
    $linkInicial    = $rodapeBase . ($rodapeTipo === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php');
    $linkPainel     = $linkInicial;
    $linkDuvidas    = $rodapeBase . 'CanalDuvidas.php';
} else {
    // Visitantes precisam autenticar-se para acessar áreas restritas.
    $linkInicial    = ($rodapeBase === '') ? '../index.php' : 'index.php';
    $linkPainel     = $rodapeBase . 'login.php?motivo=acesso_restrito';
    $linkDuvidas    = $linkPainel;
}
$alvoInformativo = ' target="_blank" rel="noopener noreferrer"';
?>
<link rel="stylesheet" href="<?= htmlspecialchars($rodapeCss, ENT_QUOTES, 'UTF-8') ?>">
<footer class="site-footer">

    <!-- ===== Área superior: grid de 4 colunas ===== -->
    <div class="site-footer__grid">

        <div class="site-footer__col site-footer__marca">
            <span class="site-footer__logo"><i class="fas fa-cow" aria-hidden="true"></i> Pecuária em Rede</span>
            <p class="site-footer__slogan">Gestão inteligente para seu rebanho.</p>
        </div>

        <nav class="site-footer__col" aria-label="Navegação">
            <h4 class="site-footer__titulo">Navegação</h4>
            <a href="<?= $linkInicial ?>">Início</a>
            <a href="<?= htmlspecialchars($linkPainel, ENT_QUOTES, 'UTF-8') ?>">Acessar painel</a>
        </nav>

        <nav class="site-footer__col" aria-label="Institucional">
            <h4 class="site-footer__titulo">Institucional</h4>
            <a href="<?= $rodapeBase ?>SobreNos.php"<?= $alvoInformativo ?>>Sobre Nós</a>
            <a href="<?= $rodapeBase ?>TermosDeUso.php"<?= $alvoInformativo ?>>Termos de Uso</a>
            <a href="<?= $rodapeBase ?>TermosDeUso.php#privacidade"<?= $alvoInformativo ?>>Política de Privacidade</a>
        </nav>

        <nav class="site-footer__col" aria-label="Suporte">
            <h4 class="site-footer__titulo">Suporte</h4>
            <a href="<?= htmlspecialchars($linkDuvidas, ENT_QUOTES, 'UTF-8') ?>">Canal de Dúvidas</a>
        </nav>

    </div>

    <!-- ===== Barra inferior: copyright ===== -->
    <div class="site-footer__barra">
        <span>&copy; 2026 Pecuária em Rede TCC - IFRN Campus Nova Cruz</span>
        <div class="site-footer__social" aria-label="Redes e contato">
            <a href="mailto:contato@pecuariaemrede.com" title="E-mail" aria-label="E-mail"><i class="fas fa-envelope" aria-hidden="true"></i></a>
            <a href="https://github.com/pedr0-Rafae1/Trabalho-Conclus-o-de-Curso" target="_blank" rel="noopener noreferrer" title="GitHub do projeto" aria-label="GitHub"><i class="fab fa-github" aria-hidden="true"></i></a>
        </div>
    </div>

</footer>

<script>
// Fecha qualquer banner de alerta padronizado ao clicar no "X"
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.fechar-alerta');
    if (!btn) return;
    const alerta = btn.closest('.alerta-notificacao, .alert');
    if (alerta) alerta.remove();
});
</script>
