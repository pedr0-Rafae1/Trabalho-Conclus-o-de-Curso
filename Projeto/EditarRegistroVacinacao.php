<?php

include 'Sessao.php';

if (($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario') {
    header("Location: Pecuarista.php?erro=area_pecuarista");
    exit();
}

require_once __DIR__ .  '/../app/Dao/RegistroVacinacaoDao.php';
require_once __DIR__ .  '/../app/Model/RegistroVacinacao.php';
require_once __DIR__ .  '/../app/Conexao/ConexaoBD.php';

$id_vacinacao = $_GET['id']; 
$dao = new RegistroVacinacaoDao();
$registrovacinacao = $dao->BuscarPorId($id_vacinacao, $_SESSION['id_usuario']);
$hoje = date('Y-m-d');
include 'Cabecalho.php';

if (!$registrovacinacao) {
    die("Seu registro da vacinação não foi encontrado ou não pertence à sua conta.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro Vacinacao do Animal</title>
    <link rel="stylesheet" href="../CSS/Formularios.css">
</head>
<body>

 <main class="container mt-5">
    <div class="card card-formulario shadow-sm">
        <h2><i class="fas fa-edit me-2"></i> Editar Vacinação</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alerta-notificacao alerta-erro" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span>
                    <?php
                        $mensagens = [
                            'data_futura' => 'Não é permitido cadastrar uma data futura.',
                            'data_invalida' => 'A data de aplicação não pode ser anterior à data de nascimento do animal.',
                            'animal_nao_encontrado' => 'Animal não encontrado.',
                            'dose_invalida' => 'A dose deve ser um valor numérico maior que zero.',
                            'banco' => 'Erro ao atualizar no banco de dados. Tente novamente.'
                        ];
                        echo htmlspecialchars($mensagens[$_GET['erro']] ?? 'Erro ao processar a edição.');
                    ?>
                </span>
                <button type="button" class="fechar-alerta" aria-label="Fechar"><i class="fas fa-times"></i></button>
            </div>
        <?php endif; ?>

        <form action="ProcessarAtualizarRegistroVacinacao.php" method="POST">
            <input type="hidden" name="id_vacinacao" value="<?= $registrovacinacao->id_vacinacao ?>">

            <label >Id do animal:</label>
            <input type="text" name="id_animal" class="form-control" value="<?= $registrovacinacao->id_animal ?>" required>

            <label >Nome da Vacina:</label>
            <input type="text" name="nome_vacina" class="form-control" value="<?= $registrovacinacao->nome_vacina ?>" required>

            <label >Data de Aplicação:</label>
            <input type="date" name="data_aplicacao" class="form-control" value="<?= $registrovacinacao->data_aplicacao ?>" max="<?= $hoje ?>" required>

            <label >Aplicador Responsável:</label>
            <input type="text" name="aplicador" class="form-control" value="<?= $registrovacinacao->aplicador ?>" required>

            <label >Dose (ml/mg):</label>
            <input type="number" name="dose" class="form-control" value="<?= $registrovacinacao->dose ?>" min="1" step="1" onkeydown="return ['e', 'E', '-', '+'].includes(event.key) ? false : true" required>

            <div class="form-acoes">
                <button type="submit">Salvar Alterações</button>
                <a href="ListaRegistroVacinacao.php" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>
</main>
    
<?php include 'Rodape.php'; ?>
</body>
</html>

