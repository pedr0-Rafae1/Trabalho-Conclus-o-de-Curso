<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class UsuarioDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection(); 
    }

    public function Cadastrar(Usuario $usuario) {
        $sql = "INSERT INTO usuario (nome, idade, email, senha, tipo_usuario) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $senhaHash = password_hash($usuario->senha, PASSWORD_DEFAULT);
        
        $stmt->bind_param("sisss", $usuario->nome, $usuario->idade, $usuario->email, $senhaHash, $usuario->tipo_usuario);
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
        $stmt->bind_param("i", $id_usuario); 
        return $stmt->execute();
    }

    public function Atualizar(Usuario $usuario) {
        // Se a senha não estiver vazia, atualiza ela com hash
        if (!empty($usuario->senha)) {
            $sql = "UPDATE usuario SET nome = ?, idade = ?, senha = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            $senhaHash = password_hash($usuario->senha, PASSWORD_DEFAULT);
            $stmt->bind_param("sisi", $usuario->nome, $usuario->idade, $senhaHash, $usuario->id_usuario);
        } else {
            // Se o usuário não digitou nova senha no perfil, mantém a atual
            $sql = "UPDATE usuario SET nome = ?, idade = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sii", $usuario->nome, $usuario->idade, $usuario->id_usuario);
        }
        
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
        $sql = "SELECT id_usuario, nome, idade, email, senha, tipo_usuario, homologado FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}