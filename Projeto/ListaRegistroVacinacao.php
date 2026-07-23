<?php
include_once 'Sessao.php';
require_once  __DIR__ .  '/../app/Dao/RegistroVacinacaoDao.php'; 
require_once  __DIR__ .  '/../app/Model/RegistroVacinacao.php';
require_once  __DIR__ .  '/../app/Conexao/ConexaoBD.php';

$registrovacinacaoDao = new RegistroVacinacaoDao();

if (isset($_GET['excluir'])) {
    $idParaRemover = $_GET['excluir'];
    if ($registrovacinacaoDao->Remover($idParaRemover)) {
        header("Location: ListaRegistroVacinacao.php?msg=excluido");
        exit();
    }
}

include 'Cabecalho.php';

$registrovacinacao = $registrovacinacaoDao->ListarPorUsuario($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Lista.css">
</head>

<body class="bg-light">

<main class="container-fluid px-md-5 my-5">
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'excluido'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> Registro da vacinação removida com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="mb-0"><i class="fas fa-cow me-2"></i> Vacinação</h4>
            <a href="RegistrarVacinacao.php" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-plus me-1"></i> Novo Registro de Vacinacao
            </a>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Identificador da vacina</th>
                            <th>O animal escolhido</th>
                            <th>Nome da Vacina</th>
                            <th>Aplicador</th>
                            <th>Data da Aplicacao</th>
                            <th>Dose</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($registrovacinacao) > 0): ?>
                            <?php foreach($registrovacinacao as $rv): ?>
                                <tr>
                                    <td >#<?= $rv->id_vacinacao?></td>
                                    <td><span><?= $rv->id_animal?></span></td>
                                    <td><?= $rv->nome_vacina?> </td>
                                    <td><span><?= $rv->aplicador ?></span></td>
                                    <td><span><?= $rv->data_aplicacao ?></span></td>
                                    <td><?= $rv->dose?></td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="EditarRegistroVacinacao.php?id=<?= $rv->id_vacinacao ?>" class="btn btn-outline-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="ListaRegistroVacinacao.php?excluir=<?= $rv->id_vacinacao?>" 
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
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-search text-muted fa-3x mb-3"></i>
                                    <p class="text-muted">Nenhum Registro de Vacinação encontrado no sistema</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

</body>
</html>