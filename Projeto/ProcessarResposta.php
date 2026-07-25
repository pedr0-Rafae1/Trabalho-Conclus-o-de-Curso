<?php

include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_duvida = (int) ($_POST['id_duvida'] ?? 0);
    $resposta  = trim($_POST['resposta'] ?? '');

    if ($resposta === '' || $id_duvida <= 0) {
        header("Location: CanalDuvidas.php?erro=resposta_invalida");
        exit();
    }

    $dao = new DuvidaDao();

    if (($_SESSION['tipo_usuario'] ?? '') !== 'Veterinario' || (int) ($_SESSION['homologado'] ?? 0) !== 1) {
        header("Location: CanalDuvidas.php?erro=nao_autorizado");
        exit();
    }

    if ($dao->Responder($id_duvida, $_SESSION['id_usuario'], $resposta)) {
        header("Location: CanalDuvidas.php?sucesso=respondido");
        exit();
    } else {
        echo "Não foi possível registrar a resposta.";
    }
} else {
    header("Location: CanalDuvidas.php");
    exit();
}

?>