<?php
include_once 'Sessao.php';
include_once 'Cabecalho.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Dao/RegistroVacinacaoDao.php';
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';
require_once __DIR__ . '/../app/Dao/AtendimentoDao.php';

$ehVeterinario = ($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario';
$ehHomologado = $ehVeterinario && (int) ($_SESSION['homologado'] ?? 0) === 1;

if ($ehVeterinario) {
    $duvidaDao = new DuvidaDao();
    $atendimentoDao = new AtendimentoDao();
    $totalPendentes = $ehHomologado ? count($duvidaDao->ListarPendentes()) : 0;
    $totalAtendimentos = count($atendimentoDao->ListarPorVeterinario($_SESSION['id_usuario']));
} else {
    $animalDao = new AnimalDao();
    $vacinacaoDao = new RegistroVacinacaoDao();
    $pesoDao = new RegistroPesoDao();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal - Pecuária em Rede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/pecuaria.css?v=2.0">
    <link rel="shortcut icon" href="../imagem/Logo.png" type="image/png">
    
    <style>
        .dropdown-menu { list-style: none !important; margin: 0; padding: 0; }
        .dropdown-item { padding: 10px 20px !important; }
        .header-container { display: flex; justify-content: space-between; align-items: center; }
        .card-dashboard { border: none; border-radius: 15px; transition: 0.3s; }
        .card-dashboard:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<main class="container my-5">

    <?php if (($_GET['erro'] ?? '') === 'area_pecuarista'): ?>
        <div class="alert alert-warning">
            <i class="fas fa-user-shield me-2"></i>
            Essa área é exclusiva para pecuaristas. Contas de veterinário podem usar o Canal de Dúvidas e Registrar Atendimento.
        </div>
    <?php endif; ?>
    <section class="boas-vindas text-center mb-5 p-4 bg-white rounded shadow-sm">
        <?php if ($ehVeterinario): ?>
            <h2 class="text-success">Bem-vindo, Dr(a). <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
            <p class="lead text-muted">Aqui você acompanha suas dúvidas pendentes e atendimentos realizados nas propriedades.</p>
            <?php if (!$ehHomologado): ?>
                <div class="alert alert-warning d-inline-block mt-2 mb-0">
                    <i class="fas fa-hourglass-half me-2"></i>Sua conta ainda aguarda homologação.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h2 class="text-success">Bem-vindo à Pecuária em Rede</h2>
            <p class="lead text-muted">Aqui você gerencia seu rebanho com facilidade: controle de peso, vacinação e muito mais.</p>
        <?php endif; ?>
    </section>

    <?php if ($ehVeterinario): ?>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card card-dashboard bg-success text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h6 class="text-uppercase fw-bold opacity-75">Dúvidas Pendentes</h6>
                            <h2 class="display-4 fw-bold mb-0"><?= $totalPendentes ?></h2>
                        </div>
                        <i class="fas fa-comment-medical fa-4x opacity-25"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-dashboard bg-primary text-white shadow">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h6 class="text-uppercase fw-bold opacity-75">Atendimentos Realizados</h6>
                            <h2 class="display-4 fw-bold mb-0"><?= $totalAtendimentos ?></h2>
                        </div>
                        <i class="fas fa-stethoscope fa-4x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <section class="servicos-destaque">
            <h3 class="text-center mb-4">Ações Rápidas</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 p-3 text-center">
                        <i class="fas fa-stethoscope fa-3x text-success mb-3"></i>
                        <h4>Registrar Atendimento</h4>
                        <p class="text-muted">Registre uma visita ou atendimento clínico numa propriedade.</p>
                        <a href="RegistrarAtendimento.php" class="btn btn-outline-success mt-auto rounded-pill">Acessar</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 p-3 text-center">
                        <i class="fas fa-comment-medical fa-3x text-success mb-3"></i>
                        <h4>Canal de Dúvidas</h4>
                        <p class="text-muted">Responda dúvidas enviadas pelos produtores.</p>
                        <a href="CanalDuvidas.php" class="btn btn-outline-success mt-auto rounded-pill">Acessar</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 p-3 text-center">
                        <i class="fas fa-notes-medical fa-3x text-success mb-3"></i>
                        <h4>Meus Atendimentos</h4>
                        <p class="text-muted">Veja o histórico completo do que você já atendeu.</p>
                        <a href="HistoricoAtendimento.php" class="btn btn-outline-success mt-auto rounded-pill">Acessar</a>
                    </div>
                </div>
            </div>
        </section>

    <?php else: ?>

    <div class="row g-4 mb-5">
        <div class="col-md-6 col-lg-4">
            <div class="card card-dashboard bg-primary text-white shadow">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase fw-bold opacity-75">Total de Animais</h6>
                        <h2 class="display-4 fw-bold mb-0"><?= $animalDao->contarAnimaisPorUsuario($_SESSION['id_usuario']); ?></h2>
                    </div>
                    <i class="fas fa-cow fa-4x opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card card-dashboard bg-success text-white shadow">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase fw-bold opacity-75">Vacinas Aplicadas</h6>
                        <h2 class="display-4 fw-bold mb-0"><?= $vacinacaoDao->contarVacinasPorUsuario($_SESSION['id_usuario']); ?></h2>
                    </div>
                    <i class="fas fa-syringe fa-4x opacity-25"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="card card-dashboard bg-warning text-dark shadow">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase fw-bold opacity-75">Pesagens Realizadas</h6>
                        <h2 class="display-4 fw-bold mb-0"><?= $pesoDao->contarPesagensPorUsuario($_SESSION['id_usuario']); ?></h2>
                    </div>
                    <i class="fas fa-weight fa-4x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <section class="servicos-destaque">
        <h3 class="text-center mb-4">Serviços em Destaque</h3>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 p-3 text-center">
                    <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                    <h4>Controle de Peso</h4>
                    <p class="text-muted">Acompanhe o crescimento dos animais com registros organizados.</p>
                    <a href="ControlePeso.php" class="btn btn-outline-success mt-auto rounded-pill">Acessar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 p-3 text-center">
                    <i class="fas fa-shield-virus fa-3x text-success mb-3"></i>
                    <h4>Vacinação</h4>
                    <p class="text-muted">Mantenha seu rebanho protegido e com o calendário em dia.</p>
                    <a href="RegistrarVacinacao.php" class="btn btn-outline-success mt-auto rounded-pill">Acessar</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 p-3 text-center">
                    <i class="fas fa-plus-circle fa-3x text-success mb-3"></i>
                    <h4>Novos Animais</h4>
                    <p class="text-muted">Adicione novos animais ao seu sistema de forma rápida.</p>
                    <a href="CadastroAnimal.php" class="btn btn-outline-success mt-auto rounded-pill">Cadastrar</a>
                </div>
            </div>
        </div>
    </section>

    <?php endif; ?>
</main>

<section id="duvidas_frequentes" class="mt-5 mb-5">
    <div class="text-center mb-4">
        <h3 class="text-success"><i class="fas fa-question-circle me-2"></i>Dúvidas Frequentes</h3>
        <p class="text-muted">Tire suas dúvidas sobre o funcionamento do Pecuária em Rede</p>
    </div>

    <div class="accordion shadow-sm" id="accordionDuvidas">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                    O que é o Projeto Pecuária em Rede?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionDuvidas">
                <div class="accordion-body">
                    O projeto visa digitalizar e melhorar o controle da criação de animais no Agreste, usando tecnologia para registrar peso, vacinação e auxiliar na decisão de venda ou abate.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                    Os meus dados estão seguros?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionDuvidas">
                <div class="accordion-body">
                    Sim! O sistema foi atualizado para garantir que cada produtor veja apenas os seus próprios animais e registros. Seus dados são privados e protegidos por senha.
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                    Como o sistema ajuda na venda ou abate?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionDuvidas">
                <div class="accordion-body">
                    Ao acompanhar o peso regularmente, o sistema sinaliza quando o animal atinge o peso ideal (geralmente acima de 450 kg), ajudando você a decidir o melhor momento para negociar.
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'Rodape.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>