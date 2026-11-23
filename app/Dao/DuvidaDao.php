<?php
date_default_timezone_set('America/Sao_Paulo');
require_once __DIR__ . '/../Conexao/ConexaoBD.php';
require_once __DIR__ . '/../Model/Duvida.php';

class DuvidaDao {
    private $db;
    
    public function __construct() {
        $this->db = ConexaoBD::getConnection();
        $this->garantirTabelaMensagens();
    }

    private function garantirTabelaMensagens() {
        $sql = "CREATE TABLE IF NOT EXISTS duvida_mensagem (
            id_mensagem INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            id_duvida INT UNSIGNED NOT NULL,
            id_usuario INT UNSIGNED NOT NULL,
            mensagem TEXT NOT NULL,
            enviado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_duvida_mensagem_duvida FOREIGN KEY (id_duvida) REFERENCES duvida(id_duvida) ON DELETE CASCADE,
            CONSTRAINT fk_duvida_mensagem_usuario FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE,
            INDEX idx_duvida_mensagem_conversa (id_duvida, enviado_em)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        if (!$this->db->query($sql)) {
            throw new RuntimeException('Não foi possível preparar as mensagens do canal de dúvidas.');
        }
    }

    public function Cadastrar(Duvida $duvida) {
        $duvida->data_pergunta = date('Y-m-d H:i:s');
        $sql = "INSERT INTO duvida (id_usuario, pergunta, data_pergunta) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iss", $duvida->id_usuario, $duvida->pergunta, $duvida->data_pergunta);
        return $stmt->execute() ? (int) $this->db->insert_id : false;
    }

    public function ListarPorUsuario($id_usuario) {
        $lista = [];
        $sql = "SELECT d.*, v.nome AS veterinario_nome
                FROM duvida d
                LEFT JOIN usuario v ON v.id_usuario = d.id_veterinario
                WHERE d.id_usuario = ?
                ORDER BY d.data_pergunta DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $lista[] = $this->montarDuvida($row);
        }
        return $lista;
    }

    public function ListarPendentes() {
        $lista = [];
        $sql = "SELECT d.*, u.nome AS usuario_nome
                FROM duvida d
                INNER JOIN usuario u ON u.id_usuario = d.id_usuario
                WHERE d.status = 'pendente'
                ORDER BY d.data_pergunta ASC";
        $result = $this->db->query($sql);
        while ($row = $result->fetch_assoc()) {
            $duvida = $this->montarDuvida($row);
            $duvida->usuario_nome = $row['usuario_nome'];
            $lista[] = $duvida;
        }
        return $lista;
    }

    public function ListarParaVeterinario($id_veterinario) {
        $lista = [];
        $sql = "SELECT d.*, u.nome AS usuario_nome, v.nome AS veterinario_nome
                FROM duvida d
                INNER JOIN usuario u ON u.id_usuario = d.id_usuario
                LEFT JOIN usuario v ON v.id_usuario = d.id_veterinario
                WHERE d.status = 'pendente'
                   OR (d.status = 'respondida' AND (
                        d.id_veterinario = ?
                        OR EXISTS (
                            SELECT 1 FROM notificacao n
                            WHERE n.id_usuario = ?
                              AND n.id_referencia = d.id_duvida
                              AND n.tipo = 'duvida_nova'
                        )
                   ))
                ORDER BY d.data_pergunta DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_veterinario, $id_veterinario);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $duvida = $this->montarDuvida($row);
            $duvida->usuario_nome = $row['usuario_nome'];
            $lista[] = $duvida;
        }
        return $lista;
    }

    public function ListarMensagens($id_duvida) {
        $lista = [];
        $sql = "SELECT m.id_usuario, m.mensagem, m.enviado_em, u.nome AS autor_nome, u.tipo_usuario
                FROM duvida_mensagem m
                INNER JOIN usuario u ON u.id_usuario = m.id_usuario
                WHERE m.id_duvida = ?
                ORDER BY m.enviado_em ASC, m.id_mensagem ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_duvida);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $lista[] = $row;
        }
        return $lista;
    }

    public function ObterPorId($id_duvida) {
        $sql = "SELECT d.*, u.nome AS usuario_nome
                FROM duvida d
                INNER JOIN usuario u ON u.id_usuario = d.id_usuario
                WHERE d.id_duvida = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_duvida);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function ListarIdsVeterinariosHomologados() {
        $ids = [];
        $result = $this->db->query(
            "SELECT id_usuario FROM usuario
             WHERE tipo_usuario = 'Veterinario' AND homologado = 1"
        );
        while ($row = $result->fetch_assoc()) {
            $ids[] = (int) $row['id_usuario'];
        }
        return $ids;
    }
    
    public function ListarRespondidasPorVeterinario($id_veterinario) {
        $lista = [];
        $sql = "SELECT d.*, u.nome AS usuario_nome
                FROM duvida d
                INNER JOIN usuario u ON u.id_usuario = d.id_usuario
                WHERE d.id_veterinario = ?
                ORDER BY d.data_resposta DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_veterinario);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $duvida = $this->montarDuvida($row);
            $duvida->usuario_nome = $row['usuario_nome'];
            $lista[] = $duvida;
        }
        return $lista;
    }

    public function Responder($id_duvida, $id_veterinario, $resposta) {
        $sqlCheck = "SELECT tipo_usuario, homologado FROM usuario WHERE id_usuario = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $id_veterinario);
        $stmtCheck->execute();
        $usuario = $stmtCheck->get_result()->fetch_assoc();

        if (!$usuario || $usuario['tipo_usuario'] !== 'Veterinario' || (int)$usuario['homologado'] !== 1) {
            return false;
        }

        $agora = date('Y-m-d H:i:s');
        $sql = "UPDATE duvida
                SET resposta = ?, id_veterinario = ?, status = 'respondida', data_resposta = ?
                WHERE id_duvida = ? AND status = 'pendente'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sisi", $resposta, $id_veterinario, $agora, $id_duvida);
        return $stmt->execute() && $stmt->affected_rows === 1;
    }

    public function EnviarMensagem($id_duvida, $id_usuario, $tipo_usuario, $mensagem) {
        $sql = "SELECT id_usuario, id_veterinario, status
                FROM duvida
                WHERE id_duvida = ? AND status = 'respondida'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_duvida);
        $stmt->execute();
        $duvida = $stmt->get_result()->fetch_assoc();

        if (!$duvida || empty($duvida['id_veterinario'])) {
            return false;
        }

        if ($tipo_usuario === 'Veterinario' && (int) $duvida['id_veterinario'] === (int) $id_usuario) {
            $destinatario = (int) $duvida['id_usuario'];
        } elseif ($tipo_usuario === 'Pecuarista' && (int) $duvida['id_usuario'] === (int) $id_usuario) {
            $destinatario = (int) $duvida['id_veterinario'];
        } else {
            return false;
        }

        $sql = "INSERT INTO duvida_mensagem (id_duvida, id_usuario, mensagem) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iis", $id_duvida, $id_usuario, $mensagem);
        if (!$stmt->execute()) {
            return false;
        }

        return $destinatario;
    }

    private function montarDuvida($row) {
        $duvida = new Duvida($row['id_usuario'], $row['pergunta']);
        $duvida->id_duvida = $row['id_duvida'];
        $duvida->id_veterinario = $row['id_veterinario'];
        $duvida->resposta = $row['resposta'];
        $duvida->data_pergunta = $row['data_pergunta'];
        $duvida->data_resposta = $row['data_resposta'];
        $duvida->status = $row['status'];
        $duvida->veterinario_nome = $row['veterinario_nome'] ?? null;
        return $duvida;
    }
}

?>