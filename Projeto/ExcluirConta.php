<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$usuarioDao = new UsuarioDao();

if ($usuarioDao->Remover($id_usuario)) {
    session_unset();
    session_destroy();
    header("Location: login.php?conta=excluida");
    exit();
} else {
    header("Location: MeuPerfil.php?erro=exclusao");
    exit();
}
?>
?>
