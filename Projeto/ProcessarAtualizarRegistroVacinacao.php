<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ . '/../app/Dao/RegistroVacinacaoDao.php';
require_once __DIR__ . '/../app/Model/RegistroVacinacao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';

$hoje = date('Y-m-d');

$id = $_POST['id_vacinacao']; 
$id_animal = $_POST['id_animal'];
$nome_vacina = $_POST['nome_vacina'];
$data = $_POST['data_aplicacao'];
$aplicador = $_POST['aplicador'];
$dose  = $_POST['dose'];

if ($data > $hoje) {
    header("Location: EditarRegistroVacinacao.php?id={$id}&erro=data_futura");
    exit();
}

// Regra de negócio: a vacinação não pode ser anterior ao nascimento do animal
$animalDao = new AnimalDao();
$animal = $animalDao->buscarPorIdEUsuario((int) $id_animal, $_SESSION['id_usuario']);

if (!$animal) {
    header("Location: EditarRegistroVacinacao.php?id={$id}&erro=animal_nao_encontrado");
    exit();
}

if (!empty($animal['data_nascimento']) && $data < $animal['data_nascimento']) {
    header("Location: EditarRegistroVacinacao.php?id={$id}&erro=data_invalida");
    exit();
}


$registrovacinacao = new RegistroVacinacao($id_animal, $nome_vacina, $data, $aplicador, $dose);
$registrovacinacao->id_vacinacao = $id; 


$dao = new RegistroVacinacaoDAO();

if ($dao->Atualizar($registrovacinacao, $_SESSION['id_usuario'])) {
    
    header("Location: ListaRegistroVacinacao.php?sucesso=editado");
    exit();
} else {
    echo "Erro ao tentar atualizar os dados no banco.";
}