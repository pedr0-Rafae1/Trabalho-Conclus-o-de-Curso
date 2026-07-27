<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/AtendimentoDao.php';
require_once __DIR__ . '/../app/Model/Atendimento.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (($_SESSION['tipo_usuario'] ?? '') !== 'Veterinario' || (int) ($_SESSION['homologado'] ?? 0) !== 1) {
        header("Location: RegistrarAtendimento.php?erro=nao_autorizado");
        exit();
    }

    $id_animal    = (int) ($_POST['id_animal'] ?? 0);
    $data_atend   = $_POST['data_atendimento'] ?? '';
    $descricao    = trim($_POST['descricao'] ?? '');
    $diagnostico  = trim($_POST['diagnostico'] ?? '');
    $recomendacao = trim($_POST['recomendacao'] ?? '');
    $hoje         = date('Y-m-d');

    if ($id_animal <= 0 || $data_atend === '' || $descricao === '') {
        header("Location: RegistrarAtendimento.php?erro=campos_invalidos");
        exit();
    }

    if ($data_atend > $hoje) {
        header("Location: RegistrarAtendimento.php?erro=data_futura");
        exit();
    }

    $atendimento = new Atendimento($id_animal, $data_atend, $descricao, $diagnostico ?: null, $recomendacao ?: null);

    $dao = new AtendimentoDao();
    $resultado = $dao->Cadastrar($atendimento, $_SESSION['id_usuario']);

    if ($resultado === true) {
        header("Location: HistoricoAtendimento.php?sucesso=1");
        exit();
    } else {
        header("Location: RegistrarAtendimento.php?erro=1");
        exit();
    }
} else {
    header("Location: RegistrarAtendimento.php");
    exit();
}
?>