<?php
    class UsuariosModel extends Mysql{
        private $intIdUsuario;
        private $strIdentificacion;
        private $strNombre;
        private $strApellido;
        private $intTelefono;
        private $strEmail;
        private $strPassword;
        private $strToken;
        private $intTipoId;
        private $intStatus;
        private $strNit;
        private $strNomFiscal;
        private $strDirFiscal;
        private $intEmpresa;
        
        public function __construct(){
           parent::__construct();
        }

        public function insertUsuario(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $status, int $empresa)
        {
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->intTipoId = $tipoid;
            $this->intStatus = $status;
            $this->intEmpresa = $empresa;
            $return = 0;

            //dep($empresa);die();
            $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}' /*or identificacion = '{$this->strIdentificacion}' */";
            $request = $this->select_all($sql);

            if(empty($request)){
                $query_insert = "INSERT INTO persona(identificacion, nombres, apellidos, telefono, email_user, password, rolid, status, idempresa) VALUES(?,?,?,?,?,?,?,?,?)";
                $arrData = array($this->strIdentificacion, $this->strNombre, $this->strApellido, $this->intTelefono, $this->strEmail, $this->strPassword, $this->intTipoId, $this->intStatus, $this->intEmpresa);
                $request_insert = $this->insert($query_insert, $arrData);
                $return = $request_insert;

            }else{
                $return = 'exist';
            }
            return $return;
        }

        public function selectUsuarios(){
            $whereAdmin = "";
            if($_SESSION['idUser'] != 1){
                $whereAdmin = " and p.idpersona != 1";
            }

            $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user, p.idempresa, e.nombreempresa, p.status, r.idrol, r.nombrerol
                    FROM persona p
                    INNER JOIN rol r ON p.rolid = r.idrol
                    INNER JOIN empresas e ON p.idempresa = e.idempresa 
                    WHERE p.status != 0 ".$whereAdmin."  AND p.rolid!= 7 AND p.rolid!=10 ";
            $request = $this->select_all($sql);
            return $request;
        }

        public function selectUsuario(int $idpersona){
            $this->intIdUsuario = $idpersona;

            $sql = "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user, p.idempresa, e.nombreempresa,   
                           p.nit, p.nombrefiscal, p.direccionfiscal, r.idrol, r.nombrerol, p.status, 
                           DATE_FORMAT(p.datecreated, '%d-%m-%Y') as fechaRegistro 
                           FROM persona p 
                           INNER JOIN rol r ON p.rolid = r.idrol 
                           INNER JOIN empresas e ON p.idempresa = e.idempresa 
                           WHERE p.idpersona = $this->intIdUsuario";

            $request = $this->select($sql);
            return $request;
        }

        public function updateUsuario(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $status, int $empresa){
            $this->intIdUsuario = $idUsuario;
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->intTipoId = $tipoid;
            $this->intStatus = $status;
            $this->intEmpresa = $empresa;

            //dep($this->intEmpresa);

            $sql = "SELECT * FROM persona 
                    WHERE (email_user = '{$this->strEmail}' AND idpersona != $this->intIdUsuario)
                      /* OR (identificacion = '{$this->strIdentificacion}' AND idpersona != $this->intIdUsuario) */";
            $request = $this->select_all($sql);  
            
            if(empty($request)){
                if($this->strPassword != ""){
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, rolid=?, status=?, idempresa=? WHERE idpersona = $this->intIdUsuario";
                    $arrData = array($this->strIdentificacion, 
                                    $this->strNombre,
                                    $this->strApellido,
                                    $this->intTelefono,
                                    $this->strEmail,
                                    $this->strPassword,
                                    $this->intTipoId,
                                    $this->intStatus,
                                    $this->intEmpresa);
                }else {
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, rolid=?, status=?, idempresa=? WHERE idpersona = $this->intIdUsuario";
                    $arrData = array($this->strIdentificacion, 
                                    $this->strNombre,
                                    $this->strApellido,
                                    $this->intTelefono,
                                    $this->strEmail,
                                    $this->intTipoId,
                                    $this->intStatus,
                                    $this->intEmpresa);
                }
                //dep($sql);
                //dep($arrData);
                //die();
                $request = $this->update($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        }

        public function deleteUsuario(int $intIdpersona){
            $this->intIdUsuario = $intIdpersona;
            $sql = "UPDATE persona set status = ? WHERE idpersona = $this->intIdUsuario ";
            $arrData = array(0);
            $request = $this->update($sql, $arrData);
            return $request;
        }

        public function updatePerfil(int $idUsuario, string $identificacion, string $nombre, string $apellido, string $telefono, string $password){
            $this->intIdUsuario = $idUsuario;
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strPassword = $password;

            if($this->strPassword != ""){
                $sql= "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, password=? 
                       WHERE idpersona = $this->intIdUsuario";
                $arrData = array($this->strIdentificacion, 
                                 $this->strNombre,
                                 $this->strApellido,
                                 $this->intTelefono,
                                 $this->strPassword); 
            }else{
                $sql= "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?  
                       WHERE idpersona = $this->intIdUsuario";
                $arrData = array($this->strIdentificacion, 
                                 $this->strNombre,
                                 $this->strApellido,
                                 $this->intTelefono); 
            }
            $request = $this->update($sql,$arrData);
            return $request;
        }

        public function updateDataFiscal(int $idUsuario, string $strNit, string $strNomFiscal, string $strDirFiscal){
            $this->intIdUsuario = $idUsuario;
            $this->strNit = $strNit;
            $this->strNomFiscal = $strNomFiscal;
            $this->strDirFiscal = $strDirFiscal;

            $sql = "UPDATE PERSONA SET nit=?, nombrefiscal=?, direccionfiscal=?
                                   WHERE idpersona = $this->intIdUsuario ";
            $arrData = array($this->strNit, $this->strNomFiscal, $this->strDirFiscal);
            $request = $this->update($sql,$arrData);
            return $request;
        }

        public function empresa(){
            $sql="select nombreempresa as nombre from empresas where status = 1 and idempresa=".intval($_SESSION['userData']['idempresa']);
            $request = $this->select($sql);
            $nempresa = $request['nombre'];
            return $nempresa;
        }
    }
?>
