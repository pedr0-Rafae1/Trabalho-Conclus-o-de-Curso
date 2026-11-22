<?php
require_once __DIR__ . '/../Conexao/ConexaoBD.php';

class UsuarioDao {
    private $db;

    public function __construct() {
        $this->db = ConexaoBD::getConnection(); 
    }

    public function Cadastrar(Usuario $usuario) {
        $sql = "INSERT INTO usuario (nome, idade, email, senha, tipo_usuario, homologado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        $senhaHash = password_hash($usuario->senha, PASSWORD_DEFAULT);
        $homologado = 0;
        
        $stmt->bind_param("sisssi", $usuario->nome, $usuario->idade, $usuario->email, $senhaHash, $usuario->tipo_usuario, $homologado);
        return $stmt->execute();
    }

    public function HomologarVeterinario($id_usuario) {
        $sql = "UPDATE usuario SET homologado = 1 WHERE id_usuario = ? AND tipo_usuario = 'Veterinario'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    public function Listar() {
        $sql = "SELECT * FROM usuario";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC); 
    }

    public function Remover($id_usuario) {
        $usuario = $this->BuscarPorId($id_usuario);
        if ($usuario && !empty($usuario['foto_perfil'])) {
            $caminhoFoto = __DIR__ . '/../imagem/' . $usuario['foto_perfil'];
            if (file_exists($caminhoFoto)) {
                @unlink($caminhoFoto);
            }
        }

        $this->db->begin_transaction();

        $stmt = $this->db->prepare(
            "DELETE FROM atendimento WHERE id_veterinario = ? OR id_animal IN
             (SELECT id_animal FROM animal WHERE id_usuario = ?)"
        );
        $stmt->bind_param("ii", $id_usuario, $id_usuario);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->db->prepare(
            "DELETE FROM duvida WHERE id_usuario = ? OR id_veterinario = ?"
        );
        $stmt->bind_param("ii", $id_usuario, $id_usuario);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->db->prepare("DELETE FROM notificacao WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();

        $stmt = $this->db->prepare("DELETE FROM animal WHERE id_usuario = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->close();

        $sql = "DELETE FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            $this->db->rollback();
            return false;
        }
        $stmt->bind_param("i", $id_usuario); 
        $ok = $stmt->execute();
        $stmt->close();

        if ($ok) {
            $this->db->commit();
            return true;
        }

        $this->db->rollback();
        return false;
    }

    public function Atualizar(Usuario $usuario) {
        if (!empty($usuario->senha)) {
            $sql = "UPDATE usuario SET nome = ?, idade = ?, senha = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            $senhaHash = password_hash($usuario->senha, PASSWORD_DEFAULT);
            $stmt->bind_param("sisi", $usuario->nome, $usuario->idade, $senhaHash, $usuario->id_usuario);
        } else {
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

    public function RegistrarTentativa($id_usuario, $tentativas) {
        $sql = "UPDATE usuario SET tentativas_login = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $tentativas, $id_usuario);
        return $stmt->execute();
    }

    public function BloquearConta($id_usuario) {
        $sql = "UPDATE usuario SET tentativas_login = 0, bloqueado_ate = DATE_ADD(NOW(), INTERVAL 1 MINUTE) WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    public function ZerarTentativas($id_usuario) {
        $sql = "UPDATE usuario SET tentativas_login = 0, bloqueado_ate = NULL WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    public function AtualizarFoto($id_usuario, $nomeArquivo) {
        $sql = "UPDATE usuario SET foto_perfil = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $nomeArquivo, $id_usuario);
        return $stmt->execute();
    }

    public function BuscarPorId($id) {
        $sql = "SELECT id_usuario, nome, idade, email, senha, tipo_usuario, homologado, foto_perfil FROM usuario WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Marca o e-mail como verificado diretamente (usado enquanto a validação por código está desativada)
    public function MarcarEmailVerificado($id_usuario) {
        $sql = "UPDATE usuario SET email_verificado = 1 WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }

    public function SalvarCodigoVerificacao($id_usuario, $codigo) {
        $expiracao = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $sql = "UPDATE usuario SET codigo_verificacao = ?, codigo_expiracao = ?, tentativas_codigo = 0 WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $codigo, $expiracao, $id_usuario);
        return $stmt->execute();
    }

    public function ConfirmarEmail($email, $codigo) {
        // Busca o usuário para validar expiração e tentativas
        $stmt = $this->db->prepare("SELECT id_usuario, codigo_verificacao, codigo_expiracao, tentativas_codigo FROM usuario WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $usuario = $stmt->get_result()->fetch_assoc();

        if (!$usuario || empty($usuario['codigo_verificacao'])) {
            return 'invalido';
        }

        // Limite de 5 tentativas
        if ((int) $usuario['tentativas_codigo'] >= 5) {
            return 'bloqueado';
        }

        // Código expirado (15 minutos)
        if (!empty($usuario['codigo_expiracao']) && strtotime($usuario['codigo_expiracao']) < time()) {
            return 'expirado';
        }

        // Código errado: incrementa tentativas
        if ($usuario['codigo_verificacao'] !== $codigo) {
            $stmtInc = $this->db->prepare("UPDATE usuario SET tentativas_codigo = tentativas_codigo + 1 WHERE id_usuario = ?");
            $stmtInc->bind_param("i", $usuario['id_usuario']);
            $stmtInc->execute();
            return 'invalido';
        }

        // Código correto: confirma e limpa
        $sql = "UPDATE usuario SET email_verificado = 1, codigo_verificacao = NULL, codigo_expiracao = NULL, tentativas_codigo = 0
                WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuario['id_usuario']);
        $stmt->execute();
        return $stmt->affected_rows > 0 ? true : 'invalido';
    }

    // Salva o código de recuperação de 6 dígitos (válido por 30 minutos).
    // Reutiliza as colunas token_recuperacao/expiracao_token para não exigir migration.
    public function SalvarCodigoRecuperacao($email, $codigo) {
        $expiracao = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $sql = "UPDATE usuario SET token_recuperacao = ?, expiracao_token = ? WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $codigo, $expiracao, $email);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    // Busca usuário pelo par e-mail + código de recuperação ainda válido
    public function BuscarPorCodigoRecuperacao($email, $codigo) {
        $sql = "SELECT * FROM usuario WHERE email = ? AND token_recuperacao = ? AND expiracao_token > NOW()";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $email, $codigo);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Atualiza a senha (hash) e invalida o código usado
    public function RedefinirSenha($id_usuario, $novaSenha) {
        $sql = "UPDATE usuario SET senha = ?, token_recuperacao = NULL, expiracao_token = NULL WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $hash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt->bind_param("si", $hash, $id_usuario);
        return $stmt->execute();
    }

    public function ListarVeterinariosPendentes() {
        $sql = "SELECT id_usuario, nome, email FROM usuario WHERE tipo_usuario = 'Veterinario' AND homologado = 0 ORDER BY nome ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function Homologar($id_usuario) {
        $sql = "UPDATE usuario SET homologado = 1 WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        return $stmt->execute();
    }
}