<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuarioLogado = isset($_SESSION['id_usuario']);
$rodapeCssCarregado = true;
if ($usuarioLogado) {
    $cabecalhoHeadExtras = '<link rel="stylesheet" href="../CSS/SobreNos.css?v=2.1"><link rel="stylesheet" href="../CSS/Institucional.css?v=1.0"><link rel="stylesheet" href="../CSS/Rodape.css?v=1.1">';
    include 'Cabecalho.php';
} else {
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - Pecuária em Rede</title>
    <script>
        (function () {
            const tema = localStorage.getItem('rpp-tema') === 'dark' ? 'dark' : 'light';
            document.documentElement.dataset.theme = tema;
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/Variaveis.css?v=2.2">
    <link rel="stylesheet" href="../CSS/index.css?v=3.0">
    <link rel="stylesheet" href="../CSS/SobreNos.css?v=2.1">
    <link rel="stylesheet" href="../CSS/Institucional.css?v=1.0">
    <link rel="stylesheet" href="../CSS/Rodape.css?v=1.1">
</head>
<body class="pagina-institucional">
  <?php include 'CabecalhoPublico.php'; ?>
<?php } ?>
  <main>
      <section class="card sobre-card">
          <h2>O projeto</h2>
          <p> O projeto "Pecuária em Rede" é uma iniciativa desenvolvida por estudantes do IFRN, Pedro Rafael e Emilly Rodrigues, do campus de Nova Cruz, sob a orientação do professor Fabio Penha. O projeto teve origem no evento SITADS, que visa encontrar soluções para problemas que afetam os pecuaristas da região do Agreste potiguar</p>

          <h2>sua função</h2>
          <p> A região Agreste potiguar, caracterizada por seu clima semiárido, enfrenta problemas crônicos como a escassez de recursos hídricos, a degradação do solo e a falta de conhecimento técnico entre os pecuaristas, que muitas vezes dependem apenas de saberes tradicionais, dificultando a transformação de seu capital em lucro futuro e sofrendo com má organização financeira no campo. Tais dificuldades impactam diretamente a produtividade, a segurança alimentar e a qualidade de vida dos produtores. Diante desse cenário, o "Pecuária em Rede" propõe a integração de tecnologias digitais acessíveis e práticas sustentáveis como uma alternativa promissora para transformar a realidade local. </p>

          <h2>objetivo</h2>
          <p> O objetivo principal do "Pecuária em Rede" é desenvolver um sistema de monitoramento individual de animais. Este sistema busca integrar práticas sustentáveis e tecnologias acessíveis para melhorar a eficiência na gestão de recursos hídricos, pastagens e saúde animal, promovendo a sustentabilidade ambiental, o bem-estar dos rebanhos e o fortalecimento da produção local. Além disso, o projeto visa capacitar pequenos produtores rurais e estudantes do IFRN no uso de ferramentas digitais aplicadas à agropecuária regional, incentivando a inovação e a autonomia no campo.</p>

          <p>A metodologia do projeto é multifacetada, envolvendo o uso de ferramentas digitais, apoio veterinário programado, ações preventivas contra doenças e estratégias de manejo para períodos de seca. O sistema será aplicado em duas propriedades de pecuaristas locais com perfis distintos, permitindo a validação e o ajuste das soluções conforme as realidades específicas da região. </p>
      </section>
  </main>

  <?php include 'Rodape.php'; ?>
  <?php if (!$usuarioLogado): ?>
    <script src="../Javascript/ThemeToggle.js" defer></script>
    <script>document.addEventListener('DOMContentLoaded', function () { inicializarThemeToggle('themeToggle'); });</script>
  <?php endif; ?>
</body>
</html>