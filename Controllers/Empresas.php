<?php
    class Empresas extends Controllers{
        public function __construct(){
            //sessionStart();
            parent::__construct();
            session_start();
            //session_regenerate_id(true);
            if(empty($_SESSION['login'])){
                header('Location: '.base_url().'login');
            }
            //en la base de datos 7 corresponde a Empresas en tabla modulos.
            getPermisos(7);
        }

        public function Empresas(){
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location: ".base_url().'dashboard');
            }
            $data['page_tag'] = "Empresas";
            $data['page_title'] = "Empresas ";
            $data['page_name'] = "empresas";
            $data['page_functions_js'] = "functions_empresas.js";
            $this->views->getView($this,"empresas",$data);
        }

        /*public function setProveedor(){
            dep($_POST);
            die();
        }*/
        public function setEmpresa(){
            if($_POST){
                //dep($_POST);
                //die();
                if(empty($_POST['txtNombreEmpresa']) || empty($_POST['txtRfcEmpresa']) || empty($_POST['txtDireccionEmpresa']) || empty($_POST['listCiudad']) || empty($_POST['txtCP']) || empty($_POST['txtEmailEmpresa']) || empty($_POST['txtTelEmpresa']))
                {
                    $arrResponse = array("status" => false, "msg" => 'Datos incorrectos,');
                }else {
                    // Como no lo estamos enviando este parametro, se va a alamacenar 0 (para Proveedores)
                    $idEmpresa = intval($_POST['idEmpresa']);
                    $strNombreEmpresa = strClean($_POST['txtNombreEmpresa']);
                    $strRfcEmpresa = ucwords(strClean($_POST['txtRfcEmpresa']));
                    $strDireccionEmpresa = strClean($_POST['txtDireccionEmpresa']);

                    $strNumExt = strClean($_POST['txtNumExt']);
                    $strNumInt = strClean($_POST['txtNumInt']);
                    $strColonia = strClean($_POST['txtColonia']);
                    $strCP = strClean($_POST['txtCP']);

                    $intlistEstado = strClean($_POST['listEstado']);
                    $intlistCiudad = strClean($_POST['listCiudad']);
                    $intlistRegimen = strClean($_POST['listRegimen']);

                    
                    $intTelEmpresa = intval(strClean($_POST['txtTelEmpresa']));
                    $intCelEmpresa = intval(strClean($_POST['txtCelEmpresa']));
                    $strEmailEmpresa = strClean($_POST['txtEmailEmpresa']);
                    $intStatus = intval($_POST['listStatus']);
                    
                    
                    // 10 es el rolid de proveedor de la tienda
                    //$intTipoId = 10;
                    $request_empresa = "";
                    
                    if($idEmpresa == 0){
                        $option = 1;
                        if($_SESSION['permisosMod']['w']){
                            $request_empresa = $this->model->insertEmpresa($strNombreEmpresa, 
                                                                        $strRfcEmpresa, 
                                                                        $strDireccionEmpresa, 
                                                                        $intTelEmpresa, 
                                                                        $strEmailEmpresa,
                                                                        $intStatus,
                                                                        $strNumExt,
                                                                        $strNumInt,
                                                                        $strColonia,
                                                                        $strCP,
                                                                        $intlistEstado,
                                                                        $intlistCiudad,
                                                                        $intlistRegimen,
                                                                        $intCelEmpresa);
                        }
                    }else {
                        $option = 2;
                        if($_SESSION['permisosMod']['u']){
                            $request_empresa = $this->model->updateEmpresa($idEmpresa, 
                                                                        $strNombreEmpresa, 
                                                                        $strRfcEmpresa,
                                                                        $strDireccionEmpresa, 
                                                                        $intTelEmpresa, 
                                                                        $strEmailEmpresa,
                                                                        $intStatus,
                                                                        $strNumExt,
                                                                        $strNumInt,
                                                                        $strColonia,
                                                                        $strCP,
                                                                        $intlistEstado,
                                                                        $intlistCiudad,
                                                                        $intlistRegimen,
                                                                        $intCelEmpresa);
                        }
                        
                    }
                    

                    if($request_empresa > 0){
                        if($option == 1){
                            $arrResponse = array('status' => true, 'idempresa' => $request_empresa, 'msg' => 'Datos guardados correctamente.');
                        }else {
                            $arrResponse = array('status' => true, 'idempresa' => $idEmpresa, 'msg' => 'Datos Actualizados correctamente.');
                        }
                        
                    }else if($request_empresa == 'exist'){
                        $arrResponse = array("status" => false, "msg" => 'Empresa ya existe, ingresar otra información.');
                    }else{
                        $arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
                    }
                    
                }
                //sleep(3);
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);

            }
            
            die();
        }

        public function getEmpresas(){
                $arrData = $this->model->selectEmpresas();

                for ($i=0;$i < count($arrData);$i++){
                    $btnView = '';
                    $btnEdit = '';
                    $btnDelete = '';

                    if($arrData[$i]['status'] == 1)
					{
						$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
					}else{
						$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
					}

                    if($arrData[$i]['img'] == ""){
						$arrData[$i]['img'] = '<img src="'.media().'/images/uploads/psinimagen.png" width="72" height="52"> ';
					}else{
						$arrData[$i]['img'] = '<img src="'.media().'/images/uploads/'.$arrData[$i]['img'].'" width="72" height="52">';
					}


                    if ($_SESSION['permisosMod']['r']){
                        $btnView='<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idempresa'].')" title="Ver empresa"><i class="fas fa-eye"></i></button>';
                    }

                    if ($_SESSION['permisosMod']['u']){    
                        $btnEdit= '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this, '.$arrData[$i]['idempresa'].')" title="Editar empresa"><i class="fas fa-pencil-alt"></i></button>';    
                    }

                    if ($_SESSION['permisosMod']['d']){   
                        $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idempresa'].')" title="Eliminar empresa"><i class="fas fa-trash-alt"></i></button>';
                    }
                    

                    $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
                    //$arrData[$i]['options']= 'XX';
                }

                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
                die();
            
        }

        public function getEmpresa($idempresa){
            if($_SESSION['permisosMod']['r']){
                $idempresa = intval($idempresa);
                if($idempresa > 0){
                    $arrData = $this->model->selectEmpresa($idempresa);
                    //dep($arrData);exit;
                    if(empty($arrData)){
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                    }else{
                        $arrImg = $this->model->selectImages($idempresa);
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
							}
						}
						$arrData['images'] = $arrImg;
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function setImage(){
			if($_POST){
				if(empty($_POST['idEmpresa'])){
					$arrResponse = array('status' => false, 'msg' => 'Error de dato.');
				}else{
					$idEmpresa = intval($_POST['idEmpresa']);
					$foto      = $_FILES['foto'];
					$imgNombre = 'emp_'.md5(date('d-m-Y H:i:s')).'.jpg';
					$request_image = $this->model->insertImage($idEmpresa,$imgNombre);
					if($request_image){
						$uploadImage = uploadImage($foto,$imgNombre);
                        $_SESSION['iEmp']=$imgNombre;
						$arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error de carga.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

        public function delFile(){
            //dep("datos: ".$_POST);die();

			if($_POST){
				if(empty($_POST['idempresa']) || empty($_POST['file'])){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					//Eliminar de la DB
					$idEmpresa = intval($_POST['idempresa']);
					$imgNombre  = strClean($_POST['file']);
					$request_image = $this->model->deleteImage($idEmpresa,$imgNombre);
                    //dep($request_image);die();
					if($request_image){
						$deleteFile =  deleteFile($imgNombre);
						$arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
					}
				}
                
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}



        public function delEmpresa(){
            if($_POST){
                if($_SESSION['permisosMod']['d']){
                    $intIdEmpresa = intval($_POST['idEmpresa']);
                    $requestDelete = $this->model->deleteEmpresa($intIdEmpresa);
                    if($requestDelete){
                        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la empresa.');
                    }else {
                        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar la empresa.');
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getSelectEmpresas(){
			$htmlOptions = "";
			$arrData = $this->model->selectEmpresas();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['status'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['idempresa'].'">'.$arrData[$i]['nombreempresa'].'</option>';
					}
				}
			}
            //dep($arrData);
			echo $htmlOptions;
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