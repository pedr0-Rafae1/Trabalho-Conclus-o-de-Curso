<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/RegistroVacinacaoDao.php';
require_once __DIR__ . '/../app/Model/RegistroVacinacao.php';

$hoje = date('Y-m-d');

if ($data > $hoje) {
    echo "Não pode cadastrar uma data futura";
    exit();
}

$id = $_POST['id_vacinacao']; 
$id_animal = $_POST['id_animal'];
$nome_vacina = $_POST['nome_vacina'];
$data = $_POST['data_aplicacao'];
$aplicador = $_POST['aplicador'];
$dose  = $_POST['dose'];


$registrovacinacao = new RegistroVacinacao($id_animal, $nome_vacina, $data, $aplicador, $dose);
$registrovacinacao->id_vacinacao = $id; 


$dao = new RegistroVacinacaoDAO();

if ($dao->Atualizar($registrovacinacao, $_SESSION['id_usuario'])) {
    
    header("Location: ListaRegistroVacinacao.php?sucesso=1");
    exit();
} else {
    echo "Erro ao tentar atualizar os dados no banco.";
}