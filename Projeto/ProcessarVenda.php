<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/VendaDao.php';
require_once __DIR__ . '/../app/Model/Venda.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_animal   = (int) ($_POST['id_animal'] ?? 0);
    $comprador   = trim($_POST['comprador'] ?? '');
    $valor_venda = $_POST['valor_venda'] ?? '';
    $data_venda  = $_POST['data_venda'] ?? '';
    $hoje        = date('Y-m-d');

    if ($id_animal <= 0 || $comprador === '' || $valor_venda === '' || $data_venda === '') {
        header("Location: RegistrarVenda.php?erro=campos_invalidos");
        exit();
    }

    if ($data_venda > $hoje) {
        header("Location: RegistrarVenda.php?erro=data_futura");
        exit();
    }

    $venda = new Venda($id_animal, $comprador, $valor_venda, $data_venda);

    $dao = new VendaDao();
    $resultado = $dao->Cadastrar($venda, $_SESSION['id_usuario']);

    if ($resultado === true) {
        header("Location: HistoricoVenda.php?sucesso=1");
        exit();
    } elseif ($resultado === "ja_vendido") {
        header("Location: RegistrarVenda.php?erro=ja_vendido");
        exit();
    } elseif ($resultado === "nao_encontrado") {
        header("Location: RegistrarVenda.php?erro=nao_encontrado");
        exit();
    } else {
        echo "Erro ao registrar a venda.";
    }
} else {
    header("Location: RegistrarVenda.php");
    exit();
}
?>