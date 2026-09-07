<?php
session_start();

require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$usuario = $usuarioDao->buscarPorEmail($email);

if ($usuario) {
    if (password_verify($senha, $usuario['senha'])) {
        
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'] ?? 'Pecuarista';
        $_SESSION['homologado'] = $usuario['homologado'] ?? 0;
        
        header("Location: ../Projeto/home.php");
        exit();
    } else {
        header("Location: ../Projeto/login.php?erro=senha_incorreta");
        exit();
    }
    
} else {
    header("Location: ../Projeto/login.php?erro=usuario_nao_encontrado");
    exit();
}
?>