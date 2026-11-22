<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/VendaDao.php';

$vendaDao = new VendaDao();
$vendas = $vendaDao->ListarPorUsuario($_SESSION['id_usuario']);

include_once 'Cabecalho.php';
?>

<head>
    <title>Histórico de Vendas - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Lista.css?v=2.1">
</head>

<main class="lista-conteudo">
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alerta-notificacao alerta-sucesso" role="alert">
            <i class="fas fa-check-circle"></i>
            <span>Venda registrada com sucesso!</span>
            <button type="button" class="fechar-alerta" aria-label="Fechar"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>

    <div class="card lista-card mb-4">
        <div class="card-header lista-cabecalho">
            <h4><i class="fas fa-receipt me-2"></i> Histórico de Vendas</h4>
            <div class="lista-cabecalho-busca"><i class="fas fa-search"></i><input class="lista-busca" id="buscaVenda" type="search" placeholder="Buscar venda" aria-label="Buscar venda"></div>
            <a href="RegistrarVenda.php" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-plus me-1"></i> Registrar Venda
            </a>
        </div>

        <div class="card-body lista-painel-body sem-padding">
            <?php if (empty($vendas)): ?>
                <p class="text-muted p-3 mb-0">Nenhuma venda registrada ainda.</p>
            <?php else: ?>
                <div class="table-responsive lista-tabela-wrap">
                    <table class="table table-hover align-middle lista-tabela">
                        <thead>
                            <tr>
                                <th class="ps-4">Data</th>
                                <th>Animal</th>
                                <th>Raça</th>
                                <th>Comprador</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vendas as $venda): ?>
                                <tr>
                                    <td class="ps-4"><?= date('d/m/Y', strtotime($venda->data_venda)) ?></td>
                                    <td>Brinco <?= htmlspecialchars($venda->brinco) ?></td>
                                    <td><?= htmlspecialchars($venda->raca) ?></td>
                                    <td><?= htmlspecialchars($venda->comprador) ?></td>
                                    <td>R$ <?= number_format((float) $venda->valor_venda, 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'Rodape.php'; ?>
<script>
    document.getElementById('buscaVenda')?.addEventListener('input', function () {
        const termo = this.value.toLowerCase().trim();
        document.querySelectorAll('.lista-tabela tbody tr').forEach(linha => {
            linha.style.display = linha.textContent.toLowerCase().includes(termo) ? '' : 'none';
        });
    });
</script>
</body>
</html>
