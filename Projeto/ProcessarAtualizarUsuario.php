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

        if (!empty($_FILES['foto']['name'])) {
            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
            $tipoEnviado = mime_content_type($_FILES['foto']['tmp_name']);

            if (in_array($tipoEnviado, $tiposPermitidos) && $_FILES['foto']['size'] <= 3 * 1024 * 1024) {
                $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $nomeArquivo = 'usuario_' . $id_usuario . '_' . time() . '.' . $extensao;
                $destino = __DIR__ . '/../imagem/' . $nomeArquivo;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    if (!empty($dadosAtuais['foto_perfil'])) {
                        @unlink(__DIR__ . '/../imagem/' . $dadosAtuais['foto_perfil']);
                    }
                    $usuarioDao->AtualizarFoto($id_usuario, $nomeArquivo);
                    $_SESSION['foto_perfil'] = $nomeArquivo;
                }
            }
        }

        header("Location: MeuPerfil.php?sucesso=1");
    } else {
        header("Location: MeuPerfil.php?erro=1");
    }
    exit();
}