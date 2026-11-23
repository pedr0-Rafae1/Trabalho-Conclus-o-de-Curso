CREATE TABLE IF NOT EXISTS duvida (
    id_duvida INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    id_animal INT UNSIGNED NULL,
    id_veterinario INT UNSIGNED NULL,
    assunto VARCHAR(150) NOT NULL DEFAULT 'Dúvida',
    categoria ENUM('Manejo', 'Saude', 'Nutricao', 'Reproducao', 'Outro') NOT NULL DEFAULT 'Outro',
    prioridade ENUM('Normal', 'Alta', 'Urgente') NOT NULL DEFAULT 'Normal',
    pergunta TEXT NOT NULL,
    resposta TEXT NULL,
    status ENUM('pendente', 'em_analise', 'respondida', 'encerrada') NOT NULL DEFAULT 'pendente',
    data_pergunta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_resposta DATETIME NULL,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_duvida_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_duvida_animal FOREIGN KEY (id_animal) REFERENCES animal(id_animal) ON DELETE SET NULL,
    CONSTRAINT fk_duvida_veterinario FOREIGN KEY (id_veterinario) REFERENCES usuario(id_usuario) ON DELETE SET NULL,
    INDEX idx_duvida_status_prioridade (status, prioridade),
    INDEX idx_duvida_animal (id_animal),
    INDEX idx_duvida_veterinario (id_veterinario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET @rpp_sql = IF(
    (SELECT COUNT(*) FROM information_schema.columns
     WHERE table_schema = DATABASE() AND table_name = 'duvida' AND column_name = 'id_animal') = 0,
    'ALTER TABLE duvida ADD COLUMN id_animal INT UNSIGNED NULL AFTER id_usuario',
    'SELECT 1'
);
PREPARE rpp_stmt FROM @rpp_sql;
EXECUTE rpp_stmt;
DEALLOCATE PREPARE rpp_stmt;

SET @rpp_sql = IF(
    (SELECT COUNT(*) FROM information_schema.columns
     WHERE table_schema = DATABASE() AND table_name = 'duvida' AND column_name = 'assunto') = 0,
    'ALTER TABLE duvida ADD COLUMN assunto VARCHAR(150) NOT NULL DEFAULT ''Dúvida'' AFTER id_veterinario',
    'SELECT 1'
);
PREPARE rpp_stmt FROM @rpp_sql;
EXECUTE rpp_stmt;
DEALLOCATE PREPARE rpp_stmt;

SET @rpp_sql = IF(
    (SELECT COUNT(*) FROM information_schema.statistics
     WHERE table_schema = DATABASE() AND table_name = 'duvida' AND column_name = 'id_animal') = 0,
    'ALTER TABLE duvida ADD INDEX idx_duvida_animal (id_animal)',
    'SELECT 1'
);
PREPARE rpp_stmt FROM @rpp_sql;
EXECUTE rpp_stmt;
DEALLOCATE PREPARE rpp_stmt;

SET @rpp_sql = IF(
    (SELECT COUNT(*) FROM information_schema.key_column_usage
     WHERE table_schema = DATABASE() AND table_name = 'duvida'
       AND column_name = 'id_animal' AND referenced_table_name = 'animal') = 0,
    'ALTER TABLE duvida ADD CONSTRAINT fk_duvida_animal FOREIGN KEY (id_animal) REFERENCES animal(id_animal) ON DELETE SET NULL',
    'SELECT 1'
);
PREPARE rpp_stmt FROM @rpp_sql;
EXECUTE rpp_stmt;
DEALLOCATE PREPARE rpp_stmt;

CREATE TABLE IF NOT EXISTS duvida_mensagem (
    id_mensagem INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_duvida INT UNSIGNED NOT NULL,
    id_usuario INT UNSIGNED NOT NULL,
    mensagem TEXT NOT NULL,
    enviado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_duvida_mensagem_duvida FOREIGN KEY (id_duvida) REFERENCES duvida(id_duvida) ON DELETE CASCADE,
    CONSTRAINT fk_duvida_mensagem_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    INDEX idx_duvida_mensagem_conversa (id_duvida, enviado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS notificacao (
    id_notificacao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    titulo VARCHAR(140) NOT NULL,
    mensagem VARCHAR(500) NOT NULL,
    id_referencia INT UNSIGNED NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notificacao_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    INDEX idx_notificacao_usuario_lida (id_usuario, lida),
    INDEX idx_notificacao_criado (criado_em),
    INDEX idx_notificacao_referencia (id_referencia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET @rpp_sql = IF(
    (SELECT COUNT(*) FROM information_schema.columns
     WHERE table_schema = DATABASE() AND table_name = 'notificacao' AND column_name = 'id_referencia') = 0,
    'ALTER TABLE notificacao ADD COLUMN id_referencia INT UNSIGNED NULL AFTER mensagem',
    'SELECT 1'
);
PREPARE rpp_stmt FROM @rpp_sql;
EXECUTE rpp_stmt;
DEALLOCATE PREPARE rpp_stmt;

SET @rpp_sql = IF(
    (SELECT COUNT(*) FROM information_schema.statistics
     WHERE table_schema = DATABASE() AND table_name = 'notificacao' AND column_name = 'id_referencia') = 0,
    'ALTER TABLE notificacao ADD INDEX idx_notificacao_referencia (id_referencia)',
    'SELECT 1'
);
PREPARE rpp_stmt FROM @rpp_sql;
EXECUTE rpp_stmt;
DEALLOCATE PREPARE rpp_stmt;
