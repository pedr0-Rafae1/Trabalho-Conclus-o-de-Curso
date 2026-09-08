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
    <link rel="stylesheet" href="../CSS/Variaveis.css?v = 1.2">
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
            padding: 5px 0;
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
            padding: 10px 20px;
            display: block;
            font-weight: 500;
            transition: 0.3s;
        }

        .nav-links > li > a:hover {
            background-color: rgba(255,255,255,0.1);
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
</head>
<body>

<header>
    <div class="header-container container">
        <div class="esquerda">
            <a href="home.php" class="logo-link d-flex align-items-center">
                <i class="fas fa-leaf me-2"></i> <h1>Pecuária em Rede</h1>
            </a>
        </div>

        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
        
        <div class="direita">
            <?php if(isset($_SESSION['usuario_nome'])): ?>
                <div class="dropdown" style="position: relative;">
                    <button class="btn-usuario" type="button" id="userMenuBtn" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4); color: white; padding: 5px 15px; border-radius: 5px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        
                        <?php if (!empty($_SESSION['foto_perfil']) && file_exists(__DIR__ . '/../imagem/' . $_SESSION['foto_perfil'])): ?>
                            <img src="../imagem/<?= htmlspecialchars($_SESSION['foto_perfil']) ?>" style="width: 26px; height: 26px; border-radius: 50%; object-fit: cover;" alt="Perfil">
                        <?php else: ?>
                            <i class="fas fa-user-circle"></i>
                        <?php endif; ?>

                        <span><?= $_SESSION['usuario_nome'] ?></span> 
                        <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i>
                    </button>

                    <ul id="userDropdownMenu" style="display: none; position: absolute; right: 0; top: 100%; background: white; list-style: none; padding: 10px 0; margin-top: 5px; min-width: 160px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); border-radius: 8px; z-index: 1050;">
                        <li><a href="MeuPerfil.php" style="color: #333; font-weight: bold; padding: 8px 20px; display: block; text-decoration: none;">Meu Perfil</a></li>
                        <li><hr style="margin: 5px 0; border-top: 1px solid #ddd;"></li>
                        <li><a href="Sair.php" style="color: #dc3545; font-weight: 500; padding: 8px 20px; display: block; text-decoration: none;"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-light btn-sm fw-bold">Entrar</a>
            <?php endif; ?>
        </div>
    </div>

    <nav class="menu-principal">
        <ul class="nav-links" id="navLinks">
            <li><a href="home.php">Início</a></li>
            <li><a href="SobreNos.php">Sobre nós</a></li>
            <li><a href="CanalDuvidas.php"><i class="fas fa-comment-medical me-1"></i> Canal de Dúvidas</a></li>

            <?php if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario'): ?>

                <!-- ===== Menu exclusivo do Veterinário ===== -->
                <li><a href="RegistrarAtendimento.php"><i class="fas fa-stethoscope me-1"></i> Registrar Atendimento</a></li>
                <li><a href="HistoricoAtendimento.php"><i class="fas fa-notes-medical me-1"></i> Meus Atendimentos</a></li>

            <?php else: ?>

                <!-- ===== Menu do Pecuarista ===== -->
                <li class="dropdown-servicos">
                    <a href="#" class="toggle-submenu">Serviços <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i></a>
                    <ul class="submenu-branco">
                        <li><a href="CadastroAnimal.php"><i class="fas fa-plus me-2"></i> Cadastrar Animal</a></li>
                        <li><a href="RegistrarVacinacao.php"><i class="fas fa-syringe me-2"></i> Registrar Vacina</a></li>
                        <li><a href="ControlePeso.php"><i class="fas fa-weight me-2"></i> Controle de Peso</a></li>
                        <li><a href="RegistrarVenda.php"><i class="fas fa-hand-holding-usd me-2"></i> Registrar Venda</a></li>
                    </ul>
                </li>

                <li class="dropdown-servicos">
                    <a href="#" class="toggle-submenu">Listas <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i></a>
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

        menuToggle.addEventListener('click', function () {
            const aberto = navLinks.classList.toggle('mostrar');
            menuToggle.setAttribute('aria-expanded', aberto);
        });

        document.querySelectorAll('.toggle-submenu').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (window.innerWidth <= 768) {
                    event.preventDefault();
                    this.parentElement.classList.toggle('aberto');
                }
            });
        });
    })();

    document.addEventListener('DOMContentLoaded', function() {
        const userBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userDropdownMenu');

        if (userBtn && userMenu) {
            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function() {
                userMenu.style.display = 'none';
            });
        }
    });
</script>
</body>
</html>