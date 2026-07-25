<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';

$duvidaDao = new DuvidaDao();
$ehVeterinario = ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario';
$ehHomologado  = $ehVeterinario && (int) ($_SESSION['homologado'] ?? 0) === 1;

include 'Cabecalho.php';
?>

<head>
    <title>Canal de Dúvidas - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Lista.css">
</head>

<main class="container-fluid px-md-5 my-5">

    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-success text-white py-3">
            <h4 class="mb-0"><i class="fas fa-comment-medical me-2"></i> Canal de Dúvidas</h4>
        </div>
        <div class="card-body">

            <?php if (isset($_GET['sucesso'])): ?>
                <div class="alert alert-success">Enviado com sucesso!</div>
            <?php elseif (isset($_GET['erro']) && $_GET['erro'] === 'nao_autorizado'): ?>
                <div class="alert alert-danger">Somente um veterinário homologado pode responder dúvidas (RN04).</div>
            <?php elseif (isset($_GET['erro'])): ?>
                <div class="alert alert-danger">Não foi possível concluir a ação. Verifique os campos.</div>
            <?php endif; ?>

            <?php if (!$ehVeterinario): ?>
               
                <p class="text-muted">Envie sua dúvida sobre manejo, saúde ou nutrição do rebanho. Um veterinário homologado irá responder.</p>
                <form method="POST" action="ProcessarDuvida.php" class="mb-4">
                    <label for="pergunta" class="form-label fw-bold">Sua dúvida:</label>
                    <textarea name="pergunta" id="pergunta" class="form-control mb-2" rows="3" required></textarea>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i> Enviar Dúvida</button>
                </form>

            <?php elseif (!$ehHomologado): ?>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-hourglass-half me-2"></i>
                    Sua conta está cadastrada como veterinário, mas ainda aguarda homologação pela equipe do sistema.
                    Assim que homologada, você poderá responder às dúvidas dos produtores aqui.
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if (!$ehVeterinario): ?>
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Minhas Dúvidas</h5>
            </div>
            <div class="card-body">
                <?php $minhasDuvidas = $duvidaDao->ListarPorUsuario($_SESSION['id_usuario']); ?>
                <?php if (empty($minhasDuvidas)): ?>
                    <p class="text-muted mb-0">Você ainda não enviou nenhuma dúvida.</p>
                <?php else: foreach ($minhasDuvidas as $d): ?>
                    <div class="border rounded-3 p-3 mb-3">
                        <p class="mb-1"><strong>Pergunta</strong> (<?= date('d/m/Y H:i', strtotime($d->data_pergunta)) ?>):</p>
                        <p><?= nl2br(htmlspecialchars($d->pergunta)) ?></p>

                        <?php if ($d->status === 'respondida'): ?>
                            <hr>
                            <p class="mb-1 text-success"><strong><i class="fas fa-user-md me-1"></i> Resposta do Veterinário</strong> (<?= date('d/m/Y H:i', strtotime($d->data_resposta)) ?>):</p>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($d->resposta)) ?></p>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Aguardando resposta</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

    <?php elseif ($ehHomologado): ?>
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Dúvidas Pendentes</h5>
            </div>
            <div class="card-body">
                <?php $pendentes = $duvidaDao->ListarPendentes(); ?>
                <?php if (empty($pendentes)): ?>
                    <p class="text-muted mb-0">Não há dúvidas pendentes no momento.</p>
                <?php else: foreach ($pendentes as $d): ?>
                    <div class="border rounded-3 p-3 mb-3">
                        <p class="mb-1"><strong><?= htmlspecialchars($d->usuario_nome) ?></strong> perguntou em <?= date('d/m/Y H:i', strtotime($d->data_pergunta)) ?>:</p>
                        <p><?= nl2br(htmlspecialchars($d->pergunta)) ?></p>

                        <form method="POST" action="ProcessarResposta.php">
                            <input type="hidden" name="id_duvida" value="<?= $d->id_duvida ?>">
                            <textarea name="resposta" class="form-control mb-2" rows="2" placeholder="Escreva sua resposta..." required></textarea>
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-reply me-1"></i> Responder</button>
                        </form>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    <?php endif; ?>

</main>

</body>
</html>