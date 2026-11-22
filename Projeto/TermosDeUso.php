<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso - Pecuária em Rede</title>
    <script>
        (function () {
            const tema = localStorage.getItem('rpp-tema') === 'dark' ? 'dark' : 'light';
            document.documentElement.dataset.theme = tema;
            document.documentElement.style.backgroundColor = tema === 'dark' ? '#111b16' : '#f4f8f4';
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Variaveis.css?v=2.2">
    <style>
        /* ===== Página de leitura (Termos de Uso) ===== */
        body { margin: 0; font-family: var(--fonte-principal); background: var(--bg-pagina); color: var(--texto-principal); }

        .termos-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--verde-escuro);
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
        }

        .termos-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 800px;
            margin: 0 auto;
            padding: 16px 24px;
        }

        .termos-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 700;
            text-decoration: none;
        }

        .termos-logo i { color: #a5d6a7; }

        .termos-voltar {
            padding: 8px 16px;
            color: #fff;
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,.4);
            border-radius: 8px;
            transition: background .2s ease;
        }

        .termos-voltar:hover { background: rgba(255,255,255,.12); }

        .termos-container {
            max-width: 800px;
            margin: 40px auto 60px;
            padding: 48px 44px;
            background: var(--bg-card);
            border: 1px solid var(--borda);
            border-radius: 14px;
            box-shadow: var(--sombra-suave);
        }

        .termos-container h1 {
            margin: 0 0 6px;
            color: var(--texto-principal);
            font-size: 1.8rem;
        }

        .termos-atualizado {
            margin: 0 0 30px;
            color: var(--texto-secundario);
            font-size: .82rem;
        }

        .termos-container h2 {
            margin: 30px 0 10px;
            color: var(--verde-escuro);
            font-size: 1.15rem;
        }

        html[data-theme="dark"] .termos-container h2 { color: #8fd6a8; }

        .termos-container p,
        .termos-container li {
            color: var(--texto-secundario);
            font-size: .95rem;
            line-height: 1.8;
        }

        .termos-container ul { padding-left: 22px; }

        @media (max-width: 640px) {
            .termos-container { margin: 20px 14px 40px; padding: 30px 22px; }
        }
    </style>
</head>
<body>

    <header class="termos-header">
        <div class="termos-header__inner">
            <a href="../index.php" class="termos-logo">
                <i class="fas fa-cow" aria-hidden="true"></i>
                <span>Pecuária em Rede</span>
            </a>
            <a href="../index.php" class="termos-voltar"><i class="fas fa-arrow-left"></i> Voltar para Home</a>
        </div>
    </header>

    <main class="termos-container">
        <h1>Termos de Uso</h1>
        <p class="termos-atualizado">Última atualização: setembro de 2026</p>

        <h2>1. Sobre o sistema</h2>
        <p>O Pecuária em Rede é uma plataforma web de gestão pecuária desenvolvida como Trabalho de Conclusão de Curso (TCC) no IFRN — Campus Nova Cruz. O sistema permite o registro e acompanhamento de animais, pesagens, vacinações, vendas e atendimentos veterinários.</p>

        <h2>2. Cadastro e responsabilidades do usuário</h2>
        <ul>
            <li>O usuário deve fornecer informações verdadeiras no cadastro (nome, e-mail e idade).</li>
            <li>A senha é pessoal e intransferível. Não compartilhe suas credenciais de acesso.</li>
            <li>O usuário é responsável pelos dados que registra sobre seu rebanho.</li>
        </ul>

        <h2>3. Conta de veterinário</h2>
        <p>Usuários cadastrados como Veterinário passam por homologação manual da equipe antes de acessar as ferramentas profissionais (responder dúvidas e registrar atendimentos). A homologação pode ser negada ou revogada em caso de uso indevido.</p>

        <h2 id="privacidade">4. Privacidade e dados</h2>
        <p>Os dados registrados ficam vinculados exclusivamente à conta do usuário. Senhas são armazenadas com criptografia (hash) e nunca em texto puro. Não compartilhamos informações com terceiros.</p>

        <h2>5. Natureza do projeto</h2>
        <p>Por se tratar de um projeto acadêmico, o sistema é oferecido gratuitamente, sem garantias de disponibilidade contínua. As informações registradas não substituem a orientação presencial de um médico veterinário.</p>

        <h2>6. Exclusão de conta</h2>
        <p>O usuário pode excluir sua conta a qualquer momento pela página "Meu Perfil". A exclusão é permanente e remove todos os dados vinculados (animais, registros, dúvidas e atendimentos).</p>

        <h2>7. Contato</h2>
        <p>Em caso de dúvidas sobre estes termos, utilize o Canal de Dúvidas dentro da plataforma ou entre em contato pelo e-mail informado no rodapé do sistema.</p>
    </main>

    <?php include 'Rodape.php'; ?>

</body>
</html>
