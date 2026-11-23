<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$usuarioDao = new UsuarioDao();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: MeuPerfil.php');
    exit();
}

$senha = $_POST['senha'] ?? null;
$confirmarSenha = $_POST['confirmar_senha'] ?? null;

if (!is_string($senha) || !is_string($confirmarSenha) || $senha === '' || $confirmarSenha === '') {
    header('Location: MeuPerfil.php?erro=exclusao_senha');
    exit();
}

if (!hash_equals($senha, $confirmarSenha)) {
    header('Location: MeuPerfil.php?erro=exclusao_senhas_diferentes');
    exit();
}

$usuario = $usuarioDao->BuscarPorId($id_usuario);
if (!$usuario || !password_verify($senha, $usuario['senha'])) {
    header('Location: MeuPerfil.php?erro=exclusao_senha_incorreta');
    exit();
}

if ($usuarioDao->Remover($id_usuario)) {
    session_unset();
    session_destroy();
    header('Location: ../index.php?conta=excluida');
    exit();
} else {
    header("Location: MeuPerfil.php?erro=exclusao");
    exit();
}
?>
