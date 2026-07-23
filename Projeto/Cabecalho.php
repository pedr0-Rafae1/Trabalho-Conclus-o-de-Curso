<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
        
        <div class="direita">
            <?php if(isset($_SESSION['usuario_nome'])): ?>
                <div class="dropdown">
                    <button class="btn-usuario dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> <?= $_SESSION['usuario_nome'] ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="MeuPerfil.php"><i class="fas fa-id-card me-2 text-success"></i> Meu Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="Sair.php"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-light btn-sm fw-bold">Entrar</a>
            <?php endif; ?>
        </div>
    </div>

    <nav class="menu-principal">
        <ul class="nav-links">
            <li><a href="home.php">Início</a></li>
            <li><a href="SobreNos.php">Sobre nós</a></li>
            
            <li class="dropdown-servicos">
                <a href="#">Serviços <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i></a>
                <ul class="submenu-branco">
                    <li><a href="CadastroAnimal.php"><i class="fas fa-plus me-2"></i> Cadastrar Animal</a></li>
                    <li><a href="RegistrarVacinacao.php"><i class="fas fa-syringe me-2"></i> Registrar Vacina</a></li>
                    <li><a href="ControlePeso.php"><i class="fas fa-weight me-2"></i> Controle de Peso</a></li>
                </ul>
            </li>

            <li class="dropdown-servicos">
                <a href="#">Listas <i class="fas fa-chevron-down ms-1" style="font-size: 0.8rem;"></i></a>
                <ul class="submenu-branco">
                    <li><a href="ListaAnimal.php"><i class="fas fa-list me-2"></i> Lista de Animais</a></li>
                    <li><a href="ListaRegistroPeso.php"><i class="fas fa-chart-line me-2"></i> Histórico de Pesos</a></li>
                    <li><a href="ListaRegistroVacinacao.php"><i class="fas fa-notes-medical me-2"></i> Histórico de Vacinas</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>