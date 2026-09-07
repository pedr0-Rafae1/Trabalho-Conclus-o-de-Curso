<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

require_once "../app/Conexao/ConexaoBD.php";
require_once "../app/Dao/AnimalDao.php";
require_once "../app/Dao/RegistroPesoDao.php";
require_once "../app/Dao/RegistroVacinacaoDao.php";
require_once "../app/Dao/AtendimentoDao.php";
require_once "../app/Dao/VendaDao.php";

$id_usuario = $_SESSION['id_usuario'];
$id_animal = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_animal) {
    header("Location: ListagemAnimais.php?erro=id_invalido");
    exit();
}

$animalDao = new AnimalDao();

$animal = $animalDao->buscarPorIdEUsuario($id_animal, $id_usuario);

if (!$animal) {
    header("Location: ListagemAnimais.php?erro=nao_encontrado");
    exit();
}

$registroPesoDao = new RegistroPesoDao();
$pesagens = method_exists($registroPesoDao, 'buscarPorAnimal') ? $registroPesoDao->buscarPorAnimal($id_animal) : [];

$vacinacaoDao = new RegistroVacinacaoDao();
$vacinas = method_exists($vacinacaoDao, 'buscarPorAnimal') ? $vacinacaoDao->buscarPorAnimal($id_animal) : [];

$atendimentoDao = new AtendimentoDao();
$atendimentos = method_exists($atendimentoDao, 'buscarPorAnimal') ? $atendimentoDao->buscarPorAnimal($id_animal) : [];

$vendaDao = new VendaDao();
$venda = method_exists($vendaDao, 'buscarPorAnimal') ? $vendaDao->buscarPorAnimal($id_animal) : null;

$idadeFormatada = "Não informada";
if (!empty($animal['data_nascimento'])) {
    $nasc = new DateTime($animal['data_nascimento']);
    $hoje = new DateTime();
    $diferenca = $hoje->diff($nasc);
    if ($diferenca->y > 0) {
        $idadeFormatada = $diferenca->y . " ano(s) e " . $diferenca->m . " mês(es)";
    } else {
        $idadeFormatada = $diferenca->m . " mês(es)";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha Individual - <?php echo htmlspecialchars($animal['nome'] ?? 'Brinco ' . $animal['brinco']); ?></title>
    <link rel="stylesheet" href="../CSS/pecuaria.css">
    <link rel="stylesheet" href="../CSS/FichaAnimal.css">
</head>
<body>

    <main class="container-ficha">
       
        <div class="ficha-header">
            <div class="ficha-titulo">
                <h2>Ficha Individual do Animal</h2>
                <h1><?php echo htmlspecialchars($animal['nome'] ?? 'Sem Nome'); ?> <small>(Brinco: #<?php echo htmlspecialchars($animal['brinco']); ?>)</small></h1>
                <span class="badge-status status-<?php echo strtolower($animal['status'] ?? 'ativo'); ?>">
                    <?php echo htmlspecialchars($animal['status'] ?? 'Ativo'); ?>
                </span>
            </div>
            
            <div class="ficha-acoes">
                <a href="ControlePeso.php?id_animal=<?php echo $id_animal; ?>" class="btn-acao">+ Novo Peso</a>
                <a href="RegistrarVacinacao.php?id_animal=<?php echo $id_animal; ?>" class="btn-acao">+ Vacina</a>
                <a href="CadastroAnimal.php?id=<?php echo $id_animal; ?>" class="btn-acao btn-secundario">Editar Dados</a>
                <a href="ListaAnimal.php" class="btn-voltar">← Voltar à Lista</a>
            </div>
        </div>

        <div class="grid-informacoes">
            <div class="card-info">
                <h3>Dados de Identificação</h3>
                <ul>
                    <li><strong>Identificação/Brinco:</strong> <?php echo htmlspecialchars($animal['brinco']); ?></li>
                    <li><strong>Nome:</strong> <?php echo htmlspecialchars($animal['nome'] ?? 'N/A'); ?></li>
                    <li><strong>Sexo:</strong> <?php echo htmlspecialchars($animal['sexo'] ?? 'N/A'); ?></li>
                    <li><strong>Raça:</strong> <?php echo htmlspecialchars($animal['raca'] ?? 'N/A'); ?></li>
                    <li><strong>Nascimento:</strong> <?php echo !empty($animal['data_nascimento']) ? date('d/m/Y', strtotime($animal['data_nascimento'])) : 'N/A'; ?></li>
                    <li><strong>Idade Aproximada:</strong> <?php echo $idadeFormatada; ?></li>
                </ul>
            </div>

            <div class="card-info">
                <h3>Resumo Zootécnico</h3>
                <div class="resumo-metricas">
                    <div class="metrica">
                        <span class="label">Último Peso</span>
                        <span class="valor">
                            <?php 
                                if (!empty($pesagens)) {
                                    echo htmlspecialchars($pesagens[0]['peso']) . " kg";
                                } else {
                                    echo "Sem registro";
                                }
                            ?>
                        </span>
                    </div>
                    <div class="metrica">
                        <span class="label">Total de Vacinas</span>
                        <span class="valor"><?php echo count($vacinas); ?></span>
                    </div>
                    <div class="metrica">
                        <span class="label">Atendimentos Vet.</span>
                        <span class="valor"><?php echo count($atendimentos); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="secao-historicos">
            
            <div class="bloco-historico">
                <h3>Histórico de Pesagens</h3>
                <?php if (!empty($pesagens)): ?>
                    <table class="tabela-dados">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Peso (kg)</th>
                                <th>Observação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pesagens as $p): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($p['data_pesagem'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($p['peso']); ?> kg</strong></td>
                                    <td><?php echo htmlspecialchars($p['observacao'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="sem-registro">Nenhum registro de peso encontrado para este animal.</p>
                <?php endif; ?>
            </div>

            <div class="bloco-historico">
                <h3>Histórico de Vacinações</h3>
                <?php if (!empty($vacinas)): ?>
                    <table class="tabela-dados">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Vacina</th>
                                <th>Dose / Lote</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vacinas as $v): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($v['data_aplicacao'])); ?></td>
                                    <td><?php echo htmlspecialchars($v['nome_vacina']); ?></td>
                                    <td><?php echo htmlspecialchars($v['dose'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="sem-registro">Nenuma vacina registrada para este animal.</p>
                <?php endif; ?>
            </div>

            <div class="bloco-historico">
                <h3>Atendimentos Veterinários</h3>
                <?php if (!empty($atendimentos)): ?>
                    <table class="tabela-dados">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Motivo / Diagnóstico</th>
                                <th>Tratamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($atendimentos as $a): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($a['data_atendimento'])); ?></td>
                                    <td><?php echo htmlspecialchars($a['diagnostico'] ?? $a['motivo']); ?></td>
                                    <td><?php echo htmlspecialchars($a['tratamento'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="sem-registro">Nenhum atendimento registrado.</p>
                <?php endif; ?>
            </div>

            <?php if($venda): ?>
                <div class="bloco-historico destaque-venda">
                    <h3>Dados de Comercialização / Venda</h3>
                    <p><strong>Data da Venda:</strong> <?php echo date('d/m/Y', strtotime($venda['data_venda'])); ?></p>
                    <p><strong>Comprador:</strong> <?php echo htmlspecialchars($venda['comprador'] ?? 'Não informado'); ?></p>
                    <p><strong>Valor:</strong> R$ <?php echo number_format($venda['valor'], 2, ',', '.'); ?></p>
                </div>
            <?php endif; ?>

        </div>
    </main>

</body>
</html>