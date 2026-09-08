<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/AtendimentoDao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$atendimentoDao = new AtendimentoDao();
$animalDao = new AnimalDAO();
$ehVeterinario = ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario';

include_once 'Cabecalho.php';
?>

<head>
    <title>Histórico de Atendimentos - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Lista.css">
</head>

<main class="container-fluid px-md-5 my-5">

    <?php if ($ehVeterinario): ?>
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-stethoscope me-2"></i> Meus Atendimentos</h4>
                <a href="RegistrarAtendimento.php" class="btn btn-light btn-sm"><i class="fas fa-plus me-1"></i> Registrar Atendimento</a>
            </div>
            <div class="card-body p-0">
                <?php $atendimentos = $atendimentoDao->ListarPorVeterinario($_SESSION['id_usuario']);
                if (isset($_GET['sucesso'])): ?>
                    <div class="alert alert-success m-3">Atendimento registrado com sucesso!</div>
                <?php endif; ?>

                <?php if (empty($atendimentos)): ?>
                    <p class="text-muted p-3 mb-0">Nenhum atendimento registrado ainda.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Data</th>
                                    <th>Produtor</th>
                                    <th>Animal</th>
                                    <th>Descrição</th>
                                    <th>Diagnóstico</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($atendimentos as $a): ?>
                                    <tr>
                                        <td class="ps-4"><?= date('d/m/Y', strtotime($a->data_atendimento)) ?></td>
                                        <td><?= htmlspecialchars($a->dono_nome) ?></td>
                                        <td>Brinco <?= htmlspecialchars($a->brinco) ?> (<?= htmlspecialchars($a->raca) ?>) — <a href="FichaAnimal.php?id=<?= $a->id_animal ?>">Ver Ficha</a></td>
                                        <td><?= htmlspecialchars($a->descricao) ?></td>
                                        <td><?= htmlspecialchars($a->diagnostico ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <!-- ===== Visão do Pecuarista: atendimentos recebidos, por animal ===== -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-success text-white py-3">
                <h4 class="mb-0"><i class="fas fa-stethoscope me-2"></i> Atendimentos do Meu Rebanho</h4>
            </div>
            <div class="card-body">
                <?php $meusAnimais = $animalDao->ListarPorUsuario($_SESSION['id_usuario']); ?>
                <?php if (empty($meusAnimais)): ?>
                    <p class="text-muted mb-0">Você ainda não tem animais cadastrados.</p>
                <?php else: foreach ($meusAnimais as $animal):
                    $atendimentos = $atendimentoDao->ListarPorAnimal($animal->id_animal, $_SESSION['id_usuario']);
                    if (empty($atendimentos)) continue;
                ?>
                    <h6 class="mt-3">Brinco <?= htmlspecialchars($animal->brinco) ?> (<?= htmlspecialchars($animal->raca) ?>)</h6>
                    <?php foreach ($atendimentos as $a): ?>
                        <div class="border rounded-3 p-3 mb-2">
                            <p class="mb-1"><strong><?= date('d/m/Y', strtotime($a->data_atendimento)) ?></strong> — Dr(a). <?= htmlspecialchars($a->veterinario_nome) ?></p>
                            <p class="mb-1"><?= nl2br(htmlspecialchars($a->descricao)) ?></p>
                            <?php if ($a->diagnostico): ?><p class="mb-1"><strong>Diagnóstico:</strong> <?= htmlspecialchars($a->diagnostico) ?></p><?php endif; ?>
                            <?php if ($a->recomendacao): ?><p class="mb-0"><strong>Recomendação:</strong> <?= nl2br(htmlspecialchars($a->recomendacao)) ?></p><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; endif; ?>
            </div>
        </div>
    <?php endif; ?>

</main>

</body>
</html>