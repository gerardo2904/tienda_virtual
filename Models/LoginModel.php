<?php
    class LoginModel extends Mysql{
        private $intIdUsuario;
        private $strUsuario;
        private $strPassword;
        private $strToken;

        public function __construct(){
           parent::__construct();
        }

        public function loginUser(string $usuario, string $password){
            $this->strUsuario = $usuario;
            $this->strPassword = $password;

            $sql_e = "SELECT idempresa FROM persona WHERE
                    email_user = '$this->strUsuario' and 
                    password   = '$this->strPassword' and
                    status != 0 ";
            $request_e = $this->select($sql_e);

            $empresa=$request_e['idempresa'];

            $sql = "SELECT idpersona, idempresa, status, 
                    (select  if(idempresa=0, '',nombreempresa) from empresas where idempresa= $empresa  limit 1 ) as nombreempresa,
                    (select  img from imagenempresa where empresaid=$empresa limit 1) as img
                    FROM persona WHERE
                    email_user = '$this->strUsuario' and 
                    password   = '$this->strPassword' and
                    status != 0 and 
                    idempresa = $empresa ";
            $request = $this->select($sql);
            //dep($request);die();
            return $request;
        }

        public function sessionLogin(int $iduser){
            $this->intIdUsuario = $iduser;

            //Para buscar un rol.

            $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono,
                           p.email_user, p.nit, p.nombrefiscal, p.direccionfiscal, 
                           r.idrol, r.nombrerol, p.status, p.idempresa
                    FROM persona p
                    INNER JOIN rol r
                    ON p.rolid = r.idrol
                    WHERE p.idpersona = $this->intIdUsuario";
            $request = $this->select($sql);
            $_SESSION['userData'] = $request;
            return $request;
        }

        public function getUserEmail(string $strEmail){
            $this->strUsuario = $strEmail;
            $sql = "SELECT idpersona, nombres, apellidos, status FROM persona 
                    WHERE email_user = '$this->strUsuario' and status = 1 ";
            $request = $this->select($sql);
            return $request;

        }

        public function setTokenUser(int $idpersona, string $token){
            $this->intIdUsuario = $idpersona;
            $this->strToken = $token;
            $sql = "UPDATE persona SET token = ? WHERE idpersona = $this->intIdUsuario ";
            $arrData = array($this->strToken);
            $request = $this->update($sql, $arrData);
            return $request;
        }

        public function getUsuario(string $email, string $token){
            $this->strUsuario = $email;
            $this->strToken = $token;

            $sql = "SELECT idpersona FROM persona WHERE
                    email_user = '$this->strUsuario' and 
                    token = '$this->strToken' and 
                    status = 1";
            $request = $this->select($sql);
            return $request;
        }

        public function insertPassword(int $idPersona, string $Password){
            $this->intIdUsuario = $idPersona;
            $this->strPassword = $Password;
            $sql = "UPDATE persona SET password = ?, token = ? WHERE idpersona = $this->intIdUsuario";
            $arrData = array($this->strPassword,"");
            $request = $this->update($sql,$arrData);
            return $request;
        }
    }
?>
