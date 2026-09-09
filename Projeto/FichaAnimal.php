<?php
include_once 'Sessao.php';

require_once __DIR__ . "/../app/Dao/AnimalDao.php";
require_once __DIR__ . "/../app/Dao/RegistroPesoDao.php";
require_once __DIR__ . "/../app/Dao/RegistroVacinacaoDao.php";
require_once __DIR__ . "/../app/Dao/AtendimentoDao.php";
require_once __DIR__ . "/../app/Dao/VendaDao.php";

$id_usuario = $_SESSION['id_usuario'];
$ehVeterinario = ($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario';
$id_animal = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_animal) {
    header("Location: " . ($ehVeterinario ? "HistoricoAtendimento.php" : "ListaAnimal.php") . "?erro=id_invalido");
    exit();
}

$animalDao = new AnimalDao();

if ($ehVeterinario) {
    
    $animal = $animalDao->buscarPorIdQualquerDono($id_animal);
} else {

    $animal = $animalDao->buscarPorIdEUsuario($id_animal, $id_usuario);
}

if (!$animal) {
    header("Location: " . ($ehVeterinario ? "HistoricoAtendimento.php" : "ListaAnimal.php") . "?erro=nao_encontrado");
    exit();
}

$id_dono = $ehVeterinario ? $animal['id_usuario'] : $id_usuario;

$pesagens = (new RegistroPesoDao())->ListarPorAnimal($id_animal, $id_dono);
$vacinas = (new RegistroVacinacaoDao())->ListarPorAnimal($id_animal, $id_dono);
$atendimentos = (new AtendimentoDao())->ListarPorAnimal($id_animal, $id_dono);
$venda = (new VendaDao())->BuscarPorAnimal($id_animal, $id_dono);

$ultimoPeso = !empty($pesagens) ? end($pesagens) : null;

$idadeFormatada = "Não informada";
if (!empty($animal['data_nascimento'])) {
    $nasc = new DateTime($animal['data_nascimento']);
    $hoje = new DateTime();
    $diferenca = $hoje->diff($nasc);
    $idadeFormatada = $diferenca->y > 0
        ? $diferenca->y . " ano(s) e " . $diferenca->m . " mês(es)"
        : $diferenca->m . " mês(es)";
}
?>

<head>
    <title>Ficha Individual - Brinco <?= htmlspecialchars($animal['brinco']) ?></title>
    <link rel="stylesheet" href="../CSS/FichaAnimal.css?v=2.0">
</head>

<main class="container-ficha">

    <div class="ficha-header">
        <div class="ficha-titulo">
            <h2>Ficha Individual do Animal</h2>
            <h1>Brinco #<?= htmlspecialchars($animal['brinco']) ?> <small>(<?= htmlspecialchars($animal['raca']) ?>)</small></h1>
            <span class="badge-status <?= ((int) $animal['vendido'] === 1) ? 'status-vendido' : 'status-ativo' ?>">
                <?= ((int) $animal['vendido'] === 1) ? 'Vendido' : 'No Rebanho' ?>
            </span>
            <?php if ($ehVeterinario): ?>
                <p class="text-muted mb-0"><i class="fas fa-user me-1"></i> Produtor: <?= htmlspecialchars($animal['dono_nome']) ?></p>
            <?php endif; ?>
        </div>

        <div class="ficha-acoes">
            <a href="ExportarFichaAnimal.php?id=<?= $id_animal ?>" class="btn-acao btn-imprimir"><i class="fas fa-file-pdf me-1"></i> Exportar PDF</a>
            <?php if (!$ehVeterinario && (int) $animal['vendido'] === 0): ?>
                <a href="ControlePeso.php" class="btn-acao">+ Novo Peso</a>
                <a href="RegistrarVacinacao.php" class="btn-acao">+ Vacina</a>
                <a href="EditarAnimal.php?id=<?= $id_animal ?>" class="btn-acao btn-secundario">Editar Dados</a>
            <?php endif; ?>
            <a href="<?= $ehVeterinario ? 'HistoricoAtendimento.php' : 'ListaAnimal.php' ?>" class="btn-voltar">← Voltar</a>
        </div>
    </div>

    <div class="grid-informacoes">
        <div class="card-info">
            <h3>Dados de Identificação</h3>
            <ul>
                <li><strong>Brinco:</strong> <?= htmlspecialchars($animal['brinco']) ?></li>
                <li><strong>Espécie:</strong> <?= htmlspecialchars($animal['especie']) ?></li>
                <li><strong>Raça:</strong> <?= htmlspecialchars($animal['raca']) ?></li>
                <li><strong>Nascimento:</strong> <?= date('d/m/Y', strtotime($animal['data_nascimento'])) ?></li>
                <li><strong>Idade Aproximada:</strong> <?= $idadeFormatada ?></li>
                <li><strong>Altura (cadastro):</strong> <?= number_format($animal['altura'], 2, ',', '.') ?> m</li>
            </ul>
        </div>

        <div class="card-info">
            <h3>Resumo Zootécnico</h3>
            <div class="resumo-metricas">
                <div class="metrica">
                    <span class="label">Último Peso</span>
                    <span class="valor">
                        <?= $ultimoPeso ? number_format($ultimoPeso->peso_atual, 2, ',', '.') . ' kg' : 'Sem registro' ?>
                    </span>
                </div>
                <div class="metrica">
                    <span class="label">Total de Vacinas</span>
                    <span class="valor"><?= count($vacinas) ?></span>
                </div>
                <div class="metrica">
                    <span class="label">Atendimentos Vet.</span>
                    <span class="valor"><?= count($atendimentos) ?></span>
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
                        <tr><th>Data</th><th>Peso Anterior</th><th>Peso Atual</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($pesagens) as $p): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($p->data_pessagem)) ?></td>
                                <td><?= number_format($p->peso_anterior, 2, ',', '.') ?> kg</td>
                                <td><strong><?= number_format($p->peso_atual, 2, ',', '.') ?> kg</strong></td>
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
                        <tr><th>Data</th><th>Vacina</th><th>Dose</th><th>Aplicador</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($vacinas) as $v): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($v->data_aplicacao)) ?></td>
                                <td><?= htmlspecialchars($v->nome_vacina) ?></td>
                                <td><?= htmlspecialchars($v->dose ?? '-') ?></td>
                                <td><?= htmlspecialchars($v->aplicador ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="sem-registro">Nenhuma vacina registrada para este animal.</p>
            <?php endif; ?>
        </div>

        <div class="bloco-historico">
            <h3>Atendimentos Veterinários</h3>
            <?php if (!empty($atendimentos)): ?>
                <table class="tabela-dados">
                    <thead>
                        <tr><th>Data</th><th>Veterinário</th><th>Descrição</th><th>Diagnóstico</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($atendimentos as $a): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($a->data_atendimento)) ?></td>
                                <td><?= htmlspecialchars($a->veterinario_nome ?? '-') ?></td>
                                <td><?= htmlspecialchars($a->descricao) ?></td>
                                <td><?= htmlspecialchars($a->diagnostico ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="sem-registro">Nenhum atendimento registrado.</p>
            <?php endif; ?>
        </div>

        <?php if ($venda): ?>
            <div class="bloco-historico destaque-venda">
                <h3>Dados de Comercialização / Venda</h3>
                <p><strong>Data da Venda:</strong> <?= date('d/m/Y', strtotime($venda['data_venda'])) ?></p>
                <p><strong>Comprador:</strong> <?= htmlspecialchars($venda['comprador']) ?></p>
                <p><strong>Valor:</strong> R$ <?= number_format($venda['valor_venda'], 2, ',', '.') ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php include 'Rodape.php'; ?>
</body>
</html>