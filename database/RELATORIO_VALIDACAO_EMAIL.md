# Relatório Técnico — Validação de E-mail do Usuário
**Sistema:** Pecuária em Rede
**Módulo:** Autenticação / Cadastro de Usuário
**Data:** Setembro/2026

---

## 1. Objetivo

Garantir que toda conta criada no sistema pertença a um endereço de e-mail real e acessível pelo usuário, impedindo cadastros com e-mails falsos, digitados errado ou de terceiros. A verificação é obrigatória: o usuário só consegue fazer login após confirmar o endereço.

## 2. Visão Geral do Fluxo

```
[Cadastro] → gera código de 6 dígitos → salva no banco → envia por e-mail
     ↓
[Tela de Verificação] → usuário digita o código
     ↓
[Confirmação] → e-mail marcado como verificado → login liberado
```

## 3. Componentes Implementados

### 3.1 Banco de Dados (tabela `usuario`)

| Coluna | Tipo | Função |
|---|---|---|
| `email_verificado` | TINYINT(1) DEFAULT 0 | Flag que indica se o e-mail foi confirmado |
| `codigo_verificacao` | VARCHAR(6) NULL | Código temporário de 6 dígitos enviado ao usuário |

### 3.2 Camada de Acesso a Dados (`app/Dao/UsuarioDao.php`)

Dois métodos reaproveitados, sem alteração de assinatura:

- **`SalvarCodigoVerificacao($id_usuario, $codigo)`** — grava o código de 6 dígitos vinculado ao usuário recém-cadastrado.
- **`ConfirmarEmail($email, $codigo)`** — compara o código digitado com o gravado; em caso de sucesso, define `email_verificado = 1` e limpa o código (`codigo_verificacao = NULL`), impedindo reutilização.

### 3.3 Serviço de Envio (`app/Servico/EmailService.php`)

Classe responsável pelo envio de e-mails transacionais via **API HTTP da Brevo** (POST para `https://api.brevo.com/v3/smtp/email` usando cURL). A chave de API fica isolada em `app/Config/brevo.php`, fora do versionamento (`.gitignore`).

Método utilizado neste fluxo:

- **`enviarCodigoVerificacao($email, $nome, $codigo)`** — monta um e-mail em HTML com o código em destaque e o envia ao usuário.

**Decisão técnica relevante:** a abordagem anterior usava SMTP direto (PHPMailer), que foi descontinuada porque a hospedagem (InfinityFree) bloqueia conexões SMTP de saída. A API HTTP da Brevo usa HTTPS comum (porta 443), funcionando em qualquer hospedagem.

### 3.4 Páginas do Fluxo

| Arquivo | Responsabilidade |
|---|---|
| `Projeto/Processar_Usuario.php` | Após cadastrar o usuário, gera código aleatório de 6 dígitos (`random_int`), salva via `SalvarCodigoVerificacao()`, dispara o e-mail e redireciona para a tela de verificação |
| `Projeto/VerificarEmail.php` | Formulário que recebe o código de 6 dígitos, chama `ConfirmarEmail()` e, em caso de sucesso, redireciona para o login |
| `Projeto/ProcessarLogin.php` | **Barreira de acesso:** se `email_verificado = 0`, o login é interrompido e o usuário é redirecionado à tela de verificação |

## 4. Regras de Negócio

1. **RN-V01** — Todo cadastro gera um código único de 6 dígitos, numérico, aleatório e criptograficamente seguro (`random_int`).
2. **RN-V02** — O código é de uso único: após a confirmação, é apagado do banco.
3. **RN-V03** — Usuário com `email_verificado = 0` não acessa o sistema, mesmo com senha correta.
4. **RN-V04** — O e-mail de verificação não revela informações sensíveis além do código.

## 5. Segurança

- Código gerado com `random_int()` (fonte criptograficamente segura), não `rand()`.
- Código removido do banco após o uso (previne replay).
- Chave da API de e-mail fora do código versionado.
- Consultas via prepared statements (MySQLi), prevenindo SQL Injection.
- Saídas HTML escapadas com `htmlspecialchars()` nas telas (prevenção de XSS).

## 6. Interface e Experiência

A tela de verificação segue o padrão visual das demais páginas de autenticação (layout 50/50 com painel de branding e card centralizado), com:

- Campo numérico com máscara de 6 dígitos (`pattern="\d{6}"`, `inputmode="numeric"`);
- Mensagens de erro/sucesso em banners padronizados com botão de fechar;
- Suporte a tema claro/escuro.

## 7. Testes Realizados

| Cenário | Resultado |
|---|---|
| Cadastro com e-mail válido | Código gerado, salvo e enviado; redirecionamento correto |
| Código correto | `email_verificado` atualizado para 1; login liberado |
| Código incorreto | Mensagem de erro exibida; conta permanece não verificada |
| Login antes de verificar | Redirecionado à tela de verificação |
| Envio de e-mail | Confirmado via API Brevo (HTTP 201) |

## 8. Melhorias Futuras (não implementadas)

- Expiração do código de verificação (ex: válido por 15 minutos);
- Botão "Reenviar código" com limite de reenvios (rate limiting);
- Notificação ao usuário por e-mail após a confirmação bem-sucedida.

---

**Conclusão:** o módulo de validação de e-mail foi implementado com sucesso, integrando cadastro, verificação e login em um fluxo coeso, com envio de e-mails funcional em ambiente de hospedagem restrita e interface consistente com o restante do sistema.
