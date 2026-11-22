<?php
/**
 * EmailService — envio de e-mails exclusivamente via API REST da Brevo.
 * VERSÃO COM DEBUG EM ARQUIVO (temporário) — grava cada tentativa em
 * debug_brevo.txt na raiz do projeto, pra diagnosticar por que o e-mail
 * não está chegando mesmo com a conta Brevo validada.
 */

require_once __DIR__ . '/../Config/brevo.php';

class EmailService {

    private static function logDebug(string $destinatario, array $payload, $resposta, int $httpCode, string $erroCurl, array $infoCurl): void {
        $linhas   = [];
        $linhas[] = str_repeat('=', 60);
        $linhas[] = 'Data/hora: ' . date('Y-m-d H:i:s');
        $linhas[] = 'Destinatário: ' . $destinatario;
        $linhas[] = 'Sender usado: ' . json_encode($payload['sender'], JSON_UNESCAPED_UNICODE);
        $linhas[] = 'HTTP Code: ' . $httpCode;
        $linhas[] = 'Erro cURL (transporte): ' . ($erroCurl ?: '(nenhum)');
        $linhas[] = 'Resposta da Brevo (corpo): ' . ($resposta === false ? '(false - sem resposta)' : ($resposta ?: '(vazia)'));
        $linhas[] = 'SSL verify result: ' . ($infoCurl['ssl_verify_result'] ?? '(n/d)');
        $linhas[] = 'Total time: ' . ($infoCurl['total_time'] ?? '(n/d)') . 's';
        $linhas[] = 'Primary IP conectado: ' . ($infoCurl['primary_ip'] ?? '(n/d)');
        $linhas[] = str_repeat('=', 60) . "\n";

        @file_put_contents(
            __DIR__ . '/../../debug_brevo.txt',
            implode("\n", $linhas) . "\n",
            FILE_APPEND | LOCK_EX
        );
    }

    public static function enviarEmail(string $destinatarioEmail, string $destinatarioNome, string $assunto, string $corpoHtml): bool {
        $payload = [
            'sender' => [
                'name'  => BREVO_REMETENTE_NOME,
                'email' => BREVO_REMETENTE_EMAIL,
            ],
            'to' => [[
                'email' => $destinatarioEmail,
                'name'  => $destinatarioNome,
            ]],
            'subject'     => $assunto,
            'htmlContent' => $corpoHtml,
        ];

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'accept: application/json',
                'api-key: ' . BREVO_API_KEY,
                'content-type: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_CONNECTTIMEOUT => 10,
            // Diagnóstico: manter verificação de SSL ligada por padrão (mais seguro),
            // mas registrar o resultado da verificação no log pra sabermos se é isso.
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $resposta = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $erroCurl = curl_error($ch);
        $infoCurl = curl_getinfo($ch);
        curl_close($ch);

        self::logDebug($destinatarioEmail, $payload, $resposta, $httpCode, $erroCurl, $infoCurl);

        if ($resposta === false || $erroCurl !== '') {
            error_log("[EmailService] Erro cURL para {$destinatarioEmail}: {$erroCurl}");
            return false;
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            error_log("[EmailService] Brevo HTTP {$httpCode} para {$destinatarioEmail}: {$resposta}");
            return false;
        }

        return true;
    }

    public static function gerarCodigo6Digitos(): string {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function enviarCodigoCadastro(string $email, string $nome, string $codigo): bool {
        $assunto = 'Confirme seu cadastro - Pecuária em Rede';
        $corpo = "
            <div style='font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto;'>
                <h2 style='color: #164c34;'>Olá, " . htmlspecialchars($nome) . "!</h2>
                <p>Seu código de verificação é:</p>
                <p style='font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #247a48;'>{$codigo}</p>
                <p>Ele é válido por 15 minutos. Digite-o na tela de confirmação para ativar sua conta.</p>
                <p style='color: #888; font-size: 12px;'>Se você não se cadastrou, ignore este e-mail.</p>
            </div>";

        return self::enviarEmail($email, $nome, $assunto, $corpo);
    }

    public static function enviarCodigoRecuperacao(string $email, string $nome, string $codigo): bool {
        $assunto = 'Recuperação de senha - Pecuária em Rede';
        $corpo = "
            <div style='font-family: Arial, sans-serif; max-width: 480px; margin: 0 auto;'>
                <h2 style='color: #164c34;'>Olá, " . htmlspecialchars($nome) . "!</h2>
                <p>Recebemos um pedido de redefinição de senha. Seu código é:</p>
                <p style='font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #247a48;'>{$codigo}</p>
                <p>Ele é válido por 30 minutos. Digite-o na tela de redefinição junto com sua nova senha.</p>
                <p style='color: #888; font-size: 12px;'>Se você não solicitou, ignore este e-mail.</p>
            </div>";

        return self::enviarEmail($email, $nome, $assunto, $corpo);
    }
}

/*
INSTRUÇÕES:
1. Substitua app/Servico/EmailService.php por este arquivo (mantém 100% de
   compatibilidade — mesmos métodos, só com log adicional).
2. Cadastre uma conta nova (e-mail que você realmente acessa).
3. Abra: https://pecuariaemrede.42web.io/debug_brevo.txt
4. Copie e cole aqui TODO o conteúdo — principalmente "Erro cURL",
   "SSL verify result" e "Resposta da Brevo".
5. Depois de resolver: delete debug_brevo.txt do servidor e volte pro
   EmailService.php original (sem o logDebug).
*/