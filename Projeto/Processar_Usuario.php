<?php

require_once __DIR__ .  '/../app/Model/Usuario.php'; 
require_once __DIR__ .  '/../app/Dao/UsuarioDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome   = $_POST['nome'];
    $idade  = $_POST['idade'];
    $email  = $_POST['email'];
    $senha  = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'] ?? 'Pecuarista';
    
    $novoUsuario = new Usuario($nome, $idade, $email, $senha, $tipo_usuario);

    $dao = new UsuarioDAO();
    
    if ($dao->Cadastrar($novoUsuario)) {

        echo "<script>
                alert('Cadastro realizado com sucesso!');
                window.location.href = 'login.php?cadastro=sucesso'; 
              </script>";
    } else {

        echo "Erro ao salvar no banco de dados.";
    }
} else {
    
    header("Location: CadastroUsuario.php");
}
?>