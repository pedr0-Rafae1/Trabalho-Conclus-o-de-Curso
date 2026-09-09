CREATE DATABASE IF NOT EXISTS pecuaria_em_rede
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE pecuaria_em_rede;

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    idade TINYINT UNSIGNED NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('Pecuarista', 'Veterinario') NOT NULL DEFAULT 'Pecuarista',
    telefone VARCHAR(25) NULL,
    cidade VARCHAR(100) NULL,
    estado CHAR(2) NULL,
    foto_perfil VARCHAR(255) NULL,
    bio VARCHAR(500) NULL,
    registro_profissional VARCHAR(60) NULL,
    especialidade VARCHAR(120) NULL,
    homologado TINYINT(1) NOT NULL DEFAULT 0,
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuario_tipo (tipo_usuario),
    INDEX idx_usuario_ativo (ativo)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS animal (
    id_animal INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    brinco VARCHAR(40) NOT NULL,
    nome VARCHAR(100) NULL,
    idade TINYINT UNSIGNED NULL,
    especie VARCHAR(60) NOT NULL DEFAULT 'Bovino',
    raca VARCHAR(100) NULL,
    sexo ENUM('Macho', 'Femea') NULL,
    pelagem VARCHAR(80) NULL,
    lote VARCHAR(50) NULL,
    finalidade ENUM('Corte', 'Leite', 'Reproducao', 'Outro') NULL,
    data_nascimento DATE NULL,
    peso DECIMAL(8,2) NULL,
    altura DECIMAL(6,2) NULL,
    status ENUM('Ativo', 'Vendido', 'Abatido', 'Inativo') NOT NULL DEFAULT 'Ativo',
    vendido TINYINT(1) NOT NULL DEFAULT 0,
    observacoes TEXT NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_animal_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY uq_animal_usuario_brinco (id_usuario, brinco),
    INDEX idx_animal_usuario (id_usuario),
    INDEX idx_animal_status (status)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registropeso (
    id_peso INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_animal INT UNSIGNED NOT NULL,
    peso_anterior DECIMAL(8,2) NULL,
    peso_atual DECIMAL(8,2) NOT NULL,
    data_pessagem DATE NOT NULL,
    observacoes VARCHAR(500) NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_peso_animal FOREIGN KEY (id_animal) REFERENCES animal(id_animal) ON DELETE CASCADE,
    INDEX idx_peso_animal_data (id_animal, data_pessagem)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrovacinacao (
    id_vacinacao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_animal INT UNSIGNED NOT NULL,
    nome_vacina VARCHAR(120) NOT NULL,
    data_aplicacao DATE NOT NULL,
    aplicador VARCHAR(120) NULL,
    dose VARCHAR(50) NULL,
    lote_vacina VARCHAR(60) NULL,
    validade DATE NULL,
    proxima_dose DATE NULL,
    observacoes VARCHAR(500) NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_vacinacao_animal FOREIGN KEY (id_animal) REFERENCES animal(id_animal) ON DELETE CASCADE,
    INDEX idx_vacinacao_animal_data (id_animal, data_aplicacao),
    INDEX idx_vacinacao_proxima (proxima_dose)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS venda (
    id_venda INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_animal INT UNSIGNED NOT NULL,
    comprador VARCHAR(160) NOT NULL,
    valor_venda DECIMAL(12,2) NOT NULL DEFAULT 0,
    data_venda DATE NOT NULL,
    forma_pagamento VARCHAR(50) NULL,
    observacoes VARCHAR(500) NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_venda_animal FOREIGN KEY (id_animal) REFERENCES animal(id_animal) ON DELETE CASCADE,
    INDEX idx_venda_data (data_venda)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS atendimento (
    id_atendimento INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_animal INT UNSIGNED NOT NULL,
    id_veterinario INT UNSIGNED NOT NULL,
    data_atendimento DATE NOT NULL,
    tipo_atendimento ENUM('Visita', 'Consulta', 'Emergencia', 'Retorno') NOT NULL DEFAULT 'Visita',
    descricao TEXT NOT NULL,
    diagnostico TEXT NULL,
    recomendacao TEXT NULL,
    sinais_vitais VARCHAR(500) NULL,
    proximo_retorno DATE NULL,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_atendimento_animal FOREIGN KEY (id_animal) REFERENCES animal(id_animal) ON DELETE CASCADE,
    CONSTRAINT fk_atendimento_veterinario FOREIGN KEY (id_veterinario) REFERENCES usuario(id_usuario) ON DELETE RESTRICT,
    INDEX idx_atendimento_veterinario_data (id_veterinario, data_atendimento),
    INDEX idx_atendimento_animal_data (id_animal, data_atendimento)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS duvida (
    id_duvida INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    id_veterinario INT UNSIGNED NULL,
    categoria ENUM('Manejo', 'Saude', 'Nutricao', 'Reproducao', 'Outro') NOT NULL DEFAULT 'Outro',
    prioridade ENUM('Normal', 'Alta', 'Urgente') NOT NULL DEFAULT 'Normal',
    pergunta TEXT NOT NULL,
    resposta TEXT NULL,
    status ENUM('pendente', 'em_analise', 'respondida', 'encerrada') NOT NULL DEFAULT 'pendente',
    data_pergunta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_resposta DATETIME NULL,
    atualizado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_duvida_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    CONSTRAINT fk_duvida_veterinario FOREIGN KEY (id_veterinario) REFERENCES usuario(id_usuario) ON DELETE SET NULL,
    INDEX idx_duvida_status_prioridade (status, prioridade),
    INDEX idx_duvida_veterinario (id_veterinario)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notificacao (
    id_notificacao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    titulo VARCHAR(140) NOT NULL,
    mensagem VARCHAR(500) NOT NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notificacao_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
    INDEX idx_notificacao_usuario_lida (id_usuario, lida)
) ENGINE=InnoDB;
