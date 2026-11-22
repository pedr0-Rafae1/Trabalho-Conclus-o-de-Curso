<?php
include_once 'Sessao.php';

if ((int) ($_SESSION['admin'] ?? 0) !== 1) {
    header("Location: Pecuarista.php?erro=acesso_negado");
    exit();
}

require_once __DIR__ . '/../app/Dao/UsuarioDao.php';

$usuarioDao = new UsuarioDao();
$veterinariosPendentes = $usuarioDao->ListarVeterinariosPendentes();

include 'Cabecalho.php';
?>

<head>
    <title>Painel Administrativo - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Lista.css?v=2.1">
</head>

<main class="lista-conteudo">
    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> Veterinário homologado com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card lista-card">
        <div class="card-header lista-cabecalho">
            <h4><i class="fas fa-user-shield me-2"></i> Homologação de Veterinários</h4>
        </div>

        <div class="card-body lista-corpo">
            <?php if (empty($veterinariosPendentes)): ?>
                <div class="lista-vazia">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <p>Nenhum veterinário aguardando homologação.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive lista-tabela-wrap">
                    <table class="table table-hover align-middle lista-tabela">
                        <thead>
                            <tr>
                                <th class="ps-4">Nome</th>
                                <th>E-mail</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($veterinariosPendentes as $vet): ?>
                                <tr>
                                    <td class="ps-4"><?= htmlspecialchars($vet['nome']) ?></td>
                                    <td><?= htmlspecialchars($vet['email']) ?></td>
                                    <td class="text-center">
                                        <form method="POST" action="ProcessarHomologacao.php" style="display: inline;" onsubmit="return confirm('Confirma a homologação deste veterinário?');">
                                            <input type="hidden" name="id_usuario" value="<?= (int) $vet['id_usuario'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check me-1"></i> Homologar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>
