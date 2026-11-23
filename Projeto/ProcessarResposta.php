<?php

date_default_timezone_set('America/Sao_Paulo');

include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/DuvidaDao.php';
require_once __DIR__ . '/../app/Dao/NotificacaoDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_duvida = (int) ($_POST['id_duvida'] ?? 0);
    $respostaRecebida = $_POST['resposta'] ?? '';
    if (!is_string($respostaRecebida)) {
        header("Location: CanalDuvidas.php?erro=resposta_invalida");
        exit();
    }
    $resposta = trim($respostaRecebida);

    if ($resposta === '' || $id_duvida <= 0) {
        header("Location: CanalDuvidas.php?erro=resposta_invalida");
        exit();
    }

    $dao = new DuvidaDao();
    $duvida = $dao->ObterPorId($id_duvida);

    if (($_SESSION['tipo_usuario'] ?? '') !== 'Veterinario' || (int) ($_SESSION['homologado'] ?? 0) !== 1) {
        header("Location: CanalDuvidas.php?erro=nao_autorizado");
        exit();
    }

    if ($duvida && $dao->Responder($id_duvida, $_SESSION['id_usuario'], $resposta)) {
        $notificacaoDao = new NotificacaoDao();
        if (!$notificacaoDao->Criar(
            (int) $duvida['id_usuario'],
            'duvida_resposta',
            'O veterinário respondeu sua dúvida',
            ($_SESSION['usuario_nome'] ?? 'O veterinário') . ' respondeu à sua dúvida no Canal de Dúvidas.',
            $id_duvida
        )) {
            error_log('Falha ao criar notificação da resposta da dúvida ' . $id_duvida . '.');
        }
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