<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class AnimalDAO {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection(); 
    }

    public function Cadastrar(Animal $animal, $id_usuario_logado) {
        $sql = "INSERT INTO animal (brinco, idade, especie, raca, data_nascimento, peso, altura, id_usuario) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sisssddi", $animal->brinco, $animal->idade, $animal->especie, $animal->raca, $animal->data_nascimento, $animal->peso, $animal->altura, $id_usuario_logado);
        return $stmt->execute();
    }

    public function ListarPorUsuario($id_logado) {
        $lista = [];
        $sql = "SELECT * FROM animal where id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_logado);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
           while ($row = $result->fetch_assoc()) {
                $animal = (object) $row; 
                $lista[] = $animal;
            }
        }
        return $lista;
    }

    public function Remover($id_animal, $id_usuario) {
        $sql = "DELETE FROM animal WHERE id_animal = ? AND id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $id_animal, $id_usuario); 
        return $stmt->execute();
    }

    public function Atualizar(Animal $animal, $id_usuario) {
        $sql = "UPDATE animal SET brinco=?, idade=?, especie=?, raca=?, data_nascimento=?, peso=?, altura=? WHERE id_animal=? AND id_usuario=?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sisssddii", $animal->brinco, $animal->idade, $animal->especie, $animal->raca, $animal->data_nascimento, $animal->peso, $animal->altura, $animal->id_animal, $id_usuario);
        return $stmt->execute();
    }

    public function BuscarPorId($id_animal) {
        $sql = "SELECT * FROM animal WHERE id_animal = ?"; 
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_animal);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

    if ($row) {
        $animal = new Animal($row['brinco'], $row['idade'], $row['especie'], $row['raca'], $row['data_nascimento'], $row['peso'], $row['altura']);
        $animal->id_animal = $row['id_animal']; 
        return $animal;
    }
        return null;
    }

    public function buscarPorIdEUsuario($id_animal, $id_usuario) {
    $sql = "SELECT * FROM animal WHERE id_animal = ? AND id_usuario = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ii", $id_animal, $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc(); 
}

    // Uso exclusivo do veterinário: busca qualquer animal, sem exigir que seja do usuário logado
    public function buscarPorIdQualquerDono($id_animal) {
        $sql = "SELECT a.*, u.nome AS dono_nome
                FROM animal a
                INNER JOIN usuario u ON u.id_usuario = a.id_usuario
                WHERE a.id_animal = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_animal);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function ListarTodosComDono() {
        $lista = [];
        $sql = "SELECT a.*, u.nome AS dono_nome
                FROM animal a
                INNER JOIN usuario u ON u.id_usuario = a.id_usuario
                ORDER BY u.nome, a.brinco";
        $result = $this->db->query($sql);
        while ($row = $result->fetch_assoc()) {
            $lista[] = (object) $row;
        }
        return $lista;
    }

    public function contarAnimaisPorUsuario($id_usuario) {
        $sql = "SELECT COUNT(*) as total FROM animal WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado['total'];
    }

    public function ObterPesoAnterior($id_animal) {

    $conn = ConexaoBD::getConnection();
    
    $sql = "SELECT peso_atual FROM registropeso WHERE id_animal = ? ORDER BY data_pessagem DESC, id_peso DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_animal);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $stmt->close();
        $conn->close();
        return floatval($row['peso_atual']);
    }
    $stmt->close();
    
    $sqlAnimal = "SELECT peso FROM animal WHERE id_animal = ?";
    $stmtAnimal = $conn->prepare($sqlAnimal);
    $stmtAnimal->bind_param("i", $id_animal);
    $stmtAnimal->execute();
    $resultadoAnimal = $stmtAnimal->get_result();
    
    if ($resultadoAnimal->num_rows > 0) {
        $rowAnimal = $resultadoAnimal->fetch_assoc();
        $stmtAnimal->close();
        $conn->close();
        return floatval($rowAnimal['peso']);
    }
    
    $stmtAnimal->close();
    $conn->close();
    return 0;
}
}