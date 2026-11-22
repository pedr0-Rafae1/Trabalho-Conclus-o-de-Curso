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

// Prefixo de caminho: páginas dentro de /Projeto não precisam de prefixo;
// a index.php (raiz) precisa de "Projeto/" para alcançar as páginas internas.
$rodapeBase = (basename(dirname($_SERVER['SCRIPT_FILENAME'])) === 'Projeto') ? '' : 'Projeto/';
$rodapeCss = ($rodapeBase === '') ? '../CSS/Rodape.css' : 'CSS/Rodape.css';

$rodapeLogado = isset($_SESSION['id_usuario']);
$rodapeTipo   = $_SESSION['tipo_usuario'] ?? 'Pecuarista';

if ($rodapeLogado) {
    // O painel varia conforme o tipo de conta; os documentos abrem em nova guia.
    $linkInicial    = $rodapeBase . ($rodapeTipo === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php');
    $alvoTermos     = ' target="_blank" rel="noopener"';
} else {
    // Visitantes permanecem na mesma guia e voltam para a página inicial.
    $linkInicial    = ($rodapeBase === '') ? '../index.php' : 'index.php';
    $alvoTermos     = '';
}
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
        </nav>

        <nav class="site-footer__col" aria-label="Institucional">
            <h4 class="site-footer__titulo">Institucional</h4>
            <a href="<?= $rodapeBase ?>SobreNos.php">Sobre Nós</a>
            <a href="<?= $rodapeBase ?>TermosDeUso.php"<?= $alvoTermos ?>>Termos de Uso</a>
            <a href="<?= $rodapeBase ?>TermosDeUso.php#privacidade"<?= $alvoTermos ?>>Política de Privacidade</a>
        </nav>

        <nav class="site-footer__col" aria-label="Suporte">
            <h4 class="site-footer__titulo">Suporte</h4>
            <a href="<?= $rodapeBase ?>CanalDuvidas.php">Canal de Dúvidas</a>
            <div class="site-footer__social">
                <a href="mailto:contato@pecuariaemrede.com" title="E-mail" aria-label="E-mail"><i class="fas fa-envelope"></i></a>
                <a href="#" title="GitHub do projeto" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
        </nav>

    </div>

    <!-- ===== Barra inferior: copyright ===== -->
    <div class="site-footer__barra">
        <span>&copy; <?= date('Y') ?> Pecuária em Rede</span>
        <span>TCC - IFRN Campus Nova Cruz</span>
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
