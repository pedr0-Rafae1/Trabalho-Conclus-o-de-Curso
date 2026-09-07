<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class RegistroPesoDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection(); 
    }

    public function Cadastrar(RegistroPeso $registropeso, ){

        $sql = "INSERT INTO registropeso (id_animal, peso_anterior, peso_atual, data_pessagem) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $data = date('Y-m-d', strtotime($registropeso->data_pessagem));

        $stmt->bind_param("idds", $registropeso->id_animal, $registropeso->peso_anterior,  $registropeso->peso_atual, $data);
        return $stmt->execute();
    }

    public function ListarPorUsuario($id_logado){

        $lista = [];
        $sql = "SELECT p.* FROM registropeso p INNER JOIN animal a ON p.id_animal = a.id_animal WHERE a.id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_logado);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $registropeso = new RegistroPeso(
                    $row['id_animal'],
                    $row['peso_anterior'],
                    $row['peso_atual'],
                    $row['data_pessagem']
                );
                $registropeso->id_peso = $row['id_peso'];
                $lista[] = $registropeso;
            }
        }
        return $lista;
    }

    public function ListarPorAnimal($id_animal, $id_usuario){
 
        $lista = [];
        $sql = "SELECT p.* FROM registropeso p INNER JOIN animal a ON p.id_animal = a.id_animal WHERE p.id_animal = ? AND a.id_usuario = ? ORDER BY p.data_pessagem ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_animal, $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
 
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $registropeso = new RegistroPeso(
                    $row['id_animal'],
                    $row['peso_anterior'],
                    $row['peso_atual'],
                    $row['data_pessagem']
                );
                $registropeso->id_peso = $row['id_peso'];
                $lista[] = $registropeso;
            }
        }
        return $lista;
    }

    public function Remover($id_peso, $id_usuario){
        
        $sql = "DELETE p FROM registropeso p 
                INNER JOIN animal a ON p.id_animal = a.id_animal 
                WHERE p.id_peso = ? AND a.id_usuario = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_peso, $id_usuario); 
        return $stmt->execute();
    }

    public function Atualizar(RegistroPeso $registropeso, $id_usuario){
       
        $sql = "UPDATE registropeso p 
                INNER JOIN animal a ON p.id_animal = a.id_animal 
                SET p.id_animal=?, p.peso_anterior=?, p.peso_atual=?, p.data_pessagem=? 
                WHERE p.id_peso=? AND a.id_usuario=?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iddsii", $registropeso->id_animal, $registropeso->peso_anterior, $registropeso->peso_atual, $registropeso->data_pessagem, $registropeso->id_peso, $id_usuario);
        return $stmt->execute();
    }

    public function BuscarPorId($id_peso, $id_usuario){
       
        $sql = "SELECT p.* FROM registropeso p 
                INNER JOIN animal a ON p.id_animal = a.id_animal 
                WHERE p.id_peso = ? AND a.id_usuario = ?"; 
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_peso, $id_usuario);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $registropeso = new RegistroPeso ($row['id_animal'], $row['peso_anterior'], $row['peso_atual'], $row['data_pessagem']);
            $registropeso->id_peso = $row['id_peso']; 
            return $registropeso;
        }
        return null;
    }
}