<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class NotificacaoDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection();
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
