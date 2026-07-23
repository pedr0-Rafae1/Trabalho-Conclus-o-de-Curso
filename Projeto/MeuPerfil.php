<?php
include_once 'Sessao.php';
include 'Cabecalho.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Pecuária em Rede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/pecuaria.css">
    <link rel="stylesheet" href="../CSS/Formularios.css">
    
    <style>
        .perfil-card { border-radius: 15px; overflow: hidden; border: none; }
        .perfil-header { background-color: #2e7d32; color: white; padding: 30px; }
        .perfil-avatar { width: 100px; height: 100px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #2e7d32; font-size: 3rem; }
        .secao-titulo { color: #2e7d32; border-bottom: 2px solid #e8f5e9; padding-bottom: 8px; margin-bottom: 20px; font-weight: bold; }
    </style>
</head>
<body class="bg-light">

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <div class="card perfil-card shadow-lg">
                <div class="perfil-header text-center">
                    <div class="perfil-avatar shadow">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="mb-0"><?= $_SESSION['usuario_nome'] ?></h2>
                    <p class="opacity-75">Produtor Parceiro - Pecuária em Rede</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form action="ProcessarPerfil.php" method="POST">
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="secao-titulo"><i class="fas fa-id-badge me-2"></i>Informações de Conta</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nome Completo</label>
                                    <input type="text" name="nome" class="form-control" value="<?= $_SESSION['usuario_nome'] ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">E-mail Cadastrado</label>
                                    <input type="email" class="form-control bg-light" value="<?= $_SESSION['usuario_email'] ?? 'email@exemplo.com' ?>" readonly>
                                    <small class="text-muted">O e-mail não pode ser alterado por aqui.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="secao-titulo"><i class="fas fa-tractor me-2"></i>Dados da Fazenda</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nome da Propriedade</label>
                                    <input type="text" name="nome_fazenda" class="form-control" placeholder="Ex: Fazenda Santa Maria">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Cidade / Estado</label>
                                    <input type="text" name="localizacao" class="form-control" placeholder="Ex: Garanhuns - PE">
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow">
                                <i class="fas fa-save me-2"></i> Salvar Alterações
                            </button>
                            <div class="mt-3">
                                <a href="home.php" class="text-decoration-none text-muted">
                                    <i class="fas fa-arrow-left me-1"></i> Voltar para a Home
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div class="row mt-4 g-3 text-center">
                <div class="col-4">
                    <div class="card shadow-sm p-3 border-0 bg-white">
                        <i class="fas fa-cow text-success mb-2"></i>
                        <h6 class="mb-0 text-muted">Animais</h6>
                        <span class="fw-bold">Ver Rebanho</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card shadow-sm p-3 border-0 bg-white">
                        <i class="fas fa-syringe text-primary mb-2"></i>
                        <h6 class="mb-0 text-muted">Vacinas</h6>
                        <span class="fw-bold">Ver Histórico</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card shadow-sm p-3 border-0 bg-white">
                        <i class="fas fa-weight text-warning mb-2"></i>
                        <h6 class="mb-0 text-muted">Engorda</h6>
                        <span class="fw-bold">Ver Pesos</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>