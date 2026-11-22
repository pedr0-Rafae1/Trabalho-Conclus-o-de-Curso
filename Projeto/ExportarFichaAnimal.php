<?php
// Debug: exibe erros na tela em vez de página em branco (remova em produção se desejar)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia o buffer ANTES de qualquer include, para capturar saída prematura
// (BOM de UTF-8, espaços antes de <?php, warnings) que corromperia o PDF
ob_start();

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
    @page { margin: 36px 40px; }
    * { box-sizing: border-box; }
    body { color: #24352d; font-family: DejaVu Sans, sans-serif; font-size: 10.5px; line-height: 1.5; }

    /* Cabeçalho do documento */
    .doc-header { width: 100%; border-bottom: 3px solid #164c34; padding-bottom: 12px; margin-bottom: 18px; }
    .doc-header table { width: 100%; border-collapse: collapse; }
    .doc-header td { border: 0; padding: 0; vertical-align: middle; }
    .doc-marca { color: #164c34; font-size: 17px; font-weight: bold; letter-spacing: 0.5px; }
    .doc-marca small { display: block; color: #5d6d65; font-size: 9px; font-weight: normal; letter-spacing: 1.5px; text-transform: uppercase; }
    .doc-data { color: #5d6d65; font-size: 9.5px; text-align: right; }

    /* Título e status */
    .doc-titulo { margin: 14px 0 4px; color: #164c34; font-size: 20px; }
    .doc-subtitulo { color: #5d6d65; font-size: 11.5px; margin-bottom: 4px; }
    .status { display: inline-block; padding: 4px 12px; color: #fff; background: #2d9b62; border-radius: 10px; font-size: 9px; font-weight: bold; letter-spacing: 0.8px; text-transform: uppercase; }
    .status.vendido { background: #c57a28; }

    /* Seções */
    h2 { margin: 20px 0 8px; padding: 6px 10px; color: #164c34; background: #eaf4ec; border-left: 4px solid #247a48; font-size: 12px; text-transform: uppercase; letter-spacing: 0.6px; }

    /* Grade de dados de identificação */
    .dados { width: 100%; border-collapse: collapse; }
    .dados td { width: 33.33%; padding: 7px 10px; border: 1px solid #dce6df; vertical-align: top; }
    .label { display: block; margin-bottom: 2px; color: #63736b; font-size: 8px; letter-spacing: 0.8px; text-transform: uppercase; }
    .valor { font-size: 11px; font-weight: bold; color: #24352d; }

    /* Tabelas de histórico */
    table.hist { width: 100%; margin-top: 4px; border-collapse: collapse; }
    table.hist th { padding: 7px 9px; color: #164c34; background: #eaf4ec; border: 1px solid #c8ddcd; font-size: 8.5px; letter-spacing: 0.6px; text-align: left; text-transform: uppercase; }
    table.hist td { padding: 7px 9px; border: 1px solid #dce6df; font-size: 10px; }
    table.hist tr:nth-child(even) td { background: #f4f8f4; }

    .vazio { color: #718078; font-style: italic; }

    /* Rodapé do documento */
    .rodape { margin-top: 30px; padding-top: 10px; border-top: 1px solid #dce6df; color: #718078; font-size: 8.5px; text-align: center; }
</style></head><body>

    <div class="doc-header">
        <table><tr>
            <td>
                <div class="doc-marca">PECUÁRIA EM REDE
                    <small>Gestão inteligente para o seu rebanho</small>
                </div>
            </td>
            <td class="doc-data">
                Relatório gerado em<br><strong>' . date('d/m/Y \à\s H:i') . '</strong>
            </td>
        </tr></table>
    </div>

    <h1 class="doc-titulo">Ficha Individual do Animal</h1>
    <div class="doc-subtitulo">Brinco <strong>#' . $esc($animal['brinco']) . '</strong> &nbsp;|&nbsp; ' . $esc($animal['raca'])
        . ' &nbsp; <span class="status ' . ((int) $animal['vendido'] === 1 ? 'vendido' : '') . '">'
        . ((int) $animal['vendido'] === 1 ? 'Vendido' : 'No rebanho') . '</span></div>'
        . ($ehVeterinario ? '<div class="doc-subtitulo">Produtor: <strong>' . $esc($animal['dono_nome']) . '</strong></div>' : '') . '

    <h2>Dados de Identificação</h2>
    <table class="dados">
        <tr>
            <td><span class="label">Brinco</span><span class="valor">' . $esc($animal['brinco']) . '</span></td>
            <td><span class="label">Espécie</span><span class="valor">' . $esc($animal['especie']) . '</span></td>
            <td><span class="label">Raça</span><span class="valor">' . $esc($animal['raca']) . '</span></td>
        </tr>
        <tr>
            <td><span class="label">Nascimento</span><span class="valor">' . $formatarData($animal['data_nascimento']) . '</span></td>
            <td><span class="label">Idade Aproximada</span><span class="valor">' . $esc($idadeFormatada) . '</span></td>
            <td><span class="label">Altura</span><span class="valor">' . number_format((float) $animal['altura'], 2, ',', '.') . ' m</span></td>
        </tr>
    </table>

    <h2>Resumo Zootécnico</h2>
    <table class="dados">
        <tr>
            <td><span class="label">Último Peso</span><span class="valor">' . ($ultimoPeso ? number_format((float) $ultimoPeso->peso_atual, 2, ',', '.') . ' kg' : 'Sem registro') . '</span></td>
            <td><span class="label">Total de Vacinas</span><span class="valor">' . count($vacinas) . '</span></td>
            <td><span class="label">Atendimentos Veterinários</span><span class="valor">' . count($atendimentos) . '</span></td>
        </tr>
    </table>

    <h2>Histórico de Pesagens</h2>'
    . ($linhasPesagens
        ? '<table class="hist"><thead><tr><th>Data</th><th>Peso Anterior</th><th>Peso Atual</th></tr></thead><tbody>' . $linhasPesagens . '</tbody></table>'
        : '<p class="vazio">Nenhum registro de peso encontrado.</p>') . '

    <h2>Histórico de Vacinações</h2>'
    . ($linhasVacinas
        ? '<table class="hist"><thead><tr><th>Data</th><th>Vacina</th><th>Dose</th><th>Aplicador</th></tr></thead><tbody>' . $linhasVacinas . '</tbody></table>'
        : '<p class="vazio">Nenhuma vacina registrada.</p>') . '

    <h2>Atendimentos Veterinários</h2>'
    . ($linhasAtendimentos
        ? '<table class="hist"><thead><tr><th>Data</th><th>Veterinário</th><th>Descrição</th><th>Diagnóstico</th></tr></thead><tbody>' . $linhasAtendimentos . '</tbody></table>'
        : '<p class="vazio">Nenhum atendimento registrado.</p>')

    . $blocoVenda . '

    <div class="rodape">Documento gerado automaticamente pelo sistema Pecuária em Rede — Projeto acadêmico IFRN Campus Nova Cruz</div>
</body></html>';

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Limpa TODO o buffer de saída acumulado (espaços, BOM, warnings dos includes)
// para que o PDF não chegue corrompido ao navegador
if (ob_get_length()) {
    ob_clean();
}

$dompdf->stream('ficha-animal-' . $animal['brinco'] . '.pdf', ['Attachment' => true]);

exit;
?>