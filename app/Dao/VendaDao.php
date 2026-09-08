<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';
require_once __DIR__ . '/../Model/Venda.php';

class VendaDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection();
    }

    public function Cadastrar(Venda $venda, $id_usuario) {

        $sqlCheck = "SELECT vendido FROM animal WHERE id_animal = ? AND id_usuario = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $venda->id_animal, $id_usuario);
        $stmtCheck->execute();
        $animal = $stmtCheck->get_result()->fetch_assoc();

        if (!$animal) {
            return "nao_encontrado";
        }
        if ((int) $animal['vendido'] === 1) {
            return "ja_vendido";
        }

        $this->db->begin_transaction();

        $sql = "INSERT INTO venda (id_animal, comprador, valor_venda, data_venda) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isds", $venda->id_animal, $venda->comprador, $venda->valor_venda, $venda->data_venda);
        $okVenda = $stmt->execute();

        $sqlUpdate = "UPDATE animal SET vendido = 1 WHERE id_animal = ? AND id_usuario = ?";
        $stmtUpdate = $this->db->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ii", $venda->id_animal, $id_usuario);
        $okUpdate = $stmtUpdate->execute();

        if ($okVenda && $okUpdate) {
            $this->db->commit();
            return true;
        }

        $this->db->rollback();
        return "erro";
    }

    public function ListarPorUsuario($id_usuario) {
        $lista = [];
        $sql = "SELECT v.*, a.brinco, a.raca
                FROM venda v
                INNER JOIN animal a ON a.id_animal = v.id_animal
                WHERE a.id_usuario = ?
                ORDER BY v.data_venda DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $venda = new Venda($row['id_animal'], $row['comprador'], $row['valor_venda'], $row['data_venda']);
            $venda->id_venda = $row['id_venda'];
            $venda->brinco = $row['brinco'];
            $venda->raca = $row['raca'];
            $lista[] = $venda;
        }
        return $lista;
    }

    public function BuscarPorAnimal($id_animal, $id_usuario) {
        $sql = "SELECT v.* FROM venda v
                INNER JOIN animal a ON a.id_animal = v.id_animal
                WHERE v.id_animal = ? AND a.id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_animal, $id_usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function ListarAnimaisDisponiveis($id_usuario) {
        $lista = [];
        $sql = "SELECT id_animal, brinco, raca FROM animal WHERE id_usuario = ? AND vendido = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $lista[] = $row;
        }
        return $lista;
    }
}

?>