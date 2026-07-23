<?php
include_once 'Sessao.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';
include 'Cabecalho.php';
$hoje = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro Animal - Pecuária em Rede</title>
    <link rel="stylesheet" href="../CSS/Formularios.css?v=1.4">
</head>
<body>

<main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-plus-circle me-2"></i> Cadastro de Animal</h2>
        <form action="ProcessarAnimal.php" method="POST">

            <label for="brinco">Brinco (Identificação):</label>
            <input type="text" id="brinco" name="brinco" class="form-control" placeholder="Ex: 1020" required>

            <label for="idade">Idade (Meses):</label>
            <input type="number" id="idade" name="idade" class="form-control" placeholder="Ex: 50" required>

            <label for="especie">Espécie:</label>
            <input type="text" id="especie" name="especie" class="form-control" placeholder="Ex: Bovino" required>

            <label for="raca">Raça:</label>
            <input type="text" id="raca" name="raca" class="form-control" placeholder="Ex: Nelore" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" max="<?= $hoje ?>" required>

            <label for="peso">Peso (kg):</label>
            <input type="text" id="peso" name="peso" class="form-control" placeholder="Ex: 80.50" required>

            <label for="altura">Altura (m):</label>
            <input type="text" id="altura" name="altura" class="form-control" placeholder="Ex: 2.00" required>

            <button type="submit">Cadastrar Animal</button>
        </form>
    </div>
</main>

</body>
</html>