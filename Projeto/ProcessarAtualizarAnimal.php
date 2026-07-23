<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Model/Animal.php';

$id      = $_POST['id_animal']; 
$brinco  = $_POST['brinco'];
$idade   = $_POST['idade'];
$especie = $_POST['especie'];
$raca    = $_POST['raca'];
$data    = $_POST['data_nascimento'];
$peso    = $_POST['peso'];
$altura  = $_POST['altura'];

$animal = new Animal($brinco, $idade, $especie, $raca, $data, $peso, $altura);
$animal->id_animal = $id; 


$dao = new AnimalDAO();

if ($dao->Atualizar($animal)) {
    
    header("Location: ListaAnimal.php?sucesso=1");
    exit();
} else {
    echo "Erro ao tentar atualizar os dados no banco.";
}