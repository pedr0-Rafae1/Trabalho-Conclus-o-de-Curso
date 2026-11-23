<?php
include_once 'Sessao.php';

if ((int) ($_SESSION['admin'] ?? 0) === 1) {
    header('Location: PainelAdmin.php');
    exit();
}

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header('Location: Veterinario.php?erro=area_pecuarista');
    exit();
}

require_once __DIR__ . '/../app/Dao/VendaDao.php';
require_once __DIR__ . '/../app/Model/Venda.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_animal   = (int) ($_POST['id_animal'] ?? 0);
    $comprador   = trim($_POST['comprador'] ?? '');
    $valor_venda = filter_input(INPUT_POST, 'valor_venda', FILTER_VALIDATE_FLOAT);
    $data_venda  = $_POST['data_venda'] ?? '';
    $hoje        = date('Y-m-d');

    if ($id_animal <= 0 || $comprador === '' || $valor_venda === false || $valor_venda === null || $data_venda === '') {
        header("Location: RegistrarVenda.php?erro=campos_invalidos");
        exit();
    }

    if ($valor_venda <= 0) {
        header("Location: RegistrarVenda.php?erro=valor_invalido");
        exit();
    }

    if ($data_venda > $hoje) {
        header("Location: RegistrarVenda.php?erro=data_futura");
        exit();
    }

    // Regra de negócio: a venda não pode ser anterior ao nascimento do animal
    $animalDao = new AnimalDao();
    $animal = $animalDao->buscarPorIdEUsuario($id_animal, $_SESSION['id_usuario']);

    if (!$animal) {
        header("Location: RegistrarVenda.php?erro=nao_encontrado");
        exit();
    }

    if (!empty($animal['data_nascimento']) && $data_venda < $animal['data_nascimento']) {
        header("Location: RegistrarVenda.php?erro=data_anterior_nascimento");
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