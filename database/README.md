# Banco remodelado

O arquivo `schema.sql` cria o banco `pecuaria_em_rede` com as tabelas e os campos novos do sistema.

## Importacao no XAMPP

1. Abra o painel do XAMPP e inicie o MySQL.
2. Acesse `http://localhost/phpmyadmin`.
3. Abra a aba **Importar**.
4. Selecione `database/schema.sql`.
5. Execute a importacao.
6. Na aba **SQL** do phpMyAdmin, confirme que `pecuaria_em_rede` aparece na lista de bancos.
7. Dentro dele, confirme que a tabela `usuario` foi criada.
8. Depois de conferir que o banco foi criado, mantenha `app/Conexao/ConexaoBD.php` assim:

```php
private static $db = "pecuaria_em_rede";
```

Se aparecer `Table 'pecuaria_em_rede.usuario' doesn't exist`, importe `database/reparar_usuario.sql` na aba **SQL**, selecione o banco `pecuaria_em_rede` e execute novamente `database/schema.sql`.

A conexao atual usa `pedro`, que era o banco original do projeto, para manter o site funcionando. O banco `pecuaria_em_rede` fica preparado separadamente para uma migracao futura.

## Perfis

- `Pecuarista`: animais, pesos, vacinacoes, vendas e comunicacao com veterinarios.
- `Veterinario`: atendimentos, respostas, historico clinico e comunicacao com produtores.

O schema usa chaves estrangeiras e `utf8mb4`, com campos de auditoria (`criado_em` e `atualizado_em`) nas entidades principais.
