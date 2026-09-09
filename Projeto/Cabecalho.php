<?php
$paginaAtual = basename($_SERVER['PHP_SELF']);
$ehVeterinario = ($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario';
$ehPaginaLista = preg_match('/^(Lista|Historico|CanalDuvidas|EvolucaoPeso)/', $paginaAtual) === 1;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Variaveis.css?v=2.0">
    <script>
        (function () {
            const temaSalvo = localStorage.getItem('rpp-tema');
            const tema = temaSalvo === 'dark' ? 'dark' : 'light';
            document.documentElement.dataset.theme = tema;
        })();
    </script>
    <style>
        header {
            background-color: #2e7d32; 
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            min-height: 72px;
        }

        .logo-link {
            text-decoration: none;
            color: white;
        }

        .logo-link h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .menu-principal {
            background-color: #1b5e20;
            padding: 0;
            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .nav-links {
            display: flex;
            justify-content: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links > li {
            position: relative;
        }

        .nav-links > li > a {
            color: white;
            text-decoration: none;
            padding: 13px 18px;
            display: block;
            font-weight: 500;
            transition: 0.3s;
            border-bottom: 3px solid transparent;
        }

        .nav-links > li > a:hover {
            background-color: rgba(255,255,255,0.1);
            border-bottom-color: #a5d6a7;
        }

        .nav-links > li > a[aria-current="page"] {
            background-color: rgba(255,255,255,0.12);
            border-bottom-color: #ffffff;
        }

        .dropdown-servicos:hover .submenu-branco {
            display: block;
        }

        .submenu-branco {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #ffffff;
            min-width: 220px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 0 0 8px 8px;
            padding: 10px 0;
            list-style: none;
            z-index: 1050;
        }

        .submenu-branco li a {
            color: #333 !important;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .submenu-branco li a:hover {
            background-color: #f1f8f1;
            color: #2e7d32 !important;
        }

        .btn-usuario {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            transition: 0.3s;
            font-weight: 600; 
        }

        .btn-usuario:hover {
            background: white;
            color: #2e7d32;
        }

        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
            margin-top: 5px;
        }

        .dropdown-item {
            color: #333 !important;
            font-weight: 500 !important;
            text-decoration: none;
            padding: 8px 20px;
            display: block;
        }

        .dropdown-item:hover {
            background-color: #f1f8f1;
            color: #2e7d32 !important;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.6rem;
            cursor: pointer;
            padding: 8px 10px;
            border-radius: 6px;
        }

        .menu-toggle:hover,
        .menu-toggle:focus-visible {
            background: rgba(255,255,255,0.14);
            outline: none;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
            }

            .nav-links.mostrar {
                display: flex;
            }

            .nav-links > li {
                width: 100%;
                text-align: left;
            }

            .nav-links > li > a {
                padding: 12px 24px;
            }

            .dropdown-servicos:hover .submenu-branco {
                display: none;
            }

            .dropdown-servicos.aberto .submenu-branco {
                display: block;
            }

            .submenu-branco {
                position: static;
                box-shadow: none;
                border-radius: 0;
                background-color: #eafbea;
            }
        }
    </style>
    <link rel="stylesheet" href="../CSS/Cabecalho.css?v=1.0">
    <link rel="stylesheet" href="../CSS/Sidebar.css?v=1.0">
</head>
<body class="<?= $ehVeterinario ? 'perfil-veterinario' : 'perfil-pecuarista' ?><?= $ehPaginaLista ? ' pagina-lista' : '' ?>">

<?php if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') !== 'Veterinario'): ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar-pecuarista" id="sidebarPecuarista" aria-label="Menu do pecuarista">
        <div class="sidebar-heading">
            <span class="sidebar-eyebrow">Área do produtor</span>
            <strong>Meu manejo</strong>
            <button class="sidebar-close" id="sidebarClose" type="button" aria-label="Fechar menu">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="Pecuarista.php" class="sidebar-link <?= $paginaAtual === 'Pecuarista.php' ? 'ativo' : '' ?>">
                <i class="fas fa-chart-pie" aria-hidden="true"></i><span>Meu painel</span>
            </a>
            <span class="sidebar-section">Manejo</span>
            <a href="CadastroAnimal.php" class="sidebar-link"><i class="fas fa-cow" aria-hidden="true"></i><span>Cadastrar animal</span></a>
            <a href="ControlePeso.php" class="sidebar-link"><i class="fas fa-weight" aria-hidden="true"></i><span>Controle de peso</span></a>
            <a href="RegistrarVacinacao.php" class="sidebar-link"><i class="fas fa-syringe" aria-hidden="true"></i><span>Registrar vacinação</span></a>
            <a href="RegistrarVenda.php" class="sidebar-link"><i class="fas fa-hand-holding-dollar" aria-hidden="true"></i><span>Registrar venda</span></a>
            <span class="sidebar-section">Acompanhamento</span>
            <a href="ListaAnimal.php" class="sidebar-link"><i class="fas fa-list" aria-hidden="true"></i><span>Meu rebanho</span></a>
            <a href="ListaRegistroPeso.php" class="sidebar-link"><i class="fas fa-chart-line" aria-hidden="true"></i><span>Histórico de pesos</span></a>
            <a href="ListaRegistroVacinacao.php" class="sidebar-link"><i class="fas fa-notes-medical" aria-hidden="true"></i><span>Histórico de vacinas</span></a>
            <a href="HistoricoVenda.php" class="sidebar-link"><i class="fas fa-receipt" aria-hidden="true"></i><span>Histórico de vendas</span></a>
            <a href="HistoricoAtendimento.php" class="sidebar-link"><i class="fas fa-stethoscope" aria-hidden="true"></i><span>Atendimentos</span></a>
            <span class="sidebar-section">Suporte</span>
            <a href="CanalDuvidas.php" class="sidebar-link"><i class="fas fa-comment-medical" aria-hidden="true"></i><span>Falar com veterinário</span></a>
            <a href="SobreNos.php" class="sidebar-link"><i class="fas fa-circle-info" aria-hidden="true"></i><span>Sobre o projeto</span></a>
        </nav>
    </aside>
<?php else: ?>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <aside class="sidebar-veterinario" id="sidebarVeterinario" aria-label="Menu profissional do veterinário">
        <div class="sidebar-heading">
            <span class="sidebar-eyebrow">Área profissional</span>
            <strong>Prática clínica</strong>
            <button class="sidebar-close" id="sidebarClose" type="button" aria-label="Fechar menu">
                <i class="fas fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <nav class="sidebar-nav">
            <a href="Veterinario.php" class="sidebar-link <?= $paginaAtual === 'Veterinario.php' ? 'ativo' : '' ?>">
                <i class="fas fa-chart-line" aria-hidden="true"></i><span>Painel profissional</span>
            </a>
            <span class="sidebar-section">Atuação</span>
            <a href="RegistrarAtendimento.php" class="sidebar-link"><i class="fas fa-stethoscope" aria-hidden="true"></i><span>Registrar atendimento</span></a>
            <a href="HistoricoAtendimento.php" class="sidebar-link"><i class="fas fa-notes-medical" aria-hidden="true"></i><span>Meus atendimentos</span></a>
            <a href="CanalDuvidas.php" class="sidebar-link"><i class="fas fa-comments" aria-hidden="true"></i><span>Canal de dúvidas</span></a>
            <span class="sidebar-section">Conta</span>
            <a href="MeuPerfil.php" class="sidebar-link"><i class="fas fa-user-circle" aria-hidden="true"></i><span>Meu perfil</span></a>
            <a href="SobreNos.php" class="sidebar-link"><i class="fas fa-circle-info" aria-hidden="true"></i><span>Sobre o projeto</span></a>
        </nav>
    </aside>
<?php endif; ?>

<header class="site-header">
    <div class="header-container container">
        <div class="esquerda">
            <a href="<?= ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php' ?>" class="logo-link d-flex align-items-center">
                <i class="fas fa-leaf me-2"></i> <h1>Pecuária em Rede</h1>
            </a>
        </div>

        <button class="menu-toggle" id="menuToggle" type="button" aria-label="Abrir menu" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="direita">
            <button class="theme-toggle" type="button" id="themeToggle" title="Alternar modo claro e escuro" aria-label="Alternar modo claro e escuro">
                <i class="fas fa-moon" aria-hidden="true"></i>
            </button>
            <?php if(isset($_SESSION['usuario_nome'])): ?>
                <div class="dropdown" style="position: relative;">
                    <button class="btn-usuario" type="button" id="userMenuBtn" aria-expanded="false">
                        
                        <?php if (!empty($_SESSION['foto_perfil']) && file_exists(__DIR__ . '/../imagem/' . $_SESSION['foto_perfil'])): ?>
                            <img src="../imagem/<?= htmlspecialchars($_SESSION['foto_perfil']) ?>" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover;" alt="Perfil">
                        <?php else: ?>
                            <i class="fas fa-user-circle"></i>
                        <?php endif; ?>

                        <span><?= htmlspecialchars($_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8') ?></span>
                        <i class="fas fa-chevron-down ms-1" aria-hidden="true"></i>
                    </button>

                    <ul id="userDropdownMenu" class="user-dropdown">
                        <li><a href="MeuPerfil.php"><i class="fas fa-user-circle"></i> Meu Perfil</a></li>
                        <li><hr></li>
                        <li><a href="Sair.php" class="sair"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-light btn-sm fw-bold">Entrar</a>
            <?php endif; ?>
        </div>
    </div>

    <nav class="menu-principal" aria-label="Navegação principal">
        <ul class="nav-links" id="navLinks">
            <?php $painelUsuario = ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario' ? 'Veterinario.php' : 'Pecuarista.php'; ?>
            <?php if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario'): ?>
                <li><a href="CanalDuvidas.php" <?= $paginaAtual === 'CanalDuvidas.php' ? 'aria-current="page"' : '' ?>><i class="fas fa-comment-medical me-1"></i> Canal de dúvidas</a></li>
            <?php else: ?>
                <li><a href="Pecuarista.php" aria-current="page"><i class="fas fa-chart-pie me-1"></i> Meu painel</a></li>
            <?php endif; ?>

            <?php if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario'): ?>

                <!-- ===== Menu exclusivo do Veterinário ===== -->
                <li><a href="Veterinario.php" <?= $paginaAtual === 'Veterinario.php' ? 'aria-current="page"' : '' ?>><i class="fas fa-chart-line me-1"></i> Painel profissional</a></li>
                <li><a href="RegistrarAtendimento.php"><i class="fas fa-stethoscope me-1"></i> Registrar Atendimento</a></li>
                <li><a href="HistoricoAtendimento.php"><i class="fas fa-notes-medical me-1"></i> Meus Atendimentos</a></li>

            <?php else: ?>

                <!-- ===== Menu do Pecuarista ===== -->
                <li><a href="Pecuarista.php" <?= $paginaAtual === 'Pecuarista.php' ? 'aria-current="page"' : '' ?>><i class="fas fa-chart-pie me-1"></i> Meu painel</a></li>
                <li class="dropdown-servicos">
                    <a href="#" class="toggle-submenu" aria-haspopup="true" aria-expanded="false">Manejo <i class="fas fa-chevron-down ms-1" aria-hidden="true"></i></a>
                    <ul class="submenu-branco">
                        <li><a href="CadastroAnimal.php"><i class="fas fa-plus me-2"></i> Cadastrar Animal</a></li>
                        <li><a href="RegistrarVacinacao.php"><i class="fas fa-syringe me-2"></i> Registrar Vacina</a></li>
                        <li><a href="ControlePeso.php"><i class="fas fa-weight me-2"></i> Controle de Peso</a></li>
                        <li><a href="RegistrarVenda.php"><i class="fas fa-hand-holding-usd me-2"></i> Registrar Venda</a></li>
                    </ul>
                </li>

                <li class="dropdown-servicos">
                    <a href="#" class="toggle-submenu" aria-haspopup="true" aria-expanded="false">Histórico <i class="fas fa-chevron-down ms-1" aria-hidden="true"></i></a>
                    <ul class="submenu-branco">
                        <li><a href="ListaAnimal.php"><i class="fas fa-list me-2"></i> Lista de Animais</a></li>
                        <li><a href="ListaRegistroPeso.php"><i class="fas fa-chart-line me-2"></i> Histórico de Pesos</a></li>
                        <li><a href="EvolucaoPeso.php"><i class="fas fa-chart-line me-2"></i>Evolução do Peso</a></li>
                        <li><a href="ListaRegistroVacinacao.php"><i class="fas fa-notes-medical me-2"></i> Histórico de Vacinas</a></li>
                        <li><a href="HistoricoVenda.php"><i class="fas fa-hand-holding-usd me-2"></i> Histórico de Vendas</a></li>
                        <li><a href="HistoricoAtendimento.php"><i class="fas fa-stethoscope me-2"></i> Atendimentos</a></li>
                    </ul>
                </li>

            <?php endif; ?>
        </ul>
    </nav>
</header>

<script>
    (function () {
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', function () {
            if (document.body.classList.contains('perfil-pecuarista') || document.body.classList.contains('perfil-veterinario')) {
                const aberto = document.body.classList.toggle('sidebar-aberto');
                menuToggle.setAttribute('aria-expanded', aberto);
                return;
            }

            const aberto = navLinks.classList.toggle('mostrar');
            menuToggle.setAttribute('aria-expanded', aberto);
        });

        [sidebarClose, sidebarOverlay].forEach(function (elemento) {
            if (elemento) {
                elemento.addEventListener('click', function () {
                    document.body.classList.remove('sidebar-aberto');
                    menuToggle.setAttribute('aria-expanded', 'false');
                });
            }
        });

        document.querySelectorAll('.toggle-submenu').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (window.innerWidth <= 768) {
                    event.preventDefault();
                    const aberto = this.parentElement.classList.toggle('aberto');
                    this.setAttribute('aria-expanded', aberto);
                }
            });
        });
    })();

    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('themeToggle');

        function atualizarBotaoTema(tema) {
            if (!themeToggle) {
                return;
            }

            const escuro = tema === 'dark';
            themeToggle.innerHTML = escuro
                ? '<i class="fas fa-sun" aria-hidden="true"></i>'
                : '<i class="fas fa-moon" aria-hidden="true"></i>';
            themeToggle.title = escuro ? 'Ativar modo claro' : 'Ativar modo escuro';
            themeToggle.setAttribute('aria-label', themeToggle.title);
        }

        function aplicarTema(tema) {
            const temaNormalizado = tema === 'dark' ? 'dark' : 'light';
            document.documentElement.dataset.theme = temaNormalizado;
            document.body.dataset.theme = temaNormalizado;
            localStorage.setItem('rpp-tema', temaNormalizado);
            atualizarBotaoTema(temaNormalizado);
        }

        const temaAtual = document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';
        document.body.dataset.theme = temaAtual;
        atualizarBotaoTema(temaAtual);

        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                aplicarTema(document.body.dataset.theme === 'dark' ? 'light' : 'dark');
            });
        }

        const userBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userDropdownMenu');

        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const aberto = userMenu.style.display === 'block';
                userMenu.style.display = aberto ? 'none' : 'block';
                userBtn.setAttribute('aria-expanded', !aberto);
            });

            document.addEventListener('click', function() {
                userMenu.style.display = 'none';
                userBtn.setAttribute('aria-expanded', 'false');
            });
        }
    });
</script>