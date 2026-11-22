<?php

include  'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Model/Animal.php';
require_once __DIR__ . '/../app/Conexao/ConexaoBD.php';

$id_animal = $_GET['id']; 
$dao = new AnimalDAO();
$animal = $dao->BuscarPorId($id_animal);

if (!$animal) {
    die("O cadastro do seu animal não foi encontrado ou não pertence à sua conta.");
}

include 'Cabecalho.php';

$hoje = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Animal</title>
    <link rel="stylesheet" href="../CSS/Formularios.css">
</head>
<body>

<main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-edit me-2"></i> Editar Animal</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alerta-notificacao alerta-erro" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>
                    <?php
                        $mensagens = [
                            'data_futura' => 'Não é permitido cadastrar uma data futura.',
                            'banco' => 'Erro ao atualizar no banco de dados. Tente novamente.'
                        ];
                        echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Erro ao processar a edição.');
                    ?>
                </span>
                <button type="button" class="fechar-alerta" aria-label="Fechar"><i class="fas fa-times"></i></button>
            </div>
        <?php endif; ?>

        <form action="ProcessarAtualizarAnimal.php" method="POST">
            <input type="hidden" name="id_animal" value="<?= $animal->id_animal ?>">

            <label >Brinco (Identificação):</label>
            <input type="number" name="brinco" class="form-control" value="<?= $animal->brinco ?>" min="1" step="1" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <label for="especie">Espécie:</label>
            <input type="text" name="especie" class="form-control" value="<?= $animal->especie ?>" required>

            <label for="raca">Raça:</label>
            <input type="text" name="raca" class="form-control" value="<?= $animal->raca ?>" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" name="data_nascimento" class="form-control" value="<?= $animal->data_nascimento ?>" max="<?= $hoje ?>" required>

            <label for="peso">Peso (kg):</label>
            <input type="number" name="peso" class="form-control" value="<?= $animal->peso ?>" min="0.01" step="0.01" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <label for="altura">Altura (m):</label>
            <input type="number" name="altura" class="form-control" value="<?= $animal->altura ?>" min="0.01" step="0.01" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <div class="form-acoes">
                <button type="submit">Salvar Alterações</button>
                <a href="ListaAnimal.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</main>
    
<?php include 'Rodape.php'; ?>
</body>
</html>
