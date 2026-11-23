<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class NotificacaoDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection();
        $this->garantirTabela();
    }

    private function garantirTabela() {
        $sql = "CREATE TABLE IF NOT EXISTS notificacao (
            id_notificacao INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            id_usuario INT UNSIGNED NOT NULL,
            tipo VARCHAR(50) NOT NULL,
            titulo VARCHAR(140) NOT NULL,
            mensagem VARCHAR(500) NOT NULL,
            id_referencia INT UNSIGNED NULL,
            lida TINYINT(1) NOT NULL DEFAULT 0,
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_notificacao_usuario_lida (id_usuario, lida),
            INDEX idx_notificacao_criado (criado_em),
            INDEX idx_notificacao_referencia (id_referencia)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        if (!$this->db->query($sql)) {
            throw new RuntimeException('Não foi possível preparar a tabela de notificações.');
        }

        $colunaReferencia = $this->db->query("SHOW COLUMNS FROM notificacao LIKE 'id_referencia'");
        if (!$colunaReferencia) {
            throw new RuntimeException('Não foi possível verificar a estrutura das notificações.');
        }
        if ($colunaReferencia->num_rows === 0
            && !$this->db->query('ALTER TABLE notificacao ADD COLUMN id_referencia INT UNSIGNED NULL AFTER mensagem')) {
            throw new RuntimeException('Não foi possível atualizar a tabela de notificações.');
        }

        $indiceReferencia = $this->db->query("SHOW INDEX FROM notificacao WHERE Key_name = 'idx_notificacao_referencia'");
        if (!$indiceReferencia) {
            throw new RuntimeException('Não foi possível verificar os índices das notificações.');
        }
        if ($indiceReferencia->num_rows === 0
            && !$this->db->query('ALTER TABLE notificacao ADD INDEX idx_notificacao_referencia (id_referencia)')) {
            throw new RuntimeException('Não foi possível indexar as referências de notificações.');
        }
    }

    public function Criar($id_usuario, $tipo, $titulo, $mensagem, $id_referencia = null) {
        $sql = "INSERT INTO notificacao (id_usuario, tipo, titulo, mensagem, id_referencia) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isssi", $id_usuario, $tipo, $titulo, $mensagem, $id_referencia);
        return $stmt->execute();
    }

    public function ListarRecentes($id_usuario, $limite = 8) {
        $limite = max(1, min(30, (int) $limite));
        $sql = "SELECT id_notificacao, tipo, titulo, mensagem, id_referencia, lida, criado_em
                FROM notificacao
                WHERE id_usuario = ?
                ORDER BY criado_em DESC
                LIMIT {$limite}";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function ContarNaoLidas($id_usuario) {
        $sql = "SELECT COUNT(*) AS total FROM notificacao WHERE id_usuario = ? AND lida = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return (int) ($resultado['total'] ?? 0);
    }

    public function MarcarTodasComoLidas($id_usuario) {
        $sql = "UPDATE notificacao SET lida = 1 WHERE id_usuario = ? AND lida = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    public function MarcarComoLidaEObter($id_notificacao, $id_usuario) {
        $sql = "SELECT id_notificacao, tipo, id_referencia
                FROM notificacao
                WHERE id_notificacao = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_notificacao, $id_usuario);
        $stmt->execute();
        $notificacao = $stmt->get_result()->fetch_assoc();

        if (!$notificacao) {
            return null;
        }

        $sql = "UPDATE notificacao SET lida = 1 WHERE id_notificacao = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_notificacao, $id_usuario);
        $stmt->execute();

        return $notificacao;
    }
}
