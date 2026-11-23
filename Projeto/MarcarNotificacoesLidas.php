<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/NotificacaoDao.php';

$notificacaoDao = new NotificacaoDao();
$idNotificacao = filter_input(INPUT_GET, 'id_notificacao', FILTER_VALIDATE_INT);

$destino = (int) ($_SESSION['admin'] ?? 0) === 1
    ? 'PainelAdmin.php'
    : (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario'
        ? 'Veterinario.php'
        : 'Pecuarista.php');

if ($idNotificacao) {
    $notificacao = $notificacaoDao->MarcarComoLidaEObter($idNotificacao, (int) $_SESSION['id_usuario']);
    if ($notificacao && strpos($notificacao['tipo'], 'duvida_') === 0 && !empty($notificacao['id_referencia'])) {
        $destino = 'CanalDuvidas.php#duvida-' . (int) $notificacao['id_referencia'];
    }
} else {
    $notificacaoDao->MarcarTodasComoLidas($_SESSION['id_usuario']);
}

header('Location: ' . $destino);
exit();
