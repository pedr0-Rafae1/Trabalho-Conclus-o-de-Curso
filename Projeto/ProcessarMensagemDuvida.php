<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';
require_once __DIR__ . '/../app/Dao/NotificacaoDao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: CanalDuvidas.php');
    exit();
}

$idDuvida = filter_input(INPUT_POST, 'id_duvida', FILTER_VALIDATE_INT);
$mensagemRecebida = $_POST['mensagem'] ?? '';
if (!is_string($mensagemRecebida)) {
    header('Location: CanalDuvidas.php?erro=mensagem_invalida');
    exit();
}
$mensagem = trim($mensagemRecebida);
if (!$idDuvida || $mensagem === '') {
    header('Location: CanalDuvidas.php?erro=mensagem_invalida');
    exit();
}

$dao = new DuvidaDao();
$destinatario = $dao->EnviarMensagem(
    $idDuvida,
    (int) $_SESSION['id_usuario'],
    $_SESSION['tipo_usuario'] ?? '',
    $mensagem
);

if (!$destinatario) {
    header('Location: CanalDuvidas.php?erro=nao_autorizado');
    exit();
}

$ehVeterinario = ($_SESSION['tipo_usuario'] ?? '') === 'Veterinario';
$tipoNotificacao = $ehVeterinario ? 'duvida_resposta' : 'duvida_resposta_pecuarista';
$titulo = $ehVeterinario ? 'Nova resposta do veterinário' : 'Nova resposta do produtor';
$nomeAutor = $_SESSION['usuario_nome'] ?? ($ehVeterinario ? 'O veterinário' : 'O pecuarista');
$texto = $ehVeterinario
    ? $nomeAutor . ' respondeu à conversa da sua dúvida.'
    : $nomeAutor . ' respondeu à conversa no Canal de Dúvidas.';
$notificacaoDao = new NotificacaoDao();
if (!$notificacaoDao->Criar($destinatario, $tipoNotificacao, $titulo, $texto, $idDuvida)) {
    error_log('Falha ao criar notificação da mensagem da dúvida ' . $idDuvida . '.');
}

header('Location: CanalDuvidas.php?sucesso=mensagem#duvida-' . $idDuvida);
exit();
