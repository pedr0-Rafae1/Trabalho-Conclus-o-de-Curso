<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

 require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Model/Animal.php';

$hoje = date('Y-m-d');

$id      = $_POST['id_animal']; 
$brinco  = $_POST['brinco'];
$especie = $_POST['especie'];
$raca    = $_POST['raca'];
$data    = $_POST['data_nascimento'];
$peso    = $_POST['peso'];
$altura  = $_POST['altura'];

if ($data > $hoje) {
    header("Location: EditarAnimal.php?id={$id}&erro=data_futura");
    exit();
}

// Calcula a idade em meses automaticamente a partir da data de nascimento
$nascimento = new DateTime($data);
$hoje_obj = new DateTime();
$diferenca = $nascimento->diff($hoje_obj);
$idade = ($diferenca->y * 12) + $diferenca->m;

$animal = new Animal($brinco, $idade, $especie, $raca, $data, $peso, $altura);
$animal->id_animal = $id; 


$dao = new AnimalDAO();

if ($dao->Atualizar($animal, $_SESSION['id_usuario'])) {
    header("Location: ListaAnimal.php?sucesso=editado");
    exit();
} else {
    header("Location: EditarAnimal.php?id={$id}&erro=banco");
    exit();
}