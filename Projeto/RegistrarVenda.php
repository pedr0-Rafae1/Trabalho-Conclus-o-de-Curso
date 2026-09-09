<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/VendaDao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: home.php?erro=area_pecuarista");
    exit();
}

$vendaDao = new VendaDao();
$animaisDisponiveis = $vendaDao->ListarAnimaisDisponiveis($_SESSION['id_usuario']);
$hoje = date('Y-m-d');

include 'Cabecalho.php';
?>

<head>
    <title>Registrar Venda - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Formularios.css?v=2.0">
</head>

<main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-hand-holding-usd me-2"></i> Registrar Venda</h2>

        <?php if (empty($animaisDisponiveis)): ?>
            <p class="text-muted">Não há animais disponíveis para venda (todos já vendidos ou nenhum cadastrado).</p>
        <?php else: ?>
            <form action="ProcessarVenda.php" method="POST">

                <label for="id_animal">Animal:</label>
                <select id="id_animal" name="id_animal" class="form-control" required>
                    <option value="">-- Selecione --</option>
                    <?php foreach ($animaisDisponiveis as $a): ?>
                        <option value="<?= $a['id_animal'] ?>">
                            #<?= $a['id_animal'] ?> - Brinco <?= htmlspecialchars($a['brinco']) ?> (<?= htmlspecialchars($a['raca']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="comprador">Comprador:</label>
                <input type="text" id="comprador" name="comprador" class="form-control" placeholder="Ex: João da Silva" required>

                <label for="valor_venda">Valor da Venda (R$):</label>
                <input type="text" id="valor_venda" name="valor_venda" class="form-control" placeholder="Ex: 3500.00" required>

                <label for="data_venda">Data da Venda:</label>
                <input type="date" id="data_venda" name="data_venda" class="form-control" max="<?= $hoje ?>" required>

                <button type="submit">Registrar Venda</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>