<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Model/RegistroPeso.php';

$hoje = date('Y-m-d');

if ($data > $hoje) {
    echo "Não pode cadastrar uma data futura";
    exit();
}

$id = $_POST['id_peso']; 
$id_animal = $_POST['id_animal'];
$peso_anterior = $_POST['peso_anterior'];
$peso_atual = $_POST['peso_atual'];
$data = $_POST['data_pessagem'];

$registropeso = new RegistroPeso($id_animal, $peso_anterior, $peso_atual, $data);
$registropeso->id_peso = $id; 


$dao = new RegistroPesoDAO();

if ($dao->Atualizar($registropeso, $_SESSION['id_usuario'])) {
    
    header("Location: ListaRegistroPeso.php?sucesso=1");
    exit();
} else {
    echo "Erro ao tentar atualizar os dados no banco.";
}