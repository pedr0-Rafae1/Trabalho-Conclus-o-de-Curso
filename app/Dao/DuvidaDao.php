<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';
require_once __DIR__ . '/../Model/Duvida.php';

class DuvidaDao {
    private $db;
    
    public function __construct() {
        $this->db = ConexaoBD::getConnection();
    }

    public function Cadastrar(Duvida $duvida) {
        $sql = "INSERT INTO duvida (id_usuario, pergunta) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $duvida->id_usuario, $duvida->pergunta);
        return $stmt->execute();
    }

    public function ListarPorUsuario($id_usuario) {
        $lista = [];
        $sql = "SELECT * FROM duvida WHERE id_usuario = ? ORDER BY data_pergunta DESC";
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

    // Histórico de tudo que um veterinário já respondeu
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

        $sql = "UPDATE duvida
                SET resposta = ?, id_veterinario = ?, status = 'respondida', data_resposta = NOW()
                WHERE id_duvida = ? AND status = 'pendente'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sii", $resposta, $id_veterinario, $id_duvida);
        return $stmt->execute();
    }

    private function montarDuvida($row) {
        $duvida = new Duvida($row['id_usuario'], $row['pergunta']);
        $duvida->id_duvida = $row['id_duvida'];
        $duvida->id_veterinario = $row['id_veterinario'];
        $duvida->resposta = $row['resposta'];
        $duvida->data_pergunta = $row['data_pergunta'];
        $duvida->data_resposta = $row['data_resposta'];
        $duvida->status = $row['status'];
        return $duvida;
    }
}

?>