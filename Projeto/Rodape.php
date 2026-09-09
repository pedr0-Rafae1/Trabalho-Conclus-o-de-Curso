<footer class="site-footer">
    <div class="site-footer__inner container">
        <div class="site-footer__brand">
            <a href="<?= ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php' ?>" class="site-footer__logo">
                <i class="fas fa-leaf" aria-hidden="true"></i>
                <span>Pecuária em Rede</span>
            </a>
            <p>Gestão prática para cuidar melhor do seu rebanho.</p>
        </div>

        <nav class="site-footer__links" aria-label="Links do rodapé">
            <a href="<?= ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php' ?>"><i class="fas fa-house" aria-hidden="true"></i> Meu painel</a>
            <a href="SobreNos.php"><i class="fas fa-circle-info" aria-hidden="true"></i> Sobre nós</a>
            <a href="CanalDuvidas.php"><i class="fas fa-comment-medical" aria-hidden="true"></i> Canal de dúvidas</a>
        </nav>

        <div class="site-footer__bottom">
            <span>&copy; <?= date('Y') ?> Pecuária em Rede</span>
            <span>Feito para o campo</span>
        </div>
    </div>
</footer>
