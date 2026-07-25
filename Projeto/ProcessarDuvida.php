<?php

include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';
require_once __DIR__ . '/../app/Model/Duvida.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $pergunta = trim($_POST['pergunta'] ?? '');

    if ($pergunta === '') {
        header("Location: CanalDuvidas.php?erro=vazio");
        exit();
    }

    $duvida = new Duvida($_SESSION['id_usuario'], $pergunta);

    $dao = new DuvidaDao();
    if ($dao->Cadastrar($duvida)) {
        header("Location: CanalDuvidas.php?sucesso=1");
        exit();
    } else {
        echo "Erro ao enviar a dúvida.";
    }
} else {
    header("Location: CanalDuvidas.php");
    exit();
}

?>