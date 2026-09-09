<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$ehVeterinario = ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario';
$ehHomologado  = $ehVeterinario && (int) ($_SESSION['homologado'] ?? 0) === 1;

$hoje = date('Y-m-d');
$animalDao = new AnimalDAO();
$todosAnimais = $ehHomologado ? $animalDao->ListarTodosComDono() : [];

include 'Cabecalho.php';
?>

<head>
    <title>Registrar Atendimento - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Formularios.css?v=2.0">
</head>

<main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-stethoscope me-2"></i> Registrar Atendimento</h2>

        <?php if (!$ehVeterinario): ?>
            <div class="alert alert-danger">Essa área é exclusiva para veterinários.</div>

        <?php elseif (!$ehHomologado): ?>
            <div class="alert alert-warning">
                Sua conta de veterinário ainda aguarda homologação. Assim que homologada, você poderá registrar atendimentos aqui.
            </div>

        <?php else: ?>
            <?php if (isset($_GET['erro'])): ?>
                <div class="alert alert-danger">Não foi possível registrar o atendimento. Verifique os dados.</div>
            <?php endif; ?>

            <form action="ProcessarAtendimento.php" method="POST">

                <label for="id_animal">Animal / Propriedade:</label>
                <select id="id_animal" name="id_animal" class="form-control" required>
                    <option value="">-- Selecione o animal --</option>
                    <?php foreach ($todosAnimais as $a): ?>
                        <option value="<?= $a->id_animal ?>">
                            #<?= $a->id_animal ?> - Brinco <?= htmlspecialchars($a->brinco) ?> (<?= htmlspecialchars($a->raca) ?>) — Produtor: <?= htmlspecialchars($a->dono_nome) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="data_atendimento">Data do Atendimento:</label>
                <input type="date" id="data_atendimento" name="data_atendimento" class="form-control" max="<?= $hoje ?>" required>

                <label for="descricao">Descrição da Visita/Atendimento:</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="3" required></textarea>

                <label for="diagnostico">Diagnóstico (opcional):</label>
                <input type="text" id="diagnostico" name="diagnostico" class="form-control" placeholder="Ex: Verminose leve">

                <label for="recomendacao">Recomendação ao Produtor (opcional):</label>
                <textarea id="recomendacao" name="recomendacao" class="form-control" rows="2"></textarea>

                <button type="submit">Registrar Atendimento</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>