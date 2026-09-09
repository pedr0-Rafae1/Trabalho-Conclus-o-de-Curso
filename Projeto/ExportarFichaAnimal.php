<?php
include_once 'Sessao.php';

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Dao/AnimalDao.php';
require_once __DIR__ . '/../app/Dao/RegistroPesoDao.php';
require_once __DIR__ . '/../app/Dao/RegistroVacinacaoDao.php';
require_once __DIR__ . '/../app/Dao/AtendimentoDao.php';
require_once __DIR__ . '/../app/Dao/VendaDao.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_usuario = $_SESSION['id_usuario'];
$ehVeterinario = ($_SESSION['tipo_usuario'] ?? 'Pecuarista') === 'Veterinario';
$id_animal = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_animal) {
    http_response_code(400);
    exit('Animal inválido.');
}

$animalDao = new AnimalDao();
$animal = $ehVeterinario
    ? $animalDao->buscarPorIdQualquerDono($id_animal)
    : $animalDao->buscarPorIdEUsuario($id_animal, $id_usuario);

if (!$animal) {
    http_response_code(404);
    exit('Animal não encontrado.');
}

$id_dono = $ehVeterinario ? $animal['id_usuario'] : $id_usuario;
$pesagens = (new RegistroPesoDao())->ListarPorAnimal($id_animal, $id_dono);
$vacinas = (new RegistroVacinacaoDao())->ListarPorAnimal($id_animal, $id_dono);
$atendimentos = (new AtendimentoDao())->ListarPorAnimal($id_animal, $id_dono);
$venda = (new VendaDao())->BuscarPorAnimal($id_animal, $id_dono);

$esc = static fn ($valor): string => htmlspecialchars((string) ($valor ?? '-'), ENT_QUOTES, 'UTF-8');
$formatarData = static fn ($valor): string => $valor ? date('d/m/Y', strtotime($valor)) : '-';
$ultimoPeso = !empty($pesagens) ? end($pesagens) : null;
$idadeFormatada = 'Não informada';

if (!empty($animal['data_nascimento'])) {
    $nascimento = new DateTime($animal['data_nascimento']);
    $idade = (new DateTime())->diff($nascimento);
    $idadeFormatada = $idade->y > 0
        ? $idade->y . ' ano(s) e ' . $idade->m . ' mês(es)'
        : $idade->m . ' mês(es)';
}

$linhasPesagens = '';
foreach (array_reverse($pesagens) as $peso) {
    $linhasPesagens .= '<tr><td>' . $formatarData($peso->data_pessagem) . '</td><td>'
        . number_format((float) $peso->peso_anterior, 2, ',', '.') . ' kg</td><td><strong>'
        . number_format((float) $peso->peso_atual, 2, ',', '.') . ' kg</strong></td></tr>';
}

$linhasVacinas = '';
foreach (array_reverse($vacinas) as $vacina) {
    $linhasVacinas .= '<tr><td>' . $formatarData($vacina->data_aplicacao) . '</td><td>'
        . $esc($vacina->nome_vacina) . '</td><td>' . $esc($vacina->dose) . '</td><td>'
        . $esc($vacina->aplicador) . '</td></tr>';
}

$linhasAtendimentos = '';
foreach ($atendimentos as $atendimento) {
    $linhasAtendimentos .= '<tr><td>' . $formatarData($atendimento->data_atendimento) . '</td><td>'
        . $esc($atendimento->veterinario_nome) . '</td><td>' . $esc($atendimento->descricao) . '</td><td>'
        . $esc($atendimento->diagnostico) . '</td></tr>';
}

$blocoVenda = $venda
    ? '<section><h2>Dados de comercialização</h2><p><strong>Data:</strong> ' . $formatarData($venda['data_venda'])
        . '</p><p><strong>Comprador:</strong> ' . $esc($venda['comprador']) . '</p><p><strong>Valor:</strong> R$ '
        . number_format((float) $venda['valor_venda'], 2, ',', '.') . '</p></section>'
    : '';

$html = '<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><style>
    @page { margin: 28px 32px; }
    body { color: #24352d; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.45; }
    h1 { margin: 0 0 4px; color: #164c34; font-size: 22px; }
    h2 { margin: 20px 0 8px; padding-bottom: 5px; color: #164c34; border-bottom: 1px solid #b9d8c0; font-size: 14px; }
    p { margin: 5px 0; }
    .cabecalho { padding-bottom: 12px; border-bottom: 2px solid #247a48; }
    .subtitulo { color: #5d6d65; font-size: 12px; }
    .status { float: right; padding: 5px 9px; color: #fff; background: #247a48; border-radius: 4px; font-size: 10px; }
    .status.vendido { background: #a94a4a; }
    .dados { width: 100%; border-collapse: collapse; }
    .dados td { width: 33.33%; padding: 5px 8px 5px 0; vertical-align: top; }
    .label { display: block; color: #63736b; font-size: 9px; text-transform: uppercase; }
    table { width: 100%; margin-top: 5px; border-collapse: collapse; }
    th { color: #164c34; background: #eaf4ec; font-size: 9px; text-align: left; text-transform: uppercase; }
    th, td { padding: 6px 7px; border-bottom: 1px solid #d9e5dc; }
    .vazio { color: #718078; font-style: italic; }
    .rodape { margin-top: 28px; color: #718078; font-size: 9px; }
</style></head><body>
    <div class="cabecalho"><span class="status ' . ((int) $animal['vendido'] === 1 ? 'vendido' : '') . '">'
        . ((int) $animal['vendido'] === 1 ? 'Vendido' : 'No rebanho') . '</span><h1>Ficha Individual do Animal</h1>
        <div class="subtitulo">Brinco #' . $esc($animal['brinco']) . ' | ' . $esc($animal['raca']) . '</div>'
        . ($ehVeterinario ? '<div class="subtitulo">Produtor: ' . $esc($animal['dono_nome']) . '</div>' : '') . '</div>
    <section><h2>Dados de identificação</h2><table class="dados"><tr><td><span class="label">Brinco</span>' . $esc($animal['brinco'])
        . '</td><td><span class="label">Espécie</span>' . $esc($animal['especie']) . '</td><td><span class="label">Raça</span>' . $esc($animal['raca'])
        . '</td></tr><tr><td><span class="label">Nascimento</span>' . $formatarData($animal['data_nascimento']) . '</td><td><span class="label">Idade aproximada</span>'
        . $esc($idadeFormatada) . '</td><td><span class="label">Altura</span>' . number_format((float) $animal['altura'], 2, ',', '.') . ' m</td></tr></table></section>
    <section><h2>Resumo zootécnico</h2><table class="dados"><tr><td><span class="label">Último peso</span>'
        . ($ultimoPeso ? number_format((float) $ultimoPeso->peso_atual, 2, ',', '.') . ' kg' : 'Sem registro') . '</td><td><span class="label">Total de vacinas</span>'
        . count($vacinas) . '</td><td><span class="label">Atendimentos veterinários</span>' . count($atendimentos) . '</td></tr></table></section>
    <section><h2>Histórico de pesagens</h2>' . ($linhasPesagens ? '<table><thead><tr><th>Data</th><th>Peso anterior</th><th>Peso atual</th></tr></thead><tbody>' . $linhasPesagens . '</tbody></table>' : '<p class="vazio">Nenhum registro de peso encontrado.</p>') . '</section>
    <section><h2>Histórico de vacinações</h2>' . ($linhasVacinas ? '<table><thead><tr><th>Data</th><th>Vacina</th><th>Dose</th><th>Aplicador</th></tr></thead><tbody>' . $linhasVacinas . '</tbody></table>' : '<p class="vazio">Nenhuma vacina registrada.</p>') . '</section>
    <section><h2>Atendimentos veterinários</h2>' . ($linhasAtendimentos ? '<table><thead><tr><th>Data</th><th>Veterinário</th><th>Descrição</th><th>Diagnóstico</th></tr></thead><tbody>' . $linhasAtendimentos . '</tbody></table>' : '<p class="vazio">Nenhum atendimento registrado.</p>') . '</section>'
    . $blocoVenda . '<div class="rodape">Documento gerado em ' . date('d/m/Y H:i') . ' - Pecuária em Rede</div></body></html>';

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('ficha-animal-' . $animal['brinco'] . '.pdf', ['Attachment' => true]);
exit;
