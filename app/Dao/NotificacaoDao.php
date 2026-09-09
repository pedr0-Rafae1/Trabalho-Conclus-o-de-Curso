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
            lida TINYINT(1) NOT NULL DEFAULT 0,
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_notificacao_usuario_lida (id_usuario, lida),
            INDEX idx_notificacao_criado (criado_em)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->db->query($sql);
    }

    public function Criar($id_usuario, $tipo, $titulo, $mensagem) {
        $sql = "INSERT INTO notificacao (id_usuario, tipo, titulo, mensagem) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isss", $id_usuario, $tipo, $titulo, $mensagem);
        return $stmt->execute();
    }

    public function ListarRecentes($id_usuario, $limite = 8) {
        $limite = max(1, min(30, (int) $limite));
        $sql = "SELECT id_notificacao, tipo, titulo, mensagem, lida, criado_em
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
}
