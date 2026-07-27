<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';
require_once __DIR__ . '/../Model/Atendimento.php';

class AtendimentoDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection();
    }

    public function Cadastrar(Atendimento $atendimento, $id_veterinario) {

        $sqlCheck = "SELECT tipo_usuario, homologado FROM usuario WHERE id_usuario = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("i", $id_veterinario);
        $stmtCheck->execute();
        $usuario = $stmtCheck->get_result()->fetch_assoc();

        if (!$usuario || $usuario['tipo_usuario'] !== 'Veterinario' || (int) $usuario['homologado'] !== 1) {
            return "nao_autorizado";
        }

        $sql = "INSERT INTO atendimento (id_animal, id_veterinario, data_atendimento, descricao, diagnostico, recomendacao)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "iissss",
            $atendimento->id_animal,
            $id_veterinario,
            $atendimento->data_atendimento,
            $atendimento->descricao,
            $atendimento->diagnostico,
            $atendimento->recomendacao
        );

        return $stmt->execute() ? true : "erro";
    }

    public function ListarPorAnimal($id_animal, $id_usuario) {
        $lista = [];
        $sql = "SELECT at.*, u.nome AS veterinario_nome
                FROM atendimento at
                INNER JOIN animal a ON a.id_animal = at.id_animal
                INNER JOIN usuario u ON u.id_usuario = at.id_veterinario
                WHERE at.id_animal = ? AND a.id_usuario = ?
                ORDER BY at.data_atendimento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_animal, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $lista[] = $this->montar($row);
        }
        return $lista;
    }

    public function ListarPorVeterinario($id_veterinario) {
        $lista = [];
        $sql = "SELECT at.*, a.brinco, a.raca, u.nome AS dono_nome
                FROM atendimento at
                INNER JOIN animal a ON a.id_animal = at.id_animal
                INNER JOIN usuario u ON u.id_usuario = a.id_usuario
                WHERE at.id_veterinario = ?
                ORDER BY at.data_atendimento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_veterinario);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $atendimento = $this->montar($row);
            $atendimento->brinco = $row['brinco'];
            $atendimento->raca = $row['raca'];
            $atendimento->dono_nome = $row['dono_nome'];
            $lista[] = $atendimento;
        }
        return $lista;
    }

    private function montar($row) {
        $atendimento = new Atendimento($row['id_animal'], $row['data_atendimento'], $row['descricao'], $row['diagnostico'], $row['recomendacao']);
        $atendimento->id_atendimento = $row['id_atendimento'];
        $atendimento->id_veterinario = $row['id_veterinario'];
        if (isset($row['veterinario_nome'])) {
            $atendimento->veterinario_nome = $row['veterinario_nome'];
        }
        return $atendimento;
    }
}

?>