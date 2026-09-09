<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/NotificacaoDao.php';

$notificacaoDao = new NotificacaoDao();
$notificacaoDao->MarcarTodasComoLidas($_SESSION['id_usuario']);

$destino = ($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario'
    ? 'Veterinario.php'
    : 'Pecuarista.php';
header('Location: ' . $destino);
exit();
