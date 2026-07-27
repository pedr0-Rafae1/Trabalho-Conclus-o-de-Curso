<?php
include_once 'Sessao.php';

require_once __DIR__ . "/../app/Dao/RegistroVacinacaoDao.php";

$id_logado = $_SESSION['id_usuario'];

require_once __DIR__. '/../app/Dao/AnimalDao.php';
$animalDao = new AnimalDao();
$meusAnimais = $animalDao->ListarPorUsuario($id_logado);
$hoje = date('Y-m-d');
include 'Cabecalho.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro de Vacinação Pecuária em Rede</title>
  <link rel="stylesheet" href="../CSS/Formularios.css?v = 1.3" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-syringe me-2"></i> Registro de Vacinação</h2>
        <form action="ProcessarVacinacao.php" method="POST">

            <label for="id">ID do Animal:</label>
              <select name="id_animal" id="id_animal" class="form-control" required>
                <option value="">-- Selecione o Animal --</option>
                  <?php foreach($meusAnimais as $animal): ?>
                <option value="<?= $animal->id_animal ?>">
                  <?= $animal->brinco ?> - <?= $animal->especie ?>
                </option>
                <?php endforeach; ?>
              </select>

            <label for="vacina">Nome da Vacina:</label>
            <input type="text" id="vacina" name="nome_vacina" class = "form-control" required>

            <label for="dt">Data de Aplicação:</label>
            <input type="date" id="dt" name="data_aplicacao" class = "form-control" max="<?= $hoje ?>" required>

            <label for="aplicacao">Aplicador Responsável:</label>
            <input type="text" id="aplicacao" name="aplicador" class = "form-control" required>

            <label for="Dose">Dose (ml/mg):</label>
            <input type="text" id="Dose" name="dose" class = "form-control" required>

            <button type="submit">Registrar Vacina</button>
        </form>
    </div>
</main>

<script src="../Javascript/Offline.js"></script>

</body>
</html>