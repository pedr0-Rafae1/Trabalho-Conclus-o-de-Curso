<?php
include_once 'Sessao.php';

// Proteção: apenas administradores
if ((int) ($_SESSION['admin'] ?? 0) !== 1) {
    $painelUsuario = ($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario'
        ? 'Veterinario.php'
        : 'Pecuarista.php';
    header('Location: ' . $painelUsuario . '?erro=acesso_negado');
    exit();
}

require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);

    if ($id_usuario === false || $id_usuario === null || $id_usuario <= 0) {
        header("Location: PainelAdmin.php?erro=id_invalido");
        exit();
    }

    $usuarioDao = new UsuarioDao();

    if ($usuarioDao->Homologar($id_usuario)) {
        header("Location: PainelAdmin.php?sucesso=1");
        exit();
    } else {
        header("Location: PainelAdmin.php?erro=banco");
        exit();
    }
} else {
    header("Location: PainelAdmin.php");
    exit();
}
