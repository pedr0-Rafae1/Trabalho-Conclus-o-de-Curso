<?php
require_once __DIR__ .  '/../app/Dao/UsuarioDao.php';
require_once __DIR__ .  '/../app/Model/Usuario.php';
require_once __DIR__ .  '/../app/Conexao/ConexaoBD.php';

$id_usuario = $_GET['id']; 
$dao = new UsuarioDao();
$usuario = $dao->BuscarPorId($id_usuario);

include 'Cabecalho.php';

?>

<div class="container mt-4"> 
    <h2>Cadastro do Animal</h2>
    <form action="ProcessarAtualizarUsuario.php" method="POST">
        <input type="hidden" name="id_usuario" value="<?= $usuario->id_usuario ?>">

        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" value="<?= $usuario->nome ?>">
        </div>

        <div class="mb-3">
            <label>Idade:</label>
            <input type="text" name="idade" class="form-control" value="<?= $usuario->idade ?>">
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="text" name="email" class="form-control" value="<?= $usuario->email ?>">
        </div>

        <div class="mb-3">
            <label>Senha:</label>
            <input type="text" name="senha" class="form-control" value="<?= $usuario->senha ?>">
        </div>

        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="MeuPerfil.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>