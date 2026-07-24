<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Model/Animal.php';
require_once __DIR__ . '/../app/Model/RegistroPeso.php';
require_once __DIR__ . '/../app/Conexao/ConexaoBD.php';

$animalDao = new AnimalDao();
$registropesoDao = new RegistroPesoDao();

$animais = $animalDao->ListarPorUsuario($_SESSION['id_usuario']);

$id_animal_selecionado = isset($_GET['id_animal']) ? (int) $_GET['id_animal'] : null;
$historico = [];
$animalSelecionado = null;

if ($id_animal_selecionado) {
    
    $historico = $registropesoDao->ListarPorAnimal($id_animal_selecionado, $_SESSION['id_usuario']);
    foreach ($animais as $a) {
        if ($a->id_animal == $id_animal_selecionado) {
            $animalSelecionado = $a;
            break;
        }
    }
}

$temDadosSuficientes = count($historico) >= 2; 

include 'Cabecalho.php';
?>

<head>
    <title>Evolução de Peso - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Lista.css">
</head>

<main class="container-fluid px-md-5 my-5">

    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-success text-white py-3">
            <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i> Evolução de Peso</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="EvolucaoPeso.php" class="row g-2 align-items-end">
                <div class="col-md-8">
                    <label for="id_animal" class="form-label fw-bold">Selecione o animal:</label>
                    <select name="id_animal" id="id_animal" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Escolha um animal --</option>
                        <?php foreach ($animais as $a): ?>
                            <option value="<?= $a->id_animal ?>" <?= ($id_animal_selecionado == $a->id_animal) ? 'selected' : '' ?>>
                                #<?= $a->id_animal ?> - Brinco <?= htmlspecialchars($a->brinco) ?> (<?= htmlspecialchars($a->raca) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-search me-1"></i> Visualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if ($id_animal_selecionado && !$animalSelecionado): ?>
        <div class="alert alert-danger shadow-sm">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Animal não encontrado ou não pertence à sua conta.
        </div>

    <?php elseif ($id_animal_selecionado && !$temDadosSuficientes): ?>
        <div class="alert alert-warning shadow-sm">
            <i class="fas fa-info-circle me-2"></i>
            Ainda não é possível gerar a curva de evolução para o brinco <?= htmlspecialchars($animalSelecionado->brinco) ?>.
            São necessárias no mínimo <strong>2 pesagens</strong> registradas (hoje há <?= count($historico) ?>).
            Cadastre mais uma pesagem em <a href="ControlePeso.php">Controle de Peso</a>.
        </div>

    <?php elseif ($id_animal_selecionado && $temDadosSuficientes): ?>

        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Curva de Ganho de Peso — Brinco <?= htmlspecialchars($animalSelecionado->brinco) ?></h5>
            </div>
            <div class="card-body">
                <canvas id="graficoPeso" height="90"></canvas>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Histórico de Pesagens</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Data</th>
                                <th>Peso Anterior (kg)</th>
                                <th>Peso Atual (kg)</th>
                                <th>Variação (kg)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historico as $h):
                                $variacao = $h->peso_atual - $h->peso_anterior;
                            ?>
                                <tr>
                                    <td class="ps-4"><?= date('d/m/Y', strtotime($h->data_pessagem)) ?></td>
                                    <td><?= number_format($h->peso_anterior, 2, ',', '.') ?></td>
                                    <td><?= number_format($h->peso_atual, 2, ',', '.') ?></td>
                                    <td class="<?= $variacao >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <?= $variacao >= 0 ? '+' : '' ?><?= number_format($variacao, 2, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const datas = <?= json_encode(array_map(fn($h) => date('d/m/Y', strtotime($h->data_pessagem)), $historico)) ?>;
            const pesos = <?= json_encode(array_map(fn($h) => (float) $h->peso_atual, $historico)) ?>;

            new Chart(document.getElementById('graficoPeso'), {
                type: 'line',
                data: {
                    labels: datas,
                    datasets: [{
                        label: 'Peso (kg)',
                        data: pesos,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.15)',
                        tension: 0.25,
                        fill: true,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: false } }
                }
            });
        </script>

    <?php endif; ?>

</main>

</body>
</html>