<?php 
include_once 'Sessao.php';
$id_logado = $_SESSION['id_usuario'];
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';

require_once __DIR__. '/../app/Dao/AnimalDao.php';
$animalDao = new AnimalDao();
$meusAnimais = $animalDao->ListarPorUsuario($id_logado);

include 'Cabecalho.php';
$hoje = date('Y-m-d');

$peso_anterior = 0;

if (isset($_GET['id'])) {
    $id_animal = intval($_GET['id']);
    $peso_anterior = $animalDao->ObterPesoAnterior($id_animal);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Controle de Peso</title>
  <link rel="stylesheet" href="../CSS/Formularios.css?v=1.4">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

  <main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-weight-hanging me-2"></i> Controle de Peso</h2>
        <form action="ProcessarPeso.php" method="POST">

            <label for="id">ID do Animal:</label>
              <select name="id_animal" id="id_animal" class="form-control" required>
                <option value="">-- Selecione o Animal --</option>
                  <?php foreach($meusAnimais as $animal): ?>
                <option value="<?= $animal->id_animal ?>">
                  <?= $animal->brinco ?> - <?= $animal->especie ?>
                </option>
                <?php endforeach; ?>
              </select>

            <label for="pan">Peso Anterior (kg):</label>
            <input type="text" step = "0.01" id="pan" name="peso_anterior" class = "form-control" value="<?= $peso_anterior ?>" readonly>

            <label for="pat">Peso Atual (kg):</label>
            <input type="text" id="pat" name="peso_atual" class = "form-control" required>

            <label for="data">Data da Pesagem:</label>
            <input type="date" id="data" name="data_pessagem" class = "form-control" max="<?= $hoje ?>" required>

            <button type="submit">Registrar Pesagem</button>
        </form>
    </div>
</main>

<script>
document.getElementById('id_animal').addEventListener('change', function() {
    var idAnimal = this.value;
    var inputPesoAnterior = document.getElementById('pan');

    if (idAnimal === '') {
        inputPesoAnterior.value = '0.00';
        return;
    }

    fetch('BuscarPesoAnterior.php?id_animal=' + idAnimal)
        .then(response => response.json())
        .then(data => {
            inputPesoAnterior.value = parseFloat(data.peso_anterior).toFixed(2);
        })
        .catch(error => {
            console.error('Erro ao buscar o peso:', error);
            inputPesoAnterior.value = '0.00';
        });
});
</script>

<script src="../Javascript/offline-sync.js"></script>

</body>
</html>