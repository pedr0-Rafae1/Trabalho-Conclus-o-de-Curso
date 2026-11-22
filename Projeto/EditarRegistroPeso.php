<?php

include 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

include 'Cabecalho.php';
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Model/RegistroPeso.php';
require_once __DIR__ . '/../app/Conexao/ConexaoBD.php';

$id_peso = $_GET['id']; 
$dao = new RegistroPesoDao();
$registropeso = $dao->BuscarPorId($id_peso, $_SESSION['id_usuario']);
$hoje = date('Y-m-d');

if (!$registropeso) {
    die("Seu Registro da pesagem não foi encontrado ou não pertence à sua conta.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/Formularios.css">
</head>
<body>

  <main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-edit me-2"></i> Editar Pesagem</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alerta-notificacao alerta-erro" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>
                    <?php
                        $mensagens = [
                            'data_futura' => 'Não é permitido cadastrar uma data futura.',
                            'data_invalida' => 'A data da pesagem não pode ser anterior à data de nascimento do animal.',
                            'animal_nao_encontrado' => 'Animal não encontrado.',
                            'peso_invalido' => 'O peso deve ser um número maior que zero.',
                            'banco' => 'Erro ao atualizar no banco de dados. Tente novamente.'
                        ];
                        echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Erro ao processar a edição.');
                    ?>
                </span>
                <button type="button" class="fechar-alerta" aria-label="Fechar"><i class="fas fa-times"></i></button>
            </div>
        <?php endif; ?>

        <form action="ProcessarAtualizarRegistroPeso.php" method="POST">
            <input type="hidden" name="id_peso" value="<?= $registropeso->id_peso ?>">

            <label >ID do Animal:</label>
            <input type="text" name="id_animal" class="form-control" value="<?= $registropeso->id_animal ?>" required>

            <label >Peso Anterior (kg):</label>
            <input type="text" name="peso_anterior" class="form-control" value="<?= $registropeso->peso_anterior ?>" required>

            <label >Peso Atual (Kg):</label>
            <input type="number" name="peso_atual" class="form-control" value="<?= $registropeso->peso_atual ?>" min="0.01" step="0.01" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <label >Data da Pesagem:</label>
            <input type="date" name="data_pessagem" class="form-control" value="<?= $registropeso->data_pessagem ?>" max="<?= $hoje ?>" required>

            <div class="form-acoes">
                <button type="submit">Salvar Alterações</button>
                <a href="ListaRegistroPeso.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</main>
    
<?php include 'Rodape.php'; ?>
</body>
</html>