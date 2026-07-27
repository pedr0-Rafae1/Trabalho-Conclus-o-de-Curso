<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/VendaDao.php';

$vendaDao = new VendaDao();
$vendas = $vendaDao->ListarPorUsuario($_SESSION['id_usuario']);

include 'Cabecalho.php';
?>

<head>
    <title>Histórico de Vendas - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Lista.css">
</head>

<main class="container-fluid px-md-5 my-5">

    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-success text-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-hand-holding-usd me-2"></i> Histórico de Vendas</h4>
            <a href="RegistrarVenda.php" class="btn btn-light btn-sm"><i class="fas fa-plus me-1"></i> Registrar Venda</a>
        </div>
        <div class="card-body p-0">

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success m-3">Venda registrada com sucesso!</div>
            <?php endif; ?>

            <?php if (empty($vendas)): ?>
                <p class="text-muted p-3 mb-0">Nenhuma venda registrada ainda.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Brinco</th>
                                <th>Raça</th>
                                <th>Comprador</th>
                                <th>Valor</th>
                                <th>Data da Venda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vendas as $v): ?>
                                <tr>
                                    <td class="ps-4"><?= htmlspecialchars($v->brinco) ?></td>
                                    <td><?= htmlspecialchars($v->raca) ?></td>
                                    <td><?= htmlspecialchars($v->comprador) ?></td>
                                    <td>R$ <?= number_format($v->valor_venda, 2, ',', '.') ?></td>
                                    <td><?= date('d/m/Y', strtotime($v->data_venda)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

</body>
</html>