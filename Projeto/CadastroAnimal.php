<?php
include_once 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

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
    <link rel="stylesheet" href="../CSS/Formularios.css?v=2.0">
</head>
<body>

<main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-plus-circle me-2"></i> Cadastro de Animal</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alerta-notificacao alerta-erro" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>
                    <?php
                        $mensagens = [
                            'data_futura' => 'Não é permitido cadastrar uma data futura.',
                            'brinco_invalido' => 'O brinco deve ser um número inteiro maior que zero.',
                            'peso_invalido' => 'O peso deve ser um número maior que zero.',
                            'altura_invalida' => 'A altura deve ser um número maior que zero.',
                            'banco' => 'Erro ao salvar no banco de dados. Tente novamente.'
                        ];
                        echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Erro ao processar o cadastro.');
                    ?>
                </span>
                <button type="button" class="fechar-alerta" aria-label="Fechar"><i class="fas fa-times"></i></button>
            </div>
        <?php endif; ?>

        <form action="ProcessarAnimal.php" method="POST">

            <label for="brinco">Brinco (Identificação):</label>
            <input type="number" id="brinco" name="brinco" class="form-control" placeholder="Ex: 1020" min="1" step="1" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <label for="especie">Espécie:</label>
            <input type="text" id="especie" name="especie" class="form-control" placeholder="Ex: Bovino" required>

            <label for="raca">Raça:</label>
            <input type="text" id="raca" name="raca" class="form-control" placeholder="Ex: Nelore" required>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" max="<?= $hoje ?>" required>

            <label for="peso">Peso (kg):</label>
            <input type="number" id="peso" name="peso" class="form-control" placeholder="Ex: 80.50" min="0.01" step="0.01" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <label for="altura">Altura (m):</label>
            <input type="number" id="altura" name="altura" class="form-control" placeholder="Ex: 2.00" min="0.01" step="0.01" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <button type="submit">Cadastrar Animal</button>
        </form>
    </div>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>