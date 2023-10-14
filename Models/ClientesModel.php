<?php
    class ClientesModel extends Mysql{
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
        private $strNumExt;
        private $strNumInt;
        private $strColonia;
        private $strCP;
        private $intlistEstado;
        private $intlistCiudad;
        private $intlistRegimen;
        private $intlistCFDI;
        
        public function __construct(){
           parent::__construct();
        }

        public function insertCliente(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, string $nit, string $nomFiscal, string $dirFiscal, string $strNumExt, string $strNumInt, $strColonia, $strCP, int $intlistEstado, int $intlistCiudad, int $intlistRegimen, int $intlistCFDI)
        {
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->intTipoId = $tipoid;
            $this->strNit = $nit;
            $this->strNomFiscal = $nomFiscal;
            $this->strDirFiscal = $dirFiscal;
            $this->strNumExt = $strNumExt;
            $this->strNumInt = $strNumInt;
            $this->strColonia = $strColonia;
            $this->strCP = $strCP;
            $this->intlistEstado = $intlistEstado;
            $this->intlistCiudad = $intlistCiudad;
            $this->intlistRegimen = $intlistRegimen;
            $this->intlistCFDI = $intlistCFDI;
            $return = 0;

            $sql = "SELECT * FROM persona WHERE email_user = '{$this->strEmail}' /*or identificacion = '{$this->strIdentificacion}' */";
            $request = $this->select_all($sql);

            if(empty($request)){
                $query_insert = "INSERT INTO persona(identificacion, nombres, apellidos, telefono, email_user, password, rolid, nit, nombrefiscal, direccionfiscal, numext, numint, colonia, cp, estado, municipio, regfiscal,usocfdi ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                $arrData = array($this->strIdentificacion, 
                                 $this->strNombre, 
                                 $this->strApellido, 
                                 $this->intTelefono, 
                                 $this->strEmail, 
                                 $this->strPassword, 
                                 $this->intTipoId, 
                                 $this->strNit,
                                 $this->strNomFiscal,
                                 $this->strDirFiscal,
                                 $this->strNumExt,
                                 $this->strNumInt,
                                 $this->strColonia,
                                 $this->strCP,
                                 $this->intlistEstado,
                                 $this->intlistCiudad,
                                 $this->intlistRegimen,
                                 $this->intlistCFDI);
                $request_insert = $this->insert($query_insert, $arrData);
                $return = $request_insert;
                //dep($return);die();

            }else{
                $return = 'exist';
            }
            return $return;
        }

        public function selectClientes(){
            $sql = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user, status
                    FROM persona 
                    WHERE rolid = 7 and status != 0 "; // 7 es rolid de clientes
            $request = $this->select_all($sql);
            return $request;
        }

        public function selectCliente(int $idpersona){
            $this->intIdUsuario = $idpersona;

            $sql = "SELECT idpersona, identificacion, nombres, apellidos, telefono, email_user, 
                    nit, nombrefiscal, direccionfiscal, numext, numint, colonia, cp, status, 
                    DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro, regfiscal,
                    usocfdi, estado, municipio, 
                    (select nombre from estados where estados.id=persona.estado)as nestado,        
                    (select nombre from municipios where municipios.id=persona.municipio)as nciudad, 
                    (select descripcion_reg from rfiscal where rfiscal.id=persona.regfiscal) as nregfiscal, 
                    (select descripcion_uso from cfdi where cfdi.id=persona.usocfdi) as ncfdi
                           FROM persona   
                           WHERE idpersona = $this->intIdUsuario and rolid = 7";
            //dep($sql);die();
            $request = $this->select($sql);
            return $request;
        }

        public function updateCliente(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, string $nit, string $nomFiscal, string $dirFiscal, string $strNumExt, string $strNumInt, string $strColonia, string $strCP, int $intlistEstado, int $intlistCiudad, int $intlistRegimen, int $intlistCFDI){
            $this->intIdUsuario = $idUsuario;
            $this->strIdentificacion = $identificacion;
            $this->strNombre = $nombre;
            $this->strApellido = $apellido;
            $this->intTelefono = $telefono;
            $this->strEmail = $email;
            $this->strPassword = $password;
            $this->strNit = $nit;
            $this->strNomFiscal = $nomFiscal;
            $this->strDirFiscal = $dirFiscal;
            $this->strNumExt = $strNumExt;
            $this->strNumInt = $strNumInt;
            $this->strColonia = $strColonia;
            $this->strCP = $strCP;
            $this->intlistEstado = $intlistEstado;
            $this->intlistCiudad = $intlistCiudad;
            $this->intlistRegimen = $intlistRegimen;
            $this->intlistCFDI = $intlistCFDI;

            $sql = "SELECT * FROM persona 
                    WHERE (email_user = '{$this->strEmail}' AND idpersona != $this->intIdUsuario)
                       /*OR (identificacion = '{$this->strIdentificacion}' AND idpersona != $this->intIdUsuario) */ ";
            $request = $this->select_all($sql);  
            
            if(empty($request)){
                if($this->strPassword != ""){
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, nit=?, nombrefiscal=?, direccionfiscal=?, numext=?, numint=?, colonia=?, cp=?, estado=?, municipio=?, regfiscal=?, usocfdi=? WHERE idpersona = $this->intIdUsuario";
                    $arrData = array($this->strIdentificacion, 
                                    $this->strNombre,
                                    $this->strApellido,
                                    $this->intTelefono,
                                    $this->strEmail,
                                    $this->strPassword,
                                    $this->strNit,
                                    $this->strNomFiscal,
                                    $this->strDirFiscal,
                                    $this->strNumExt,
                                    $this->strNumInt,
                                    $this->strColonia,
                                    $this->strCP,
                                    $this->intlistEstado,
                                    $this->intlistCiudad,
                                    $this->intlistRegimen,
                                    $this->intlistCFDI);
                }else {
                    $sql = "UPDATE persona SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, nit=?, nombrefiscal=?, direccionfiscal=? , numext=?, numint=?, colonia=?, cp=?, estado=?, municipio=?, regfiscal=?, usocfdi=? WHERE idpersona = $this->intIdUsuario";
                    $arrData = array($this->strIdentificacion, 
                                    $this->strNombre,
                                    $this->strApellido,
                                    $this->intTelefono,
                                    $this->strEmail,
                                    $this->strNit,
                                    $this->strNomFiscal,
                                    $this->strDirFiscal,
                                    $this->strNumExt,
                                    $this->strNumInt,
                                    $this->strColonia,
                                    $this->strCP,
                                    $this->intlistEstado,
                                    $this->intlistCiudad,
                                    $this->intlistRegimen,
                                    $this->intlistCFDI);
                }
                $request = $this->update($sql,$arrData);
            }else{
                $request = "exist";
            }
            return $request;
        }

        public function deleteCliente(int $intIdpersona){
            $this->intIdUsuario = $intIdpersona;
            $sql = "UPDATE persona set status = ? WHERE idpersona = $this->intIdUsuario ";
            $arrData = array(0);
            $request = $this->update($sql, $arrData);
            return $request;
        }

        public function selectRegimen(){
            $sql = "SELECT id, clave_reg, descripcion_reg
                    FROM rfiscal 
                    order by id ASC";
            $request = $this->select_all($sql);
            return $request;
        }

        public function selectCFDI($regimen){
            $sql = "SELECT id, clave_uso, descripcion_uso
                    FROM cfdi 
                    order by id ASC";
                    
                    if($regimen>0){
                        $sql = "SELECT cf.id, cf.clave_uso, cf.descripcion_uso 
                        from regfdi 
                        inner join cfdi cf ON cf.id = regfdi.cfdi 
                        inner join rfiscal reg ON reg.id = regfdi.regimen 
                        where regfdi.regimen=".$regimen;
                    }else{
                        $sql = "SELECT id, clave_uso, descripcion_uso
                        FROM cfdi 
                        order by id ASC";
                    }
                    

            //dep($sql);die();
            $request = $this->select_all($sql);
            return $request;
        }

        public function selectEstado(){
            $sql = "SELECT id, nombre
                    FROM estados 
                    order by id ASC";
            $request = $this->select_all($sql);
            return $request;
        }

        public function selectCiudad($estado){
                    if($estado>0){
                        $sql = "SELECT id, nombre, edo FROM `municipios` WHERE edo=".$estado;
                    }else{
                        $sql = "SELECT id, nombre, edo
                        FROM `municipios`
                        order by id ASC";
                    }
                    

            //dep($sql);die();
            $request = $this->select_all($sql);
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