<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: home.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ .  '/../app/Dao/AnimalDao.php'; 
require_once __DIR__ .  '/../app/Model/Animal.php';
require_once __DIR__ .  '/../app/Conexao/ConexaoBD.php';

$animalDao = new AnimalDAO();

if (isset($_GET['excluir'])) {
    $idParaRemover = $_GET['excluir'];
    if ($animalDao->Remover($idParaRemover, $_SESSION['id_usuario'])) {
        header("Location: ListaAnimal.php?msg=excluido");
        exit();
    }
}
 
include 'Cabecalho.php'; 

$animal = $animalDao->ListarPorUsuario($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Rebanho - Pecuária em Rede</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Lista.css?v=2.1">
</head>
<body class="lista-pagina">

<main class="lista-conteudo">
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'excluido'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> Animal removido com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card lista-card">
        <div class="card-header lista-cabecalho">
            <h4 class="mb-0"><i class="fas fa-cow me-2"></i> Gestão de Rebanho</h4>
            <a href="CadastroAnimal.php" class="btn btn-light btn-sm fw-bold">
                <i class="fas fa-plus me-1"></i> Novo Animal
            </a>
        </div>
        
        <div class="card-body lista-corpo">
            <div class="table-responsive lista-tabela-wrap">
                <table class="table table-hover align-middle lista-tabela">
                    <thead>
                        <tr>
                            <th class="ps-4">Identificador do Animal</th>
                            <th>Brinco</th>
                            <th>Idade</th>
                            <th>Espécie</th>
                            <th>Raça</th>
                            <th>Data de Nascimento</th>
                            <th>Peso (kg)</th>
                            <th>Altura (m)</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($animal) > 0): ?>
                            <?php foreach($animal as $a): ?>
                                <tr>
                                    <td>#<?= $a->id_animal ?></td>
                                    <td><span><?= $a->brinco ?></span></td>
                                    <td><?= $a->idade ?> anos</td>
                                    <td><span><?= $a->especie ?></span></td>
                                    <td><span><?= $a->raca ?></span></td>
                                    <td><span><?= $a->data_nascimento ?></span></td>
                                    <td><?= $a->peso ?></td>
                                    <td><?= $a->altura ?></td>
                                    <td class="lista-acoes pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="FichaAnimal.php?id=<?= $a->id_animal ?>" class="btn btn-outline-info btn-sm" title="Ver Ficha">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                            <a href="EditarAnimal.php?id=<?= $a->id_animal ?>" class="btn btn-outline-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="ListaAnimal.php?excluir=<?= $a->id_animal?>" 
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
                                <td colspan="9" class="lista-vazia text-center">
                                    <i class="fas fa-search text-muted fa-3x mb-3"></i>
                                    <p class="text-muted">Nenhum animal cadastrado no seu rebanho.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'Rodape.php'; ?>
</body>
</html>