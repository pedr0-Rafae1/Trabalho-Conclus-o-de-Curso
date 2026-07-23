<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class UsuarioDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection(); 
    }

    public function Cadastrar(Usuario $usuario) {
        $sql = "INSERT INTO usuario (nome, idade, email, senha) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("siss", $usuario->nome, $usuario->idade, $usuario->email, $usuario->senha);
        return $stmt->execute();
    }

    public function Listar() {
        $sql = "SELECT * FROM usuario";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    public function Remover($id_usuario) {
        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $cod); 
        return $stmt->execute();
    }

    public function Atualizar(Usuario $usuario) {
        $sql = "UPDATE usuario SET nome=?, idade=?, email=?, senha=? WHERE id_usuario=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sissi", $usuario->nome, $usuario->idade, $usuario->email, $usuario->senha, $usuario->id_usuario);
        return $stmt->execute();
    }

    public function buscarPorEmail($email) {
    $sql = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $this->db->prepare($sql);
    
    if ($stmt) {

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc();
    }
    
    return null;
    }

    public function BuscarPorId($id) {
        $sql = "SELECT nome, idade, email FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
