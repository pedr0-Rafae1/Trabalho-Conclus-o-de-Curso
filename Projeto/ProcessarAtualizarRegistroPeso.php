<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Model/RegistroPeso.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$hoje = date('Y-m-d');

$id = $_POST['id_peso']; 
$id_animal = $_POST['id_animal'];
$peso_anterior = $_POST['peso_anterior'];
$peso_atual = $_POST['peso_atual'];
$data = $_POST['data_pessagem'];

if ($data > $hoje) {
    header("Location: EditarRegistroPeso.php?id={$id}&erro=data_futura");
    exit();
}

// Regra de negócio: a pesagem não pode ser anterior ao nascimento do animal
$animalDao = new AnimalDao();
$animal = $animalDao->buscarPorIdEUsuario((int) $id_animal, $_SESSION['id_usuario']);

if (!$animal) {
    header("Location: EditarRegistroPeso.php?id={$id}&erro=animal_nao_encontrado");
    exit();
}

if (!empty($animal['data_nascimento']) && $data < $animal['data_nascimento']) {
    header("Location: EditarRegistroPeso.php?id={$id}&erro=data_invalida");
    exit();
}

$registropeso = new RegistroPeso($id_animal, $peso_anterior, $peso_atual, $data);
$registropeso->id_peso = $id; 


$dao = new RegistroPesoDAO();

if ($dao->Atualizar($registropeso, $_SESSION['id_usuario'])) {
    
    header("Location: ListaRegistroPeso.php?sucesso=editado");
    exit();
} else {
    echo "Erro ao tentar atualizar os dados no banco.";
}