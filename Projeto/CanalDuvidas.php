<?php
date_default_timezone_set('America/Sao_Paulo');
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';

$duvidaDao = new DuvidaDao();
$ehVeterinario = ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario';
$ehHomologado  = $ehVeterinario && (int) ($_SESSION['homologado'] ?? 0) === 1;

include 'Cabecalho.php';
?>

<head>
    <title>Canal de Dúvidas - Pecuária em Rede</title>
    <link rel="stylesheet" href="/CSS/Lista.css?v=2.1">
</head>

<main class="lista-conteudo">

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alerta-notificacao alerta-sucesso" role="alert">
            <i class="fas fa-check-circle"></i>
            <span><?php
                $mensagensSucesso = [
                    'respondido' => 'Resposta enviada com sucesso!',
                    'mensagem' => 'Mensagem enviada com sucesso!',
                ];
                echo htmlspecialchars($mensagensSucesso[$_GET['sucesso']] ?? 'Dúvida enviada com sucesso!');
            ?></span>
            <button type="button" class="fechar-alerta" aria-label="Fechar"><i class="fas fa-times"></i></button>
        </div>
    <?php elseif (isset($_GET['erro'])): ?>
        <div class="alerta-notificacao alerta-erro" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php
                $mensagensErro = [
                    'nao_autorizado' => 'Você não tem permissão para responder a esta conversa.',
                    'mensagem_invalida' => 'Escreva uma mensagem antes de enviar.',
                ];
                echo htmlspecialchars($mensagensErro[$_GET['erro']] ?? 'Não foi possível concluir a ação. Verifique os campos.');
            ?></span>
            <button type="button" class="fechar-alerta" aria-label="Fechar"><i class="fas fa-times"></i></button>
        </div>
    <?php endif; ?>

    <div class="card lista-painel mb-4">
        <div class="card-header lista-painel-header">
            <h4 class="mb-0"><i class="fas fa-comment-medical me-2"></i> Canal de Dúvidas</h4>
        </div>
        <div class="card-body lista-painel-body">

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
        <div class="card lista-painel">
            <div class="card-header lista-painel-header claro">
                <h5 class="mb-0">Minhas Dúvidas</h5>
            </div>
            <div class="card-body lista-painel-body">
                <?php $minhasDuvidas = $duvidaDao->ListarPorUsuario($_SESSION['id_usuario']); ?>
                <?php if (empty($minhasDuvidas)): ?>
                    <p class="text-muted mb-0">Você ainda não enviou nenhuma dúvida.</p>
                <?php else: foreach ($minhasDuvidas as $d): ?>
                    <div class="lista-registro" id="duvida-<?= (int) $d->id_duvida ?>">
                        <p class="mb-1"><strong>Pergunta</strong> (<?= date('d/m/Y H:i', strtotime($d->data_pergunta)) ?>):</p>
                        <p><?= nl2br(htmlspecialchars($d->pergunta)) ?></p>

                        <?php if ($d->status === 'respondida'): ?>
                            <hr>
                            <p class="mb-1 text-success"><strong><i class="fas fa-user-md me-1"></i> Resposta de <?= htmlspecialchars($d->veterinario_nome ?? 'Veterinário') ?></strong> (<?= date('d/m/Y H:i', strtotime($d->data_resposta)) ?>):</p>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($d->resposta)) ?></p>
                            <?php foreach ($duvidaDao->ListarMensagens($d->id_duvida) as $mensagem): ?>
                                <div class="duvida-mensagem <?= $mensagem['tipo_usuario'] === 'Veterinario' ? 'duvida-mensagem-vet' : 'duvida-mensagem-produtor' ?>">
                                    <p class="mb-1"><strong><?= htmlspecialchars($mensagem['autor_nome']) ?></strong> · <?= date('d/m/Y H:i', strtotime($mensagem['enviado_em'])) ?></p>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($mensagem['mensagem'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                            <form method="POST" action="ProcessarMensagemDuvida.php" class="mt-3">
                                <input type="hidden" name="id_duvida" value="<?= (int) $d->id_duvida ?>">
                                <label for="mensagem-<?= (int) $d->id_duvida ?>" class="form-label fw-bold">Responder nesta conversa</label>
                                <textarea name="mensagem" id="mensagem-<?= (int) $d->id_duvida ?>" class="form-control mb-2" rows="2" required></textarea>
                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-reply me-1"></i> Enviar mensagem</button>
                            </form>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Aguardando resposta</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

    <?php elseif ($ehHomologado): ?>
        <div class="card lista-painel">
            <div class="card-header lista-painel-header claro">
                <h5 class="mb-0">Dúvidas e conversas</h5>
            </div>
            <div class="card-body lista-painel-body">
                <?php $duvidasVeterinario = $duvidaDao->ListarParaVeterinario($_SESSION['id_usuario']); ?>
                <?php if (empty($duvidasVeterinario)): ?>
                    <p class="text-muted mb-0">Não há dúvidas ou conversas aguardando sua atenção.</p>
                <?php else: foreach ($duvidasVeterinario as $d): ?>
                    <div class="lista-registro" id="duvida-<?= (int) $d->id_duvida ?>">
                        <p class="mb-1"><strong><?= htmlspecialchars($d->usuario_nome) ?></strong> perguntou em <?= date('d/m/Y H:i', strtotime($d->data_pergunta)) ?>:</p>
                        <p><?= nl2br(htmlspecialchars($d->pergunta)) ?></p>

                        <?php if ($d->status === 'respondida'): ?>
                            <hr>
                            <p class="mb-1 text-success"><strong><i class="fas fa-user-md me-1"></i> <?= (int) $d->id_veterinario === (int) $_SESSION['id_usuario'] ? 'Sua resposta' : 'Resposta de ' . htmlspecialchars($d->veterinario_nome ?? 'Veterinário') ?></strong> (<?= date('d/m/Y H:i', strtotime($d->data_resposta)) ?>):</p>
                            <p><?= nl2br(htmlspecialchars($d->resposta)) ?></p>
                            <?php foreach ($duvidaDao->ListarMensagens($d->id_duvida) as $mensagem): ?>
                                <div class="duvida-mensagem <?= $mensagem['tipo_usuario'] === 'Veterinario' ? 'duvida-mensagem-vet' : 'duvida-mensagem-produtor' ?>">
                                    <p class="mb-1"><strong><?= htmlspecialchars($mensagem['autor_nome']) ?></strong> · <?= date('d/m/Y H:i', strtotime($mensagem['enviado_em'])) ?></p>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($mensagem['mensagem'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                            <?php if ((int) $d->id_veterinario === (int) $_SESSION['id_usuario']): ?>
                                <form method="POST" action="ProcessarMensagemDuvida.php" class="mt-3">
                                    <input type="hidden" name="id_duvida" value="<?= (int) $d->id_duvida ?>">
                                    <textarea name="mensagem" class="form-control mb-2" rows="2" placeholder="Escreva uma mensagem para o produtor..." required></textarea>
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-reply me-1"></i> Enviar mensagem</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <form method="POST" action="ProcessarResposta.php">
                                <input type="hidden" name="id_duvida" value="<?= (int) $d->id_duvida ?>">
                                <textarea name="resposta" class="form-control mb-2" rows="2" placeholder="Escreva sua resposta..." required></textarea>
                                <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-reply me-1"></i> Responder</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    <?php endif; ?>

</main>

<?php include 'Rodape.php'; ?>
</body>
</html>