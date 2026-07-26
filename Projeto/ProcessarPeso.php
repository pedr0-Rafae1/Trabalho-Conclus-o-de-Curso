<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Model/RegistroPeso.php'; 
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';

$id_dono = $_SESSION['usuario_id']; 

$hoje = date('Y-m-d');

if ($data > $hoje) {
    echo "Não pode cadastrar uma data futura";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_animal  = $_POST['id_animal'];
    $peso_anterior  = $_POST['peso_anterior'];
    $peso_atual = $_POST['peso_atual'];
    $data = $_POST['data_pessagem'];
    

    $registrarpeso = new RegistroPeso($id_animal, $peso_anterior, $peso_atual, $data);

    $dao = new RegistroPesoDAO();
    
    if ($dao->Cadastrar($registrarpeso)) {

        echo "<script>
                alert('registro realizado com sucesso!');
                window.location.href = 'ListaRegistroPeso.php'; 
              </script>";
    } else {

        echo "Erro ao salvar no banco de dados.";
    }
} else {
    
    header("Location: ControlePeso.php");
}

?>