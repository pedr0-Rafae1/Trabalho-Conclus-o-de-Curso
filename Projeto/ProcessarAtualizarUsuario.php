<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Model/Usuario.php';
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $nome = $_POST['nome'] ?? '';
    $idade = $_POST['idade'] ?? 0;
    $novaSenha = $_POST['senha'] ?? null;

    $usuarioDao = new UsuarioDao();
    $dadosAtuais = $usuarioDao->BuscarPorId($id_usuario);

    $usuario = new Usuario($nome, $idade, $dadosAtuais['email'], $novaSenha, $dadosAtuais['tipo_usuario']);
    $usuario->id_usuario = $id_usuario;

    if ($usuarioDao->Atualizar($usuario)) {
        $_SESSION['usuario_nome'] = $nome;
        header("Location: MeuPerfil.php?sucesso=1");
    } else {
        header("Location: MeuPerfil.php?erro=1");
    }
    exit();
}