/*
 * RedeAnimada.js — Pecuária em Rede
 * Fundo animado de "rede" (pontos conectados por linhas) para o painel .auth-branding.
 * JS puro, sem dependências. Respeita prefers-reduced-motion e recalcula no resize.
 */
(function () {
    'use strict';

    const painel = document.querySelector('.auth-branding');
    if (!painel) return;

    const reduzirMovimento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const canvas = document.createElement('canvas');
    canvas.className = 'rede-animada-canvas';
    canvas.setAttribute('aria-hidden', 'true');
    painel.prepend(canvas);

    const ctx = canvas.getContext('2d');
    let pontos = [];
    let largura = 0;
    let altura = 0;
    let rodando = false;

    const VELOCIDADE = 0.18;          // bem lento, para não distrair
    const DISTANCIA_CONEXAO = 120;    // distância máxima para ligar dois pontos
    const COR_PONTO = 'rgba(255, 255, 255, 0.55)';
    const COR_LINHA = 'rgba(255, 255, 255, 0.14)';

    function dimensionar() {
        const rect = painel.getBoundingClientRect();
        largura = rect.width;
        altura = rect.height;
        canvas.width = largura;
        canvas.height = altura;

        // Quantidade de pontos proporcional à área (com limites)
        const total = Math.max(18, Math.min(60, Math.floor((largura * altura) / 14000)));
        pontos = [];
        for (let i = 0; i < total; i++) {
            pontos.push({
                x: Math.random() * largura,
                y: Math.random() * altura,
                vx: (Math.random() - 0.5) * VELOCIDADE * 2,
                vy: (Math.random() - 0.5) * VELOCIDADE * 2,
                r: 1.4 + Math.random() * 1.6
            });
        }
    }

    function desenhar() {
        ctx.clearRect(0, 0, largura, altura);

        // Linhas entre pontos próximos
        for (let i = 0; i < pontos.length; i++) {
            for (let j = i + 1; j < pontos.length; j++) {
                const dx = pontos[i].x - pontos[j].x;
                const dy = pontos[i].y - pontos[j].y;
                const dist = Math.hypot(dx, dy);
                if (dist < DISTANCIA_CONEXAO) {
                    ctx.globalAlpha = 1 - dist / DISTANCIA_CONEXAO;
                    ctx.strokeStyle = COR_LINHA;
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(pontos[i].x, pontos[i].y);
                    ctx.lineTo(pontos[j].x, pontos[j].y);
                    ctx.stroke();
                }
            }
        }

        // Pontos
        ctx.globalAlpha = 1;
        ctx.fillStyle = COR_PONTO;
        for (const p of pontos) {
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function passo() {
        for (const p of pontos) {
            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0 || p.x > largura) p.vx *= -1;
            if (p.y < 0 || p.y > altura) p.vy *= -1;
        }
        desenhar();
        if (rodando) requestAnimationFrame(passo);
    }

    dimensionar();

    if (reduzirMovimento) {
        // Acessibilidade: desenha um quadro estático, sem animação
        desenhar();
    } else {
        rodando = true;
        requestAnimationFrame(passo);
    }

    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            dimensionar();
            if (reduzirMovimento) desenhar();
        }, 150);
    });
})();
