<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();
$usuario = $usuarioDao->BuscarPorId($_SESSION['id_usuario']);

include 'Cabecalho.php';
?>

<head>
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="../CSS/Formularios.css?v=1.4">
    <style>
        .perfil-avatar {
            width: 90px; height: 90px; background: var(--verde-fundo-claro);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px; color: var(--verde-primario); font-size: 2.5rem;
        }
    </style>
</head>

<main class="container mt-5">
    <div class="card card-formulario shadow-sm">

        <div class="text-center">
            <div class="perfil-avatar"><i class="fas fa-user"></i></div>
            <h2 class="mb-1"><?= htmlspecialchars($usuario['nome']) ?></h2>
            <p class="text-muted mb-3">
                <?= $usuario['tipo_usuario'] === 'Veterinario' ? 'Veterinário' : 'Pecuarista' ?>
                <?php if ($usuario['tipo_usuario'] === 'Veterinario'): ?>
                    <?php if ((int) $usuario['homologado'] === 1): ?>
                        <span class="badge bg-success">Homologado</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">Aguardando homologação</span>
                    <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>

        <?php if (isset($_GET['sucesso'])): ?>
            <div class="alert alert-success">Perfil atualizado com sucesso!</div>
        <?php elseif (isset($_GET['erro'])): ?>
            <div class="alert alert-danger">Não foi possível atualizar. Verifique os campos.</div>
        <?php endif; ?>

        <form action="ProcessarAtualizarUsuario.php" method="POST">

            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>

            <label for="idade">Idade:</label>
            <input type="number" id="idade" name="idade" class="form-control" value="<?= (int) $usuario['idade'] ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
            <small class="text-muted">O e-mail não pode ser alterado.</small>

            <label for="senha" class="mt-3">Nova senha:</label>
            <input type="password" id="senha" name="senha" class="form-control" placeholder="Deixe em branco para manter a senha atual">

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</main>

</body>
</html>