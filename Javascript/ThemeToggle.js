/**
 * ThemeToggle.js — Pecuária em Rede
 * Lógica compartilhada de alternância de tema (claro/escuro).
 * Uso: <script src=".../ThemeToggle.js" defer></script>
 *      inicializarThemeToggle('idDoBotao');
 */
function inicializarThemeToggle(idBotao) {
    const botao = document.getElementById(idBotao);
    if (!botao) return;

    function atualizarBotao(tema) {
        const escuro = tema === 'dark';
        botao.innerHTML = escuro
            ? '<i class="fas fa-sun" aria-hidden="true"></i>'
            : '<i class="fas fa-moon" aria-hidden="true"></i>';
        botao.title = escuro ? 'Ativar modo claro' : 'Ativar modo escuro';
        botao.setAttribute('aria-label', botao.title);
    }

    function aplicarTema(tema) {
        const temaNormalizado = tema === 'dark' ? 'dark' : 'light';
        document.documentElement.dataset.theme = temaNormalizado;
        document.body.dataset.theme = temaNormalizado;
        localStorage.setItem('rpp-tema', temaNormalizado);
        atualizarBotao(temaNormalizado);
    }

    botao.addEventListener('click', function () {
        aplicarTema(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
    });

    atualizarBotao(document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');
}
