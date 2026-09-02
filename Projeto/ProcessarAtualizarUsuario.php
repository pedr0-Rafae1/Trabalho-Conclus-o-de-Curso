<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';
require_once __DIR__ . '/../app/Model/Usuario.php';

$id = $_POST['id_usuario'];
$nome = $_POST['nome'];
$idade = $_POST['idade'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$usuario = new Usuario($nome, $idade, $email, $senha);
$usuario->id_usuario = $id; 


$dao = new UsuarioDao();

if ($dao->Atualizar($usuario, $_SESSION['id_usuario'])) {
    
    header("Location: MeuPerfil.php?sucesso=1");
    exit();
} else {
    echo "Erro ao tentar atualizar os dados no banco.";
}

?>