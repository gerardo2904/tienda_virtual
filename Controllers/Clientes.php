<?php
    class Clientes extends Controllers{
        public function __construct(){
            //sessionStart();
            parent::__construct();
            session_start();
            //session_regenerate_id(true);
            if(empty($_SESSION['login'])){
                header('Location: '.base_url().'login');
            }
            //en la base de datos 3 corresponde a clientes en tabla modulos.
            getPermisos(3);
        }

        public function Clientes(){
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location: ".base_url().'dashboard');
            }

            $data['nempresa'] = $this->model->empresa();

            $data['page_tag'] = "Clientes";
            $data['page_title'] = "Clientes ";
            $data['page_name'] = "clientes";
            $data['page_functions_js'] = "functions_clientes.js";
            $this->views->getView($this,"clientes",$data);
        }

        /*public function setCliente(){
            dep($_POST);
            die();
        }*/
        public function setCliente(){
            if($_POST){
                //dep($_POST);
                //die();
                if(/*empty($_POST['txtIdentificacion']) || */ empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) /*|| empty($_POST['txtNit']) || empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal'])*/)
                {
                    $arrResponse = array("status" => false, "msg" => 'Datos incorrectos,');
                }else {
                    // Como no lo estamos enviando este parametro, se va a alamacenar 0 (para clientes)
                    $idUsuario = intval($_POST['idUsuario']);
                    $strIdentificacion = strClean($_POST['txtIdentificacion']);
                    $strNombre = ucwords(strClean($_POST['txtNombre']));
                    $strApellido = ucwords(strClean($_POST['txtApellido']));
                    $intTelefono = intval(strClean($_POST['txtTelefono']));
                    $strEmail = strtolower(strClean($_POST['txtEmail']));
                    $strNit = strClean($_POST['txtNit']);
                    $strNomFiscal = strClean($_POST['txtNombreFiscal']);
                    $strDirFiscal = strClean($_POST['txtDirFiscal']);
                    $strNumExt = strClean($_POST['txtNumExt']);
                    $strNumInt = strClean($_POST['txtNumInt']);
                    $strColonia = strClean($_POST['txtColonia']);
                    $strCP = strClean($_POST['txtCP']);

                    $intlistEstado = strClean($_POST['listEstado']);
                    $intlistCiudad = strClean($_POST['listCiudad']);
                    $intlistRegimen = strClean($_POST['listRegimen']);
                    $intlistCFDI = strClean($_POST['listCFDI']);

                    // 7 es el rolid de cliente de la tienda
                    $intTipoId = 7;
                    $request_user = "";
                    
                    if($idUsuario == 0){
                        $option = 1;
                        $strPassword = empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
                        $strPasswordEncript = hash("SHA256", $strPassword);
                        if($_SESSION['permisosMod']['w']){
                            $request_user = $this->model->insertCliente($strIdentificacion, 
                                                                        $strNombre, 
                                                                        $strApellido, 
                                                                        $intTelefono, 
                                                                        $strEmail, 
                                                                        $strPasswordEncript, 
                                                                        $intTipoId,
                                                                        $strNit, 
                                                                        $strNomFiscal, 
                                                                        $strDirFiscal,
                                                                        $strNumExt,
                                                                        $strNumInt,
                                                                        $strColonia,
                                                                        $strCP,
                                                                        $intlistEstado,
                                                                        $intlistCiudad,
                                                                        $intlistRegimen,
                                                                        $intlistCFDI);
                        }
                    }else {
                        $option = 2;
                        $strPassword = empty($_POST['txtPassword']) ? "": hash("SHA256", $_POST['txtPassword']);
                        if($_SESSION['permisosMod']['u']){
                            $request_user = $this->model->updateCliente($idUsuario, 
                                                                        $strIdentificacion, 
                                                                        $strNombre, 
                                                                        $strApellido, 
                                                                        $intTelefono, 
                                                                        $strEmail, 
                                                                        $strPassword, 
                                                                        $strNit, 
                                                                        $strNomFiscal, 
                                                                        $strDirFiscal,
                                                                        $strNumExt,
                                                                        $strNumInt,
                                                                        $strColonia,
                                                                        $strCP,
                                                                        $intlistEstado,
                                                                        $intlistCiudad,
                                                                        $intlistRegimen,
                                                                        $intlistCFDI);
                        }
                        
                    }
                    //dep($request_user);
                    if($request_user > 0){
                        if($option == 1){
                            $arrResponse = array("status" => true, "msg" => 'Datos guardados correctamente.',"cl" => $request_user);
                            $nombreUsuario = $strNombre.' '.$strApellido;
                            $dataUsuario = array('nombreUsuario' => $nombreUsuario,
                                                 'email' => $strEmail,
                                                 'password' => $strPassword,
                                                 'asunto' => 'Bienvenido a tu tienda en linea.');
                            sendEmail($dataUsuario,'email_bienvenida');

                        }else {
                            $arrResponse = array("status" => true, "msg" => 'Datos actualizados correctamente.');
                        }
                        
                    }else if($request_user == 'exist'){
                        $arrResponse = array("status" => false, "msg" => 'El email o la identificación ya existen, ingresar otro.');
                    }else{
                        $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                    }
                    
                }
                //sleep(3);
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);

            }
            
            die();
        }

        public function getClientes(){
                $arrData = $this->model->selectClientes();

                for ($i=0;$i < count($arrData);$i++){
                    $btnView = '';
                    $btnEdit = '';
                    $btnDelete = '';

                    if ($_SESSION['permisosMod']['r']){
                        $btnView='<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idpersona'].')" title="Ver cliente"><i class="fas fa-eye"></i></button>';
                    }

                    if ($_SESSION['permisosMod']['u']){    
                        $btnEdit= '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this, '.$arrData[$i]['idpersona'].')" title="Editar cliente"><i class="fas fa-pencil-alt"></i></button>';    
                    }

                    if ($_SESSION['permisosMod']['d']){   
                        $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idpersona'].')" title="Eliminar cliente"><i class="fas fa-trash-alt"></i></button>';
                    }
                    

                    $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
                    //$arrData[$i]['options']= 'XX';
                }

                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                die();
            
        }

        public function getCliente($idpersona){
            if($_SESSION['permisosMod']['r']){
                $idusuario = intval($idpersona);
                if($idusuario > 0){
                    $arrData = $this->model->selectCliente($idusuario);
                    //dep($arrData);exit;
                    if(empty($arrData)){
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    }else{
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function delCliente(){
            if($_POST){
                if($_SESSION['permisosMod']['d']){
                    $intIdpersona = intval($_POST['idUsuario']);
                    $requestDelete = $this->model->deleteCliente($intIdpersona);
                    if($requestDelete){
                        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el cliente.');
                    }else {
                        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el cliente.');
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getSelectClientes(){
			$htmlOptions = "";
			$arrData = $this->model->selectClientes();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['status'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['idpersona'].'">'.$arrData[$i]['nombres'].' '.$arrData[$i]['apellidos'].'</option>';
					}
				}
			}
            //dep($arrData);
			echo $htmlOptions;
			die();	
		}

        public function getMailAle(){
            //$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $permitted_chars_email = 'abcdefghijklmnopqrstuvwxyz';
            $hoy = date("dmY");
            $return = substr(str_shuffle($permitted_chars_email), 0, 10).$hoy."@".substr(str_shuffle($permitted_chars_email), 0, 5).".com";
            $arrResponse = array('status' => true, 'msg' => $return);
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }

        public function getSelectRegimen(){
            $htmlOptions = "";
            $arrData = $this->model->selectRegimen();
            if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					$htmlOptions .= '<option value="'.$arrData[$i]['id'].'">'.$arrData[$i]['clave_reg'].' '.$arrData[$i]['descripcion_reg'].'</option>';
				}
			}
            //dep($arrData);
			echo $htmlOptions;
			die();
        }

        
        public function getSelectCFDI($regimen){
            $htmlOptions = "";
            $arrData = $this->model->selectCFDI($regimen);
            if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					$htmlOptions .= '<option value="'.$arrData[$i]['id'].'">'.$arrData[$i]['clave_uso'].' '.$arrData[$i]['descripcion_uso'].'</option>';
				}
			}
            //dep($htmlOptions);exit;
            //exit;
			echo $htmlOptions;
			die();
        }

        public function getSelectEstado(){
            $htmlOptions = "";
            $arrData = $this->model->selectEstado();
            if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					$htmlOptions .= '<option value="'.$arrData[$i]['id'].'">'.$arrData[$i]['nombre'].'</option>';
				}
			}
            //dep($arrData);
			echo $htmlOptions;
			die();
        }

        public function getSelectCiudad($estado){
            $htmlOptions = "";
            $arrData = $this->model->selectCiudad($estado);
            if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					$htmlOptions .= '<option value="'.$arrData[$i]['id'].'">'.$arrData[$i]['nombre'].'</option>';
				}
			}
            //dep($arrData);
			echo $htmlOptions;
			die();
        }



    }
?>