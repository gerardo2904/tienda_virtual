<?php
     class Ingresos extends Controllers{
        public function __construct(){
            session_start();
            //sessionStart();
            parent::__construct();
            
            //session_regenerate_id(true);
            if(empty($_SESSION['login'])){
                header('Location: '.base_url().'login');
            }
            // Es el modulo 11 en tabla modulo (Ingresos).
            getPermisos(11);
        }

        public function Ingresos(){
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location: ".base_url().'dashboard');
            }

			$data['nempresa'] = $this->model->empresa();

            $data['page_tag'] = "Ingresos";
            $data['page_title'] = " Ordenes de Ingreso - ". $data['nempresa'];
            $data['page_name'] = "ingresos";
            $data['page_functions_js'] = "functions_ingresos.js";
            $this->views->getView($this,"ingresos",$data);
        }

        public function getIngresos()
		{	
            $estado = 1;
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectIngresos();
				for ($i=0; $i < count($arrData); $i++) {
					$btnView = '';
					$btnEdit = '';
					$btnDelete = '';
					if($arrData[$i]['status'] == 1)
					{
                        $estado = 1;
						$arrData[$i]['status'] = '<span class="badge badge-success">Activa</span>';
					}else{
                        $estado = 2;
						$arrData[$i]['status'] = '<span class="badge badge-info">Finalizada</span>';
					}

					$arrData[$i]['total'] = SMONEY.' '.formatMoney($arrData[$i]['total']);
                    $arrData[$i]['pimpuesto'] = SMONEY.' '.formatMoney($arrData[$i]['pimpuesto']);
                    $arrData[$i]['grantotal'] = SMONEY.' '.formatMoney($arrData[$i]['grantotal']);

					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idingreso'].')" title="Ver Orden de compra"><i class="far fa-eye"></i></button>';
					}
					
					if($_SESSION['permisosMod']['u']){
                        if ($estado == 1){
						    $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['idingreso'].')" title="Editar orden de compra"><i class="fas fa-pencil-alt"></i></button>';
                        }else{
                            $btnEdit= '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-pencil-alt"></i></button>';
                        }
					}
                    
					if($_SESSION['permisosMod']['d']){	
                        if ($estado == 1){
						    $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idingreso'].')" title="Cancelar orden de compra"><i class="far fa-trash-alt"></i></button>';
                        }else{
                            $btnDelete = '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-trash-alt"></i></button>';
                        }
					}


					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

        public function getDetalleIngresos(int $idingreso)
		{
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectDetalleIngresos($idingreso);
				for ($i=0; $i < count($arrData); $i++) {
					$btnDelete = '';
                    $btnDelete = '<button class="btn btn-danger" type="button" onClick="fntDelDetalleIngreso('.$arrData[$i]['iddetalle_ingreso'].')" title="Borrar producto"><i class="far fa-trash-alt"></i></button>';
					$arrData[$i]['options'] = '<div class="text-center">'.$btnDelete.'</div>';
                    $arrData[$i]['precioc'] = SMONEY.' '.formatMoney($arrData[$i]['precioc']);					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

        public function setDetalleIngreso(){
            //dep($_POST);die();
            if($_POST){
			    if(empty($_POST['listProductos']) || empty($_POST['intCantidad']) || empty($_POST['intPrecioc']) )
			    {
				    $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			    }else{
					
					$idIngreso = intval($_POST['idIngreso']);
                    $idProducto = intval($_POST['listProductos']);
                    $intCantidad = intval($_POST['intCantidad']);
					$intPrecioc = intval($_POST['intPrecioc']);
					//$strEtiqueta = strClean($_POST['txtEtiqueta']);
					
					$request_detalle_ingreso = "";

					//$ruta = strtolower(clear_cadena($strNombre));
					//$ruta = str_replace(" ","-",$ruta);
					if($idIngreso == 0)
					{
						$option = 1;
						if($_SESSION['permisosMod']['w']){
                            /*$ruta, poner antes de intStatus*/
							/*
                            $request_detalle_ingreso = $this->model->insertDetalleIngreso($idProveedor, 
																		$idPersona, 
																		$strComprobante, 
																		$strImpuesto,
																		$strNotas, 
																		$intStatus);
                            */
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['u']){
							/*$ruta,*/
							$request_detalle_ingreso = $this->model->updateDetalleIngreso($idIngreso,
                                                                        $idProducto,
																		$intCantidad,
																		$intPrecioc);
                                                                        
						}
					}
					if($request_detalle_ingreso > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'idingreso' => $idIngreso, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idingreso' => $request_detalle_ingreso, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_producto == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un ingreso con el comprobante Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function setIngreso(){
            //dep($_POST);die();
			if($_POST){
				if(empty($_POST['listProveedor']) || empty($_POST['txtComprobante']) || empty($_POST['txtImpuesto'])  || empty($_POST['listStatus']) || intval($_SESSION['userData']['idempresa']) == 0)
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					
					$idIngreso = intval($_POST['idIngreso']);
                    $idProveedor = intval($_POST['listProveedor']);
                    $idPersona = intval($_SESSION['idUser']);
					$strComprobante = strClean($_POST['txtComprobante']);
					$strImpuesto = strClean($_POST['txtImpuesto']);
					$strNotas = strClean($_POST['txtNotas']);
                    $intStatus = intval($_POST['listStatus']);
					$intEmpresa = intval($_SESSION['userData']['idempresa']);
					
					$request_ingreso = "";

					//$ruta = strtolower(clear_cadena($strNombre));
					//$ruta = str_replace(" ","-",$ruta);
					if($idIngreso == 0)
					{
						$option = 1;
						if($_SESSION['permisosMod']['w']){
                            /*$ruta, poner antes de intStatus*/
							$request_ingreso = $this->model->insertIngreso($idProveedor, 
																		$idPersona, 
																		$strComprobante, 
																		$strImpuesto,
																		$strNotas, 
																		$intStatus,
																	    $intEmpresa);
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['u']){
							/*$ruta,*/
							$request_ingreso = $this->model->updateIngreso($idIngreso,
                                                                        $idProveedor,
																		$idPersona,
																		$strComprobante, 
																		$strImpuesto, 
																		$strNotas, 
																		$intStatus,
																	    $intEmpresa);

							if ($intStatus == 2) {
								$arrDataDI = $this->model->selectProductos($idIngreso);
								for ($i=0; $i < count($arrDataDI); $i++) {
									$prodid=$arrDataDI[$i]['idproducto'];
									$prodcantidad=$arrDataDI[$i]['cantidad'];
									$precioc=$arrDataDI[$i]['precioc'];
									$codigo=$arrDataDI[$i]['codigo'];
									$nombre=$arrDataDI[$i]['nombre'];
									$marca=$arrDataDI[$i]['marca'];
									$intEmpresa = intval($_SESSION['userData']['idempresa']);
									$request_produc= $this->model->addProducto($prodid, $codigo, $precioc, $prodcantidad, $intEmpresa);
								}
								//dep($arrDataDI);die();
							}

						}
					}
					if($request_ingreso > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'idingreso' => $request_ingreso, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idingreso' => $idIngreso, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_producto == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un ingreso con el comprobante Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

        public function getIngreso($idingreso){
			if($_SESSION['permisosMod']['r']){
				$idingreso = intval($idingreso);
				if($idingreso > 0){
					$arrData = $this->model->selectIngreso($idingreso);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrProds = $this->model->selectProductos($idingreso);
						
						$arrData['productos'] = $arrProds;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
						$arrResponse['data']['total']=formatMoney($arrResponse['data']['total']);
						$arrResponse['data']['pimpuesto']=formatMoney($arrResponse['data']['pimpuesto']);
						$arrResponse['data']['grantotal']=formatMoney($arrResponse['data']['grantotal']);
                    //dep($arrResponse);
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}
        
        public function delDetalleIngreso($iddetalleingreso){
            if($_SESSION['permisosMod']['d']){
                $intIdDetalle = intval($iddetalleingreso);
                $requestDelete = $this->model->deleteDetalleIngreso($intIdDetalle);
                if($requestDelete)
                {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el producto');
                }else{
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el producto.');
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }

        public function delIngreso(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intIdingreso = intval($_POST['idIngreso']);
					$requestDelete = $this->model->deleteIngreso($intIdingreso);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha cancelado la orden de ingreso');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al cancelar la orden de ingreso.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

/*
        public function setImage(){
			if($_POST){
				if(empty($_POST['idproducto'])){
					$arrResponse = array('status' => false, 'msg' => 'Error de dato.');
				}else{
					$idProducto = intval($_POST['idproducto']);
					$foto      = $_FILES['foto'];
					$imgNombre = 'pro_'.md5(date('d-m-Y H:i:s')).'.jpg';
					$request_image = $this->model->insertImage($idProducto,$imgNombre);
					if($request_image){
						$uploadImage = uploadImage($foto,$imgNombre);
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
			if($_POST){
				if(empty($_POST['idproducto']) || empty($_POST['file'])){
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					//Eliminar de la DB
					$idProducto = intval($_POST['idproducto']);
					$imgNombre  = strClean($_POST['file']);
					$request_image = $this->model->deleteImage($idProducto,$imgNombre);

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
*/
		
     }
?>