<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ . '/../app/Model/RegistroPeso.php'; 
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$id_dono = $_SESSION['id_usuario']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_animal  = (int) ($_POST['id_animal'] ?? 0);
    $peso_anterior  = $_POST['peso_anterior'];
    $peso_atual = filter_input(INPUT_POST, 'peso_atual', FILTER_VALIDATE_FLOAT);
    $data = $_POST['data_pessagem'];

    $hoje = date('Y-m-d');

    if ($data > $hoje) {
        header("Location: ControlePeso.php?erro=data_futura");
        exit();
    }

    if ($peso_atual === false || $peso_atual === null || $peso_atual <= 0) {
        header("Location: ControlePeso.php?erro=peso_invalido");
        exit();
    }

    // Regra de negócio: a pesagem não pode ser anterior ao nascimento do animal
    $animalDao = new AnimalDao();
    $animal = $animalDao->buscarPorIdEUsuario($id_animal, $id_dono);

    if (!$animal) {
        header("Location: ControlePeso.php?erro=animal_nao_encontrado");
        exit();
    }

    if (!empty($animal['data_nascimento']) && $data < $animal['data_nascimento']) {
        header("Location: ControlePeso.php?erro=data_anterior_nascimento");
        exit();
    }
    

    $registrarpeso = new RegistroPeso($id_animal, $peso_anterior, $peso_atual, $data);

    $dao = new RegistroPesoDAO();
    
    if ($dao->Cadastrar($registrarpeso)) {
        header("Location: ListaRegistroPeso.php?sucesso=cadastrado");
        exit();
    } else {
        header("Location: ControlePeso.php?erro=banco");
        exit();
    }
} else {
    
    header("Location: ControlePeso.php");
}

?>