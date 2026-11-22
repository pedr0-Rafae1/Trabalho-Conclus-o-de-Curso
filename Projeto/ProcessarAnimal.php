<?php
include 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ . '/../app/Model/Animal.php'; 
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$id_dono = $_SESSION['id_usuario']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $brinco          = filter_input(INPUT_POST, 'brinco', FILTER_VALIDATE_INT);
    $especie         = $_POST['especie'];
    $raca            = $_POST['raca'];
    $data_nascimento = $_POST['data_nascimento'];
    $peso            = filter_input(INPUT_POST, 'peso', FILTER_VALIDATE_FLOAT);
    $altura          = filter_input(INPUT_POST, 'altura', FILTER_VALIDATE_FLOAT);

    $hoje = date('Y-m-d');

    if ($data_nascimento > $hoje) {
        header("Location: CadastroAnimal.php?erro=data_futura");
        exit();
    }

    if ($brinco === false || $brinco === null || $brinco <= 0) {
        header("Location: CadastroAnimal.php?erro=brinco_invalido");
        exit();
    }

    if ($peso === false || $peso === null || $peso <= 0) {
        header("Location: CadastroAnimal.php?erro=peso_invalido");
        exit();
    }

    if ($altura === false || $altura === null || $altura <= 0) {
        header("Location: CadastroAnimal.php?erro=altura_invalida");
        exit();
    }

    // Calcula a idade em meses automaticamente a partir da data de nascimento
    $nascimento = new DateTime($data_nascimento);
    $hoje_obj = new DateTime();
    $diferenca = $nascimento->diff($hoje_obj);
    $idade = ($diferenca->y * 12) + $diferenca->m;

    $novoanimal = new Animal($brinco, $idade, $especie, $raca, $data_nascimento, $peso, $altura);

    $daoanimal = new AnimalDao();

    if ($daoanimal->Cadastrar($novoanimal, $id_dono)) {
        header("Location: ListaAnimal.php?sucesso=cadastrado");
        exit();
    } else {
        header("Location: CadastroAnimal.php?erro=banco");
        exit();
    }
    
} else {

    header("Location: CadastroAnimal.php");
    exit();
}