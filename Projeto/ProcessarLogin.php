<?php
session_start();
session_unset();    
session_destroy();  
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
        $_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? null;
        
        header("Location: home.php");
        exit();
    } else {
        header("Location: login.php?erro=senha_incorreta");
        exit();
    }
    
} else {
    header("Location: login.php?erro=usuario_nao_encontrado");
    exit();
}
?>