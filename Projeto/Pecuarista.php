<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header('Location: Veterinario.php');
    exit();
}

require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Dao/RegistroVacinacaoDao.php';

$animalDao = new AnimalDao();
$pesoDao = new RegistroPesoDao();
$vacinacaoDao = new RegistroVacinacaoDao();
$idUsuario = $_SESSION['id_usuario'];
$animais = $animalDao->ListarPorUsuario($idUsuario);
$totalAnimais = count($animais);
$totalPesagens = $pesoDao->contarPesagensPorUsuario($idUsuario);
$totalVacinas = $vacinacaoDao->contarVacinasPorUsuario($idUsuario);

include 'Cabecalho.php';
?>

<head>
    <title>Painel do Pecuarista - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Pecuarista.css?v=2.0">
</head>

<main class="produtor-shell">
    <section class="produtor-hero">
        <div>
            <p class="produtor-kicker"><i class="fas fa-tractor me-1"></i> Painel do produtor</p>
            <h1>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h1>
            <p>Tenha uma visão rápida do seu rebanho e acesse as ferramentas de manejo em poucos cliques.</p>
        </div>
        <i class="fas fa-cow produtor-hero__icon" aria-hidden="true"></i>
    </section>

    <section class="produtor-metrics" aria-label="Resumo do rebanho">
        <article class="produtor-metric">
            <div class="produtor-metric__icon"><i class="fas fa-cow"></i></div>
            <div><strong><?= $totalAnimais ?></strong><span>Animais cadastrados</span></div>
        </article>
        <article class="produtor-metric">
            <div class="produtor-metric__icon"><i class="fas fa-weight"></i></div>
            <div><strong><?= $totalPesagens ?></strong><span>Pesagens registradas</span></div>
        </article>
        <article class="produtor-metric">
            <div class="produtor-metric__icon"><i class="fas fa-syringe"></i></div>
            <div><strong><?= $totalVacinas ?></strong><span>Vacinas registradas</span></div>
        </article>
    </section>

    <section class="produtor-grid">
        <article class="produtor-panel">
            <header class="produtor-panel__header">
                <h2><i class="fas fa-list me-2"></i>Meu rebanho</h2>
                <a href="ListaAnimal.php" class="btn btn-sm btn-outline-success">Ver todos</a>
            </header>
            <div class="produtor-panel__body">
                <?php if (empty($animais)): ?>
                    <p class="produtor-empty">Você ainda não cadastrou animais.</p>
                <?php else: ?>
                    <ul class="produtor-list">
                        <?php foreach (array_slice($animais, 0, 6) as $animal): ?>
                            <li>
                                <strong>Brinco <?= htmlspecialchars($animal->brinco) ?></strong>
                                <small><?= htmlspecialchars($animal->raca) ?> · <?= htmlspecialchars($animal->especie) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </article>

        <aside class="produtor-panel">
            <header class="produtor-panel__header">
                <h2><i class="fas fa-bolt me-2"></i>Ações rápidas</h2>
            </header>
            <div class="produtor-panel__body produtor-actions">
                <a class="produtor-action" href="CadastroAnimal.php"><i class="fas fa-plus-circle"></i>Cadastrar animal</a>
                <a class="produtor-action" href="ControlePeso.php"><i class="fas fa-weight"></i>Registrar peso</a>
                <a class="produtor-action" href="RegistrarVacinacao.php"><i class="fas fa-syringe"></i>Registrar vacina</a>
                <a class="produtor-action" href="CanalDuvidas.php"><i class="fas fa-comment-medical"></i>Falar com veterinário</a>
            </div>
        </aside>
    </section>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>
