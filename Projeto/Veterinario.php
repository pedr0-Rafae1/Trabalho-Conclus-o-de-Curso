<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? '') !== 'Veterinario') {
    header('Location: Pecuarista.php?erro=area_veterinario');
    exit();
}

require_once __DIR__ . '/../app/Dao/AtendimentoDao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';

$ehHomologado = (int) ($_SESSION['homologado'] ?? 0) === 1;
$atendimentoDao = new AtendimentoDao();
$duvidaDao = new DuvidaDao();
$atendimentos = $atendimentoDao->ListarPorVeterinario($_SESSION['id_usuario']);
$respondidas = $duvidaDao->ListarRespondidasPorVeterinario($_SESSION['id_usuario']);
$pendentes = $ehHomologado ? $duvidaDao->ListarPendentes() : [];

include 'Cabecalho.php';
?>

<head>
    <title>Área do Veterinário - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Veterinario.css?v=3.0">
</head>

<main class="vet-shell">
    <section class="vet-hero">
        <div>
            <p class="vet-kicker"><i class="fas fa-stethoscope me-1"></i> Área profissional</p>
            <h1>Olá, Dr(a). <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h1>
            <p>Organize seus atendimentos, responda produtores e acompanhe a saúde dos rebanhos atendidos.</p>
            <?php if (!$ehHomologado): ?>
                <div class="vet-status"><i class="fas fa-hourglass-half"></i> Conta aguardando homologação</div>
            <?php else: ?>
                <div class="vet-status vet-status-ok"><i class="fas fa-circle-check"></i> Perfil profissional homologado</div>
            <?php endif; ?>
        </div>
        <i class="fas fa-user-md vet-hero__icon" aria-hidden="true"></i>
    </section>

    <section class="vet-metrics" aria-label="Resumo profissional">
        <article class="vet-metric">
            <div class="vet-metric__icon"><i class="fas fa-notes-medical"></i></div>
            <div><strong><?= count($atendimentos) ?></strong><span>Atendimentos realizados</span></div>
        </article>
        <article class="vet-metric">
            <div class="vet-metric__icon"><i class="fas fa-comment-medical"></i></div>
            <div><strong><?= count($pendentes) ?></strong><span>Dúvidas aguardando resposta</span></div>
        </article>
        <article class="vet-metric">
            <div class="vet-metric__icon"><i class="fas fa-reply"></i></div>
            <div><strong><?= count($respondidas) ?></strong><span>Dúvidas respondidas por você</span></div>
        </article>
    </section>

    <section class="vet-grid">
        <article class="vet-panel">
            <header class="vet-panel__header">
                <h2><i class="fas fa-clock me-2"></i>Atendimentos recentes</h2>
                <a href="HistoricoAtendimento.php" class="btn btn-sm btn-outline-success">Ver todos</a>
            </header>
            <div class="vet-panel__body">
                <?php if (empty($atendimentos)): ?>
                    <p class="vet-empty">Você ainda não registrou atendimentos.</p>
                <?php else: ?>
                    <ul class="vet-list">
                        <?php foreach (array_slice($atendimentos, 0, 5) as $atendimento): ?>
                            <li>
                                <strong><?= date('d/m/Y', strtotime($atendimento->data_atendimento)) ?> · <?= htmlspecialchars($atendimento->dono_nome) ?></strong>
                                <small>Animal com brinco <?= htmlspecialchars($atendimento->brinco) ?> · <?= htmlspecialchars($atendimento->diagnostico ?: 'Sem diagnóstico informado') ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </article>

        <aside class="vet-panel">
            <header class="vet-panel__header">
                <h2><i class="fas fa-bolt me-2"></i>Ações rápidas</h2>
            </header>
            <div class="vet-panel__body vet-actions">
                <?php if ($ehHomologado): ?>
                    <a class="vet-action" href="RegistrarAtendimento.php"><i class="fas fa-plus-circle"></i>Registrar atendimento</a>
                    <a class="vet-action" href="CanalDuvidas.php"><i class="fas fa-comments"></i>Responder dúvidas</a>
                <?php else: ?>
                    <p class="vet-empty">As ações profissionais serão liberadas após a homologação da sua conta.</p>
                <?php endif; ?>
                <a class="vet-action" href="MeuPerfil.php"><i class="fas fa-user-circle"></i>Meu perfil</a>
            </div>
        </aside>
    </section>

    <section class="vet-grid vet-grid-bottom">
        <article class="vet-panel">
            <header class="vet-panel__header">
                <h2><i class="fas fa-inbox me-2"></i>Fila de dúvidas</h2>
                <a href="CanalDuvidas.php" class="btn btn-sm btn-outline-success">Abrir canal</a>
            </header>
            <div class="vet-panel__body">
                <?php if (!$ehHomologado): ?>
                    <p class="vet-empty">A fila ficará disponível após a homologação da conta.</p>
                <?php elseif (empty($pendentes)): ?>
                    <p class="vet-empty"><i class="fas fa-check-circle me-1"></i> Nenhuma dúvida aguardando resposta.</p>
                <?php else: ?>
                    <ul class="vet-list vet-list-questions">
                        <?php foreach (array_slice($pendentes, 0, 4) as $duvida): ?>
                            <li>
                                <strong><?= htmlspecialchars($duvida->usuario_nome) ?></strong>
                                <small><?= htmlspecialchars(mb_strimwidth($duvida->pergunta, 0, 130, '...')) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </article>

        <article class="vet-panel vet-profile-card">
            <header class="vet-panel__header">
                <h2><i class="fas fa-id-badge me-2"></i>Perfil profissional</h2>
            </header>
            <div class="vet-panel__body">
                <p>Revise seus dados, foto e informações de acesso para manter seu perfil atualizado.</p>
                <a class="vet-action" href="MeuPerfil.php"><i class="fas fa-user-pen"></i>Editar meu perfil</a>
            </div>
        </article>
    </section>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>
