<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: home.php?erro=area_pecuarista");
    exit();
}

require_once  __DIR__ .  '/../app/Dao/RegistroPesoDao.php'; 
require_once  __DIR__ .  '/../app/Model/RegistroPeso.php';
require_once  __DIR__ .  '/../app/Conexao/ConexaoBD.php';

$registropesoDao = new RegistroPesoDao();

if (isset($_GET['excluir'])) {
    $idParaRemover = $_GET['excluir'];
    if ($registropesoDao->Remover($idParaRemover, $_SESSION['id_usuario'])) {
        header("Location: ListaRegistroPeso.php?msg=excluido");
        exit();
    }
}

include 'Cabecalho.php';
 
$registropeso = $registropesoDao->ListarPorUsuario($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>RPP - Lista de Animais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Lista.css?v=2.0">
</head>
<body class="lista-pagina">

<main class="lista-conteudo">
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'excluido'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> Registro da pesagem removido com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card lista-card">
        <div class="card-header lista-cabecalho">
            <h4 class="mb-0"><i class="fas fa-cow me-2"></i> Pesagem</h4>
            <a href="ControlePeso.php" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-plus me-1"></i> Novo Registro de peso
            </a>
        </div>
        
        <div class="card-body lista-corpo">
            <div class="table-responsive lista-tabela-wrap">
                <table class="table table-hover align-middle lista-tabela">
                    <thead>
                        <tr>
                            <th class="ps-4">Identificador do peso</th>
                            <th>O animal escolhido</th>
                            <th>peso anterior</th>
                            <th>peso atual</th>
                            <th>Data de pessagem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($registropeso) > 0): ?>
                            <?php foreach($registropeso as $rp): ?>
                                <tr>
                                    <td>#<?= $rp->id_peso?></td>
                                    <td><span><?= $rp->id_animal?></span></td>
                                    <td><?= $rp->peso_anterior?> </td>
                                    <td><span><?= $rp->peso_atual ?></span></td>
                                    <td><span><?= $rp->data_pessagem ?></span></td>
                                    <td class="lista-acoes pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="EditarRegistroPeso.php?id=<?= $rp->id_peso ?>" class="btn btn-outline-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="ListaRegistroPeso.php?excluir=<?= $rp->id_peso?>" 
                                               class="btn btn-outline-danger btn-sm" 
                                               onclick="return confirm('Deseja realmente remover este animal?')"
                                               title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="lista-vazia text-center">
                                    <i class="fas fa-search text-muted fa-3x mb-3"></i>
                                    <p class="text-muted">Nenhum Registro de Peso encontrado no sistema</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>