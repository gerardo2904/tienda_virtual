<?php
    class Categorias extends Controllers{
        public function __construct(){
            session_start();
            //sessionStart();
            parent::__construct();
            
            //session_regenerate_id(true);
            if(empty($_SESSION['login'])){
                header('Location: '.base_url().'login');
            }
            // Es el modulo 6 en tabla modulo (Categorias).
            getPermisos(6);
        }

        public function Categorias(){
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location: ".base_url().'dashboard');
            }
            $data['page_tag'] = "Categorías";
            $data['page_title'] = " Categorías ";
            $data['page_name'] = "categorias";
            $data['page_functions_js'] = "functions_categorias.js";
            $this->views->getView($this,"categorias",$data);
        }

        public function setCategoria(){
            //dep($_POST);
            //dep($_FILES);
            //exit;
            if($_POST){
                if(empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || empty($_POST['listStatus'])){
                    $arrResponse = array('status' => false, 'msg' => 'Datos incorrectos.');
                }else{
                    if($_SESSION['permisosMod']['w']){
                        $intIdcategoria = intval($_POST['idCategoria']);
                        $strCategoria = strClean($_POST['txtNombre']);
                        $strDescripcion = strClean($_POST['txtDescripcion']);
                        $intStatus = intval($_POST['listStatus']);
        
                        $foto = $_FILES['foto'];
                        $nombre_foto = $foto['name'];
                        $type = $foto['type'];
                        $url_temp = $foto['tmp_name'];
                        //$fecha = date('ymd');
                        //$hora = date('Hms');
                        $imgPortada = 'portada_categoria.png';
                        $request_categoria = "";
        
                        if($nombre_foto != ''){
                            $imgPortada = 'img_'.md5(date('d-m-Y H:m:s')).'.jpg';
                        }
        
                        if($intIdcategoria ==0){
                            //Crear categoria
                            if($_SESSION['permisosMod']['w']){
                                $request_categoria = $this->model->insertCategoria($strCategoria,$strDescripcion,$imgPortada, $intStatus);
                                $option = 1;
                            }
                        }else{
                            // Actualizar categoria
                            if($_SESSION['permisosMod']['u']){
                                if($nombre_foto == ''){
                                    if($_POST['foto_actual']!= 'portada_categoria.jpg' && $_POST['foto_remove'] == 0){
                                        $imgPortada = $_POST['foto_actual'];
                                    }
                                }

                                $request_categoria = $this->model->updateCategoria($intIdcategoria, $strCategoria,$strDescripcion, $imgPortada, $intStatus);
                                $option = 2;
                            }
                        }
        
                        
                        if($request_categoria > 0){
                            if($option == 1){
                                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
                                if($nombre_foto != '') { uploadImage($foto, $imgPortada); }
                            }else{
                                $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente');
                                if($nombre_foto != '') { uploadImage($foto, $imgPortada); }

                                if(($nombre_foto == '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] != 'portada_categoria.png')
                                  || ($nombre_foto != '' && $_POST['foto_actual'] != 'portada_categoria.png')){
                                    deleteFile($_POST['foto_actual']);
                                  }
                            }
                            
                        }else if($request_categoria == 'exist'){
                            $arrResponse = array('status' => false, 'msg' => '¡Atención! La categoria ya existe.');
                        }else {
                            $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.');
                        }

                    }
                }

            
                //sleep(3);
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function getCategorias(){
            if($_SESSION['permisosMod']['r']){
                $arrData = $this->model->selectCategorias();
                //dep($arrData);exit;

                for ($i=0;$i < count($arrData);$i++){
                    $btnView = '';
                    $btnEdit = '';
                    $btnDelete = '';

                    if($arrData[$i]['status'] == 1){
                        $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
                    }else{
                        $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                    }

                    if($arrData[$i]['portada'] == ""){
						$arrData[$i]['portada'] = '<img src="'.media().'/images/uploads/psinimagen.png" width="72" height="52"> ';
					}else{
						$arrData[$i]['portada'] = '<img src="'.media().'/images/uploads/'.$arrData[$i]['portada'].'" width="72" height="52">';
					}


                    if ($_SESSION['permisosMod']['r']){
                        $btnView='<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idcategoria'].')" title="Ver categoria"><i class="fas fa-eye"></i></button>';
                    }

                    if ($_SESSION['permisosMod']['u']){    
                        $btnEdit= '<button class="btn btn-primary btn-sm" onClick="fntEditInfo(this, '.$arrData[$i]['idcategoria'].')" title="Editar categoria"><i class="fas fa-pencil-alt"></i></button>';    
                    }

                    if ($_SESSION['permisosMod']['d']){   
                        $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idcategoria'].')" title="Eliminar categoria"><i class="fas fa-trash-alt"></i></button>';
                    }
                    

                    $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
                    //$arrData[$i]['options']= 'XX';
                }

                echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
            }
            die();
        
        }

        public function getCategoria(int $idcategoria){
            if($_SESSION['permisosMod']['r']){
                $intIdcategoria = intval($idcategoria);
                if($intIdcategoria > 0){
                    $arrData = $this->model->selectCategoria($intIdcategoria);
                    //dep($arrData);exit;
                    if(empty($arrData)){
                        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
                    }else {
                        $arrData['url_portada'] = media().'/images/uploads/'.$arrData['portada'];
                        $arrResponse = array('status' => true, 'data' => $arrData);
                    }
                    //dep($arrData);exit;
                    echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function delCategoria(){
            if($_POST){
                //dep($_POST);exit;
                if($_SESSION['permisosMod']['d']){
                    $intIdcategoria = intval($_POST['idCategoria']);
                    $requestDelete = $this->model->deleteCategoria($intIdcategoria);
                    if($requestDelete == 'ok'){
                        $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado la Categoría');
                    }else if($requestDelete == 'exist'){
                        $arrResponse = array('status' => false, 'msg' => 'No es posible eliminar una Categoría asociada con productos asociados.');
                    }else{
                        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar Categoría');
                    }
                    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
                }
            }
            die();
        }

        public function getSelectCategorias(){
			$htmlOptions = "";
			$arrData = $this->model->selectCategorias();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['status'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['idcategoria'].'">'.$arrData[$i]['nombre'].'</option>';
					}
				}
			}
			echo $htmlOptions;
			die();	
		}
        

    }

?>
