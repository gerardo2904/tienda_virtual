<?php
     class Productos extends Controllers{
        public function __construct(){
            session_start();
            //sessionStart();
            parent::__construct();
            
            //session_regenerate_id(true);
            if(empty($_SESSION['login'])){
                header('Location: '.base_url().'login');
            }
            // Es el modulo 4 en tabla modulo (Productos).
            getPermisos(4);
        }

        public function Productos(){
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location: ".base_url().'dashboard');
            }
			$data['nempresa'] = $this->model->empresa();
			
            $data['page_tag'] = "Productos";
            $data['page_title'] = " Productos - ". $data['nempresa'];
            $data['page_name'] = "productos";
            $data['page_functions_js'] = "functions_productos.js";
            $this->views->getView($this,"productos",$data);
        }

        public function getProductos()
		{
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectProductos();
				for ($i=0; $i < count($arrData); $i++) {
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

					$arrData[$i]['precio'] = SMONEY.' '.formatMoney($arrData[$i]['precio']);
					$arrData[$i]['precio_compra'] = SMONEY.' '.formatMoney($arrData[$i]['precio_compra']);
					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idproducto'].')" title="Ver producto"><i class="far fa-eye"></i></button>';
					}
					if($_SESSION['permisosMod']['u']){
						$btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['idproducto'].')" title="Editar producto"><i class="fas fa-pencil-alt"></i></button>';
					}
					if($_SESSION['permisosMod']['d']){	
						$btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idproducto'].')" title="Eliminar producto"><i class="far fa-trash-alt"></i></button>';
					}
					$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>';
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

        public function setProducto(){
			//dep($_SESSION['userData']);die();
			if($_POST){
				if(empty($_POST['txtNombre']) || empty($_POST['txtCodigo']) || empty($_POST['listCategoria']) || empty($_POST['txtPrecio']) || empty($_POST['listStatus']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					
					$idProducto = intval($_POST['idProducto']);
					$strNombre = strClean($_POST['txtNombre']);
					$strMarca = strClean($_POST['txtMarca']);
					$strDescripcion = strClean($_POST['txtDescripcion']);
					$strCodigo = strClean($_POST['txtCodigo']);
					$intCategoriaId = intval($_POST['listCategoria']);
					$strPrecio = strClean($_POST['txtPrecio']);
					$strPrecio_compra = strClean($_POST['txtPrecio_compra']);
					$intStock = intval($_POST['txtStock']);
					$intStatus = intval($_POST['listStatus']);
					$intEmpresa = intval($_SESSION['userData']['idempresa']);
					$idProveedor = intval($_POST['listProveedor']);
					$request_producto = "";

					//$ruta = strtolower(clear_cadena($strNombre));
					//$ruta = str_replace(" ","-",$ruta);
                    //dep($intEmpresa);die();
					if($idProducto == 0)
					{
						$option = 1;
						if($_SESSION['permisosMod']['w']){
                            /*$ruta, poner antes de intStatus*/
							$request_producto = $this->model->insertProducto($strNombre, 
																		$strMarca,
																		$strDescripcion, 
																		$strCodigo, 
																		$intCategoriaId,
																		$strPrecio, 
																		$strPrecio_compra,
																		$intStock, 
																		$intStatus,
																		$intEmpresa,
																		$idProveedor );
						}
					}else{
						$option = 2;
						if($_SESSION['permisosMod']['u']){
							/*$ruta,*/
							$request_producto = $this->model->updateProducto($idProducto,
																		$strNombre,
																		$strMarca,
																		$strDescripcion, 
																		$strCodigo, 
																		$intCategoriaId,
																		$strPrecio, 
																		$strPrecio_compra,
																		$intStock, 
																		$intStatus,
																		$intEmpresa,
																		$idProveedor);
						}
					}
					if($request_producto > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'idproducto' => $request_producto, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idproducto' => $idProducto, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_producto == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe un producto con el Código Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

        public function getProducto($idproducto){
			if($_SESSION['permisosMod']['r']){
				$idproducto = intval($idproducto);
				if($idproducto > 0){
					$arrData = $this->model->selectProducto($idproducto);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrImg = $this->model->selectImages($idproducto);
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
							}
						}
						$arrData['images'] = $arrImg;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function getProductoV($idproducto){
			if($_SESSION['permisosMod']['r']){
				$idproducto = intval($idproducto);
				if($idproducto > 0){
					$arrData = $this->model->selectProductoV($idproducto);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}
		


		public function getSelectProductos(){
			$htmlOptions = "";
			$arrData = $this->model->selectProductos();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['status'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['idproducto'].'">'.$arrData[$i]['marca'].' '.$arrData[$i]['nombre'].'</option>';
					}
				}
			}
			echo $htmlOptions;
			die();	
		}

		public function getSelectProductosV(){
			$htmlOptions = "";
			$arrData = $this->model->selectProductosV();
			if(count($arrData) > 0 ){
				for ($i=0; $i < count($arrData); $i++) { 
					if($arrData[$i]['status'] == 1 ){
					$htmlOptions .= '<option value="'.$arrData[$i]['idproducto'].'">'.$arrData[$i]['codigo'].' | '.$arrData[$i]['marca'].' '.$arrData[$i]['nombre'].' </option>';
					}
				}
			}
			echo $htmlOptions;
			die();	
		}


		public function getMaxCodigoProducto(){
			//Obtiene el siguiente codigo de barras a usar para productos nuevos.
			$CodigoMax = 0;
			$arrData = $this->model->getCodigoMax();
			$CodigoMax = $arrData[codigo]+1;
			if($CodigoMax >0){
				$arrResponse = array('status' => true, 'codigo' => $CodigoMax, 'msg' => 'Codigo siguiente');
			}else {
				$arrResponse = array('status' => false, 'msg' => 'Error con codigo');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			//return $CodigoMax;
			die();
		}


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

		public function delProducto(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intIdproducto = intval($_POST['idProducto']);
					$requestDelete = $this->model->deleteProducto($intIdproducto);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el producto');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el producto.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function getExcel()
		{
			// codigo  -  marca+nombre  descripcion  -  precio
			$salida  = "";
			$salida .= "<table>";
			$salida .= "<thead><th>Codigo</th><th>Descripcion</th><th>precio</th>";

			
			$arrData = $this->model->selectProdExcel();
			for ($i=0; $i < count($arrData); $i++) {
				$salida .= "<tr><td>".$arrData[$i]['codigo']."</td><td>".$arrData[$i]['nombre']."</td><td>".SMONEY.' '.formatMoney($arrData[$i]['precio'])."</td></tr>";
			}
			$salida .= "</table>";

			header("Content-type: application/vnd.ms-excel; charset=utf-8");
			//header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
			header("Content-Disposition: attachment; filename=prods.xls");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);

			echo $salida;

			//dep($salida);exit;

			//echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			
			die();
		}




     }
?>