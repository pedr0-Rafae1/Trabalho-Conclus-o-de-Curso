<?php

include  'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: home.php?erro=area_pecuarista");
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
        <h2><i class="fas fa-plus-circle me-2"></i> Cadastro de Animal</h2>
        <form action="ProcessarAtualizarAnimal.php" method="POST">
            <input type="hidden" name="id_animal" value="<?= $animal->id_animal ?>">

            <label >Brinco (Identificação):</label>
            <input type="text" name="brinco" class="form-control" value="<?= $animal->brinco ?>" required>

            <label for="idade">Idade (Meses):</label>
            <input type="number" name="idade" class="form-control" value="<?= $animal->idade ?>" required>

            <label for="especie">Espécie:</label>
            <input type="text" name="especie" class="form-control" value="<?= $animal->especie ?>" required>

            <label for="raca">Raça:</label>
            <input type="text" name="raca" class="form-control" value="<?= $animal->raca ?>" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" name="data_nascimento" class="form-control" value="<?= $animal->data_nascimento ?>" max="<?= $hoje ?>" required>

            <label for="peso">Peso (kg):</label>
            <input type="text" name="peso" class="form-control" value="<?= $animal->peso ?>" required>

            <label for="altura">Altura (m):</label>
            <input type="text" name="altura" class="form-control" value="<?= $animal->altura ?>" required>

            <button type="submit">Salvar Alterações</button>
            <a href="ListaRegistroPeso.php" >Cancelar</a>
        </form>
    </div>
</main>
    
</body>
</html>
