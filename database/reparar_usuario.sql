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

SELECT TABLE_SCHEMA, TABLE_NAME
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'pecuaria_em_rede'
  AND TABLE_NAME = 'usuario';
