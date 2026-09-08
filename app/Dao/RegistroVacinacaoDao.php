<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class RegistroVacinacaoDAO {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection(); 
    }

    public function Cadastrar( RegistroVacinacao $registrovacinacao) {

        $sql = "INSERT INTO registrovacinacao (id_animal, nome_vacina, data_aplicacao, aplicador, dose) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);

        $data = date('Y-m-d', strtotime($registrovacinacao->data_aplicacao));

        $stmt->bind_param("issss", $registrovacinacao->id_animal, $registrovacinacao->nome_vacina, $data, $registrovacinacao->aplicador, $registrovacinacao->dose);
        return $stmt->execute();
    }

    public function ListarPorUsuario($id_logado) {

        $lista = [];
        $sql ="SELECT v.* FROM registrovacinacao v INNER JOIN animal a ON v.id_animal = a.id_animal WHERE a.id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_logado);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $registrovacinacao = new RegistroVacinacao(
                    $row['id_animal'],
                    $row['nome_vacina'],
                    $row['data_aplicacao'],
                    $row['aplicador'],
                    $row['dose']
                );
                
                $registrovacinacao->id_vacinacao = $row['id_vacinacao']; 
                $lista[] = $registrovacinacao; 
            }
        }
        return $lista;
   }

    public function Remover($id_vacinacao, $id_usuario) {
        $sql = "DELETE v FROM registrovacinacao v 
                INNER JOIN animal a ON v.id_animal = a.id_animal 
                WHERE v.id_vacinacao = ? AND a.id_usuario = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_vacinacao, $id_usuario); 
        return $stmt->execute();
    }

    public function Atualizar(RegistroVacinacao $registrovacinacao, $id_usuario) {
        $sql = "UPDATE registrovacinacao v 
                INNER JOIN animal a ON v.id_animal = a.id_animal 
                SET v.id_animal=?, v.nome_vacina=?, v.data_aplicacao=?, v.aplicador=?, v.dose=? 
                WHERE v.id_vacinacao=? AND a.id_usuario=?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issssii", $registrovacinacao->id_animal, $registrovacinacao->nome_vacina, $registrovacinacao->data_aplicacao, $registrovacinacao->aplicador, $registrovacinacao->dose, $registrovacinacao->id_vacinacao, $id_usuario);
        return $stmt->execute();
    }

    public function BuscarPorId($id_vacinacao, $id_usuario){
        $sql = "SELECT v.* FROM registrovacinacao v 
                INNER JOIN animal a ON v.id_animal = a.id_animal 
                WHERE v.id_vacinacao = ? AND a.id_usuario = ?"; 
                
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_vacinacao, $id_usuario);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $registrovacinacao = new RegistroVacinacao ($row['id_animal'], $row['nome_vacina'], $row['data_aplicacao'], $row['aplicador'], $row['dose']);
            $registrovacinacao->id_vacinacao = $row['id_vacinacao']; 
            return $registrovacinacao;
        }
        return null;
    }

    public function contarVacinasPorUsuario($id_logado) {
        $sql = "SELECT COUNT(v.id_vacinacao) as total FROM registrovacinacao v INNER JOIN animal a ON v.id_animal = a.id_animal WHERE a.id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_logado);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'] ?? 0;
    }
}