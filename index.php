<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pecuária em Rede — Gestão inteligente para o seu rebanho</title>

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
    <link rel="stylesheet" href="CSS/Variaveis.css?v=2.2">
    <link rel="stylesheet" href="CSS/index.css?v=3.0">
</head>
<body>

    <header class="landing-header">
        <div class="landing-nav">
            <a href="index.php" class="landing-logo">
                <i class="fas fa-cow" aria-hidden="true"></i>
                <span>Pecuária em Rede</span>
            </a>
            <div class="landing-acoes">
                <button class="theme-toggle" type="button" id="themeToggle" title="Alternar modo claro e escuro" aria-label="Alternar modo claro e escuro">
                    <i class="fas fa-moon" aria-hidden="true"></i>
                </button>
                <a href="Projeto/login.php" class="btn-entrar">Entrar</a>
                <a href="Projeto/CadastroUsuario.php" class="btn-cadastrar">Cadastrar</a>
            </div>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-conteudo">
                <span class="hero-badge"><i class="fas fa-seedling"></i> Agreste Potiguar Conectado</span>
                <h1>Gestão inteligente<br>para o seu rebanho</h1>
                <p>Controle peso, vacinas, vendas e atendimentos do seu gado em um só lugar — direto do navegador, de graça.</p>
                <a href="Projeto/CadastroUsuario.php" class="btn-cta">Começar Agora <i class="fas fa-arrow-right"></i></a>
            </div>
        </section>

        <section class="recursos">
            <div class="recursos-container">
                <h2>Tudo o que você precisa em um só lugar</h2>
                <p class="recursos-sub">Ferramentas feitas sob medida para o dia a dia do produtor.</p>

                <div class="recursos-grid">
                    <div class="recurso-card">
                        <div class="recurso-icone"><i class="fas fa-weight"></i></div>
                        <h3>Controle de Pesagem</h3>
                        <p>Acompanhe o ganho de peso de cada animal e visualize a evolução do rebanho com clareza.</p>
                    </div>
                    <div class="recurso-card">
                        <div class="recurso-icone"><i class="fas fa-syringe"></i></div>
                        <h3>Histórico de Vacinas</h3>
                        <p>Mantenha o calendário sanitário em dia e receba lembretes de reforço automaticamente.</p>
                    </div>
                    <div class="recurso-card">
                        <div class="recurso-icone"><i class="fas fa-hand-holding-usd"></i></div>
                        <h3>Registro de Vendas</h3>
                        <p>Registre negociações e tenha o controle financeiro da comercialização do seu gado.</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="contexto">
            <div class="contexto-container">
                <span class="contexto-badge"><i class="fas fa-graduation-cap"></i> Projeto Acadêmico</span>
                <h2>Tecnologia que nasce no campo e cresce na universidade</h2>
                <p>O <strong>Pecuária em Rede</strong> é fruto de um Trabalho de Conclusão de Curso (TCC) do <strong>IFRN — Campus Nova Cruz</strong>, desenvolvido com um propósito claro: democratizar o acesso à tecnologia no campo. Unimos o conhecimento acadêmico à realidade do produtor rural do Agreste Potiguar, criando uma ferramenta gratuita, simples e feita para quem vive da pecuária.</p>
            </div>
        </section>

        <section class="faq">
            <div class="faq-container">
                <h2>Perguntas Frequentes</h2>
                <p class="faq-sub">Tire suas dúvidas antes de começar.</p>

                <div class="faq-lista">
                    <div class="faq-item">
                        <button class="faq-pergunta" type="button">
                            <span>O sistema é gratuito?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-resposta">
                            <p>Sim. Por ser um projeto acadêmico e social do IFRN, o Pecuária em Rede é totalmente gratuito para produtores e veterinários.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-pergunta" type="button">
                            <span>Meus dados estão seguros?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-resposta">
                            <p>Sim. Suas senhas são criptografadas e os dados do seu rebanho ficam vinculados somente à sua conta, com acesso restrito por login.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-pergunta" type="button">
                            <span>Preciso instalar algum aplicativo?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-resposta">
                            <p>Não. O sistema funciona direto no navegador do celular, tablet ou computador — sem downloads e sem complicação.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-pergunta" type="button">
                            <span>Como funciona o cadastro de veterinário?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-resposta">
                            <p>O veterinário se cadastra normalmente e passa por uma homologação da equipe. Após aprovado, ele pode responder dúvidas e registrar atendimentos no rebanho.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/Projeto/Rodape.php'; ?>

    <script src="Javascript/ThemeToggle.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            inicializarThemeToggle('themeToggle');

            // FAQ em sanfona (accordion)
            document.querySelectorAll('.faq-pergunta').forEach(function (botao) {
                botao.addEventListener('click', function () {
                    const item = this.closest('.faq-item');
                    const estavaAberto = item.classList.contains('aberto');
                    document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('aberto'));
                    if (!estavaAberto) item.classList.add('aberto');
                });
            });
        });
    </script>
</body>
</html>
