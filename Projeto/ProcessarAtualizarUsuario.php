<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';
require_once __DIR__ . '/../app/Model/Usuario.php';

$id      = $_POST['id_usuario']; 



$animal = new Animal($brinco, $idade, $especie, $raca, $data, $peso, $altura, $status);
$animal->id_animal = $id; 


$dao = new UsuarioDao();

if ($dao->Atualizar($)) {
    
    header("Location: MeuPerfil.php?sucesso=1");
    exit();
} else {
    echo "Erro ao tentar atualizar os dados no banco.";
}

?>