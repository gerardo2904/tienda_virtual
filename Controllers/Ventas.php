<?php
     class Ventas extends Controllers{
        public function __construct(){
            session_start();
            //sessionStart();
            parent::__construct();
            
            //session_regenerate_id(true);
            if(empty($_SESSION['login'])){
                header('Location: '.base_url().'login');
            }
            // Es el modulo 12 en tabla modulo (Ventas).
            getPermisos(12);
        }

        public function Ventas(){
            if(empty($_SESSION['permisosMod']['r'])){
                header("Location: ".base_url().'dashboard');
            }

			$data['nempresa'] = $this->model->empresa();
			
            $data['page_tag'] = "Ventas";
            $data['page_title'] = " Ordenes de Salida - ". $data['nempresa'];
            $data['page_name'] = "ventas";
            $data['page_functions_js'] = "functions_ventas.js";
            $this->views->getView($this,"ventas",$data);
        }

        public function getVentas()
		{	
            $estado = 1;
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectVentas();
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
					$arrData[$i]['sdescuento'] = SMONEY.' '.formatMoney($arrData[$i]['sdescuento']);

					if($_SESSION['permisosMod']['r']){
						$btnView = '<button class="btn btn-info btn-sm" onClick="fntViewInfo('.$arrData[$i]['idventa'].')" title="Ver Orden de salida"><i class="far fa-eye"></i></button>';
					}
					
					if($_SESSION['permisosMod']['u']){
                        if ($estado == 1){
						    $btnEdit = '<button class="btn btn-primary  btn-sm" onClick="fntEditInfo(this,'.$arrData[$i]['idventa'].')" title="Editar orden de salida"><i class="fas fa-pencil-alt"></i></button>';
                        }else{
                            $btnEdit= '<button class="btn btn-secondary btn-sm" disabled><i class="fas fa-pencil-alt"></i></button>';
                        }
					}
                    
					if($_SESSION['permisosMod']['d']){	
                        if ($estado == 1){
						    $btnDelete = '<button class="btn btn-danger btn-sm" onClick="fntDelInfo('.$arrData[$i]['idventa'].')" title="Cancelar orden de salida"><i class="far fa-trash-alt"></i></button>';
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

        public function getDetalleVentas(int $idventa)
		{
			if($_SESSION['permisosMod']['r']){
				$arrData = $this->model->selectDetalleVentas($idventa);
				for ($i=0; $i < count($arrData); $i++) {
					$btnDelete = '';
                    $btnDelete = '<button class="btn btn-danger" type="button" onClick="fntDelDetalleVenta('.$arrData[$i]['iddetalle_venta'].')" title="Borrar producto"><i class="far fa-trash-alt"></i></button>';
					$arrData[$i]['options'] = '<div class="text-center">'.$btnDelete.'</div>';
                    $arrData[$i]['precio'] = SMONEY.' '.formatMoney($arrData[$i]['precio']);					
				}
				echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function setPct($iddet,$pct){
			if($_SESSION['permisosMod']['r']){
				$iddet = intval($iddet);
				if($iddet > 0){
				
				}
			}
		}

		public function actualizaporcen(){
			$x=$_POST[iddet];
			$y=$_POST[porc];
			/*dep($x);
			
			if ($x>170){echo "si";}else{echo "no";}
			
			die();
*/
			if($_SESSION['permisosMod']['w']){
                $intIdDetalle = intval($iddetalleventa);
				$porcenta=intval($porcen);
                $requestUpdate = $this->model->actualizaDetalleVenta($x,$y);
				
                if($requestUpdate)
                {
                    $arrResponse = array('status' => true, 'msg' => 'OK');
                }else{
                    $arrResponse = array('status' => false, 'msg' => 'Error');
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
		}

        public function setDetalleVenta(){
            //dep($_POST);die();
            if($_POST){
			    if(empty($_POST['listProductos']) || empty($_POST['intCantidad']) || empty($_POST['intPrecio']))
			    {
				    $arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			    }else{
					
					$idVenta = intval($_POST['idVenta']);
                    $idProducto = intval($_POST['listProductos']);
                    $intCantidad = strClean($_POST['intCantidad']);
					$intPrecio = strClean($_POST['intPrecio']);
					$intEmpresa = intval($_SESSION['userData']['idempresa']);
					$intDescuento = strClean($_POST['intDesc']);
					//$strEtiqueta = strClean($_POST['txtEtiqueta']);
					
					$request_detalle_venta = "";

					//$ruta = strtolower(clear_cadena($strNombre));
					//$ruta = str_replace(" ","-",$ruta);
					if($idVenta == 0)
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
							$request_detalle_venta = $this->model->updateDetalleVenta($idVenta,
                                                                        $idProducto,
																		$intCantidad,
																		$intPrecio,
																	    $intDescuento);
                                                                        
						}
					}
					if($request_detalle_venta > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'idventa' => $idVenta, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idventa' => $request_detalle_venta, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_producto == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe una salida con el comprobante Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function setVenta(){
            //dep($_POST);die();

			if($_POST){
				if(empty($_POST['listCliente']) || empty($_POST['txtComprobante']) || empty($_POST['txtImpuesto'])  || empty($_POST['listStatus']) || intval($_SESSION['userData']['idempresa']) == 0)
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{
					
					$idVenta = intval($_POST['idVenta']);
                    $idCliente = intval($_POST['listCliente']);
                    $idPersona = intval($_SESSION['idUser']);
					$strComprobante = strClean($_POST['txtComprobante']);
					$strImpuesto = strClean($_POST['txtImpuesto']);
					$strNotas = strClean($_POST['txtNotas']);
                    $intStatus = intval($_POST['listStatus']);
					$intEmpresa = intval($_SESSION['userData']['idempresa']);
					
					$request_venta = "";

					//$ruta = strtolower(clear_cadena($strNombre));
					//$ruta = str_replace(" ","-",$ruta);
					if($idVenta == 0)
					{
						$option = 1;
						if($_SESSION['permisosMod']['w']){
                            /*$ruta, poner antes de intStatus*/
							$request_venta = $this->model->insertVenta($idCliente, 
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
							$request_venta = $this->model->updateVenta($idVenta,
                                                                        $idCliente,
																		$idPersona,
																		$strComprobante, 
																		$strImpuesto, 
																		$strNotas, 
																		$intStatus,
																	    $intEmpresa);

						/*	if ($intStatus == 2) { //Revisar porque es resta, no suma...
								$arrDataDI = $this->model->selectProductos($idVenta);
								for ($i=0; $i < count($arrDataDI); $i++) {
									$prodid=$arrDataDI[$i]['idproducto'];
									$prodcantidad=$arrDataDI[$i]['cantidad'];
									$precio=$arrDataDI[$i]['precio'];
									$codigo=$arrDataDI[$i]['codigo'];
									$nombre=$arrDataDI[$i]['nombre'];
									$marca=$arrDataDI[$i]['marca'];
									$intEmpresa = intval($_SESSION['userData']['idempresa']);
									$request_produc= $this->model->addProducto($prodid, $codigo, $precio, $prodcantidad, $intEmpresa);
								}
								//dep($arrDataDI);die();
							}*/

						}
					}
					if($request_venta > 0 )
					{
						if($option == 1){
							$arrResponse = array('status' => true, 'idventa' => $request_venta, 'msg' => 'Datos guardados correctamente.');
						}else{
							$arrResponse = array('status' => true, 'idventa' => $idVenta, 'msg' => 'Datos Actualizados correctamente.');
						}
					}else if($request_producto == 'exist'){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! ya existe una salida con el comprobante Ingresado.');		
					}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function ticket($idventa){
			if($_SESSION['permisosMod']['r']){
				$idventa = intval($idventa);
				if($idventa > 0){
					$arrData = $this->model->selectVenta($idventa);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrProds = $this->model->selectProductos($idventa);
						$arrData['productos'] = $arrProds;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					//dep($arrData);die();
					$subtotal=0;
					$impuesto=0;
					$total=0;
					$pdesc=0;

					# Incluyendo librerias necesarias #
					//require_once("Libraries/Core/pdf_js.php");
					require_once("Libraries/Core/code128.php");

					


					
					$pdf = new PDF_Code128('P','mm',array(58,190));
					
					//$pdf = new PDF_Code128('P','mm','Letter');
					//$pdf = new PDF_JavaScript('P','mm',array(58,190));
					//dep($pdf);die();
					$pdf->SetMargins(4,10,4);
					$pdf->AddPage();
					//$imagen=media().'/images/uploads/'.$_SESSION['iEmp'];
					//$imagen=media();
					//$imagen='https://mantoplustj.com/tienda_virtual/Assets/images/uploads/';
					$imagen='Assets/images/uploads/';
					$imagen.=$_SESSION['iEmp'];

					//dep($imagen);die();
					//$imagen.="oftalmo1.jpg";
					//${base_url}Assets/images/uploads/

					$image_format = strtolower(pathinfo($imagen, PATHINFO_EXTENSION));
					//dep($imagen);
					//dep($image_format);
					//die();
					//$pdf->Image("'".$imagen."'", 10, 10, 30,30,"'".$image_format."'");
					$pdf->Image($imagen, 20, 2, 20,20);
					$pdf->Ln(15);
					
					//$im = imagecreatefrompng("'".$imagen."'");

					//header('Content-Type: image/png');

					//imagepng($im);
					
					# Encabezado y datos de la empresa #
					$pdf->SetFont('Arial','B',10);
					$pdf->SetTextColor(0,0,0);
					$pdf->MultiCell(0,5,utf8_decode(strtoupper($arrData['nombreempresa'])),0,'C',false);
					$pdf->SetFont('Arial','B',9);
					$pdf->MultiCell(0,5,utf8_decode($arrData['rfcempresa']),0,'C',false);
					$nint="";
					if(strlen($arrData['numint']) > 0){
						$nint=", Int. ".$arrData['numint'];
					}
					$pdf->MultiCell(0,5,utf8_decode($arrData['direccionempresa'].', No. '.$arrData['numext'].$nint.", ".$arrData['colonia'].". CP ".$arrData['cpempresa']),0,'C',false);
					$pdf->MultiCell(0,5,utf8_decode($arrData['nciudad'].", ".$arrData['nestado']),0,'C',false);
					$pdf->MultiCell(0,5,utf8_decode("Teléfono: ".$arrData['telempresa']),0,'C',false);
					$pdf->SetFont('Arial','BU',8);
					$pdf->MultiCell(0,5,utf8_decode("Email: ".$arrData['emailempresa']),0,'C',false);

					$pdf->SetFont('Arial','B',6);
					
					//$pdf->SetLeftMargin(20);
					$pdf->SetLeftMargin(0);
					$pdf->Ln(5);
					$pdf->SetFont('Arial','B',11);
					$pdf->Cell(32,3,"Producto",0,0,L);
					//$pdf->SetFont('Arial','B',9);
					/*
					$pdf->SetLeftMargin(17);
					$pdf->Cell(8,3,"Cant.",B,0,'R');
					$pdf->SetLeftMargin(37);
					$pdf->Cell(11,3,"Precio",B,0,'R');
					$pdf->SetLeftMargin(48);
					$pdf->Cell(11,3,"Total",B,0,'R');
					$pdf->SetFont('Arial','B',9);
					*/
					$pdf->Ln(3);
					$pdf->SetLeftMargin(4);

					for ($i=0; $i < count($arrProds); $i++) {
						if($i>0){
							$pdf->SetLeftMargin(0);
							$pdf->Ln(5);
							$pdf->SetFont('Arial','B',11);
							$pdf->Cell(32,3,"Producto",0,0,L);
							$pdf->Ln(3);
							$pdf->SetLeftMargin(4);
						}
						$pdf->SetLeftMargin(1);
						$pdf->Ln(2);
						$pdf->SetFont('Arial','',10);
						$pdf->Cell(22,2,$arrProds[$i]['nombre'],0,0,'L');

						$pdf->SetLeftMargin(1);
						$pdf->SetFont('Arial','B',10);
						
						$pdf->Ln(5);
						$pdf->Cell(18,3,"Cantidad",0,0,'C');
						$pdf->SetLeftMargin(20);
						$pdf->Cell(11,3,"Precio",0,0,'C');
						$pdf->SetLeftMargin(40);
						$pdf->Cell(11,3,"Total",0,0,'C');
						
						$pdf->SetFont('Arial','B',9);
						$pdf->SetLeftMargin(2);
						$pdf->Ln(5);
						$pdf->Cell(18,2,$arrProds[$i]['cantidad'],0,0,'C');
						$pdf->SetLeftMargin(20);
						$pdf->Cell(11,2,number_format($arrProds[$i]['precio'],2),0,0,'C');
						$pdf->SetLeftMargin(40);
						$subtotal=$subtotal+($arrProds[$i]['precio']*$arrProds[$i]['cantidad']);
						$pdf->Cell(11,2,number_format(($arrProds[$i]['precio']*$arrProds[$i]['cantidad']),2),0,0,'R');
						$pdf->Ln(2);
						$pdf->SetLeftMargin(1);
						if($arrProds[$i]['descuento']<>0){
							if($arrProds[$i]['descuento']>1){
								$pdesc=$arrProds[$i]['descuento']/100;
							}else{
								$pdesc=$arrProds[$i]['descuento'];
							}
							$descuento=(($arrProds[$i]['precio']*$arrProds[$i]['cantidad'])*$pdesc);
							$subtotal=$subtotal-$descuento;
							$pdf->SetFont('Arial','B',10);
							$pdf->Ln(2);
							$pdf->SetLeftMargin(2);
							$pdf->Cell(33,2,"Descuento.............",0,0,'L');
							$pdf->SetFont('Arial','B',9);
							$pdf->SetLeftMargin(40);
							$pdf->Cell(11,2,number_format((($arrProds[$i]['precio']*$arrProds[$i]['cantidad'])*$pdesc*-1),2),0,0,'R');
							$pdf->SetFont('Arial','B',10);
							$pdf->SetLeftMargin(2);
							$pdf->Ln(5);
							$pdf->Cell(33,2,"Total......................",0,0,'L');
							$pdf->SetFont('Arial','B',9);
							$pdf->SetLeftMargin(40);
							$pdf->Cell(11,2,number_format((($arrProds[$i]['precio']*$arrProds[$i]['cantidad']))-(($arrProds[$i]['precio']*$arrProds[$i]['cantidad'])*$pdesc),2),0,0,'R');
							$pdf->Ln(3);
						}
						$pdf->SetLeftMargin(4);
					}

/*					$pdf->SetLeftMargin(4);
					$pdf->Ln(3);
					$pdf->Cell(53,2,"Subtotal..........................................................................",0,0,'L');
					$pdf->SetLeftMargin(58);
					$pdf->Cell(11,2,number_format($subtotal,2),0,0,'R');
					$pdf->Ln(2);
*/
					$impuesto=$arrData['impuesto']*$subtotal;
					$total=$subtotal+$impuesto;
/*
					$pdf->SetLeftMargin(4);
					$pdf->Ln(2);
					$pdf->Cell(53,2,"Impuesto.........................................................................",0,0,'L');
					$pdf->SetLeftMargin(58);
					$pdf->Cell(11,2,number_format($impuesto,2),0,0,'R');
					$pdf->Ln(2);
*/					$pdf->SetFont('Arial','B',10);
					$pdf->SetLeftMargin(1);
					$pdf->Ln(5);
					$pdf->Cell(33,2,"Total de la venta...",0,0,'L');
					$pdf->SetFont('Arial','B',9);
					$pdf->SetLeftMargin(40);
					$pdf->Cell(11,2,number_format($total,2),0,0,'R');
					$pdf->Ln(2);

					//$pdf->Output("F","Ticket_Nro_1.pdf",true);
					//$pdf->AutoPrint();
					$pdf->SetLeftMargin(2);
					$pdf->Ln(8);
					
					$pdf->SetFont('Arial','B',9);
					$pdf->MultiCell(0,5,utf8_decode("Los precios estan expresados en moneda nacional de México (MXN)."),0,'C',false);

					$pdf->Output();die();
					//echo json_encode($pdf,JSON_UNESCAPED_UNICODE);

					//dep($pdf);die();
				}
			}
		}

		public function barras($idventa){
			if($_SESSION['permisosMod']['r']){
				$idventa = intval($idventa);
				if($idventa > 0){
					$arrData = $this->model->selectVenta($idventa);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrProds = $this->model->selectProductos($idventa);
						$arrData['productos'] = $arrProds;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
					//dep($arrData);die();
					
					# Incluyendo librerias necesarias #
					require_once("Libraries/Core/code128.php");
					
					$pdf = new PDF_Code128('P','mm',array(32,06));
					//dep($pdf);die();
					$pdf->SetMargins(1,5,4);
					//$pdf->AddPage();
					
					# Encabezado y datos de la empresa #
					$pdf->SetFont('Arial','B',5);
					$pdf->SetTextColor(0,0,0);
										

					for ($i=0; $i < count($arrProds); $i++) {
						$pdf->AddPage();

						//$pdf->Ln(1);
						$pdf->SetLeftMargin(1);
						//$pdf->Code128(5,$pdf->GetY(),$arrProds[$i]['codigo'],15,5);
						//$codigo=$pdf->Code128(3,3,$arrProds[$i]['codigo'],20,10);
						$pdf->MultiCell(0,9,$pdf->Code128(1,9,$arrProds[$i]['codigo'],22,5),1,'R',false);
						/*
						$pdf->SetFont('Arial','B',10);
						$pdf->Ln(4);
						$pdf->Cell(30,2,$arrProds[$i]['codigo'],1,0,'R');
						$pdf->Ln(7);
						$pdf->Cell(36,2,$arrProds[$i]['marca'],1,0,'R');
						*/



						# Codigo de barras #
						
						//$pdf->SetXY(7,$pdf->GetY()+6);
						//$pdf->SetFont('Arial','',12);
						//$pdf->MultiCell(0,6,utf8_decode("01931"),0,'C',false);


					}
					//$pdf->Output("F","Ticket_Nro_1.pdf",true);
					$pdf->Output();die();
					//echo json_encode($pdf,JSON_UNESCAPED_UNICODE);

					//dep($pdf);die();
				}
			}
		}



        public function getVenta($idventa){
			if($_SESSION['permisosMod']['r']){
				$idventa = intval($idventa);
				if($idventa > 0){
					$arrData = $this->model->selectVenta($idventa);
					if(empty($arrData)){
						$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
					}else{
						$arrProds = $this->model->selectProductos($idventa);
						
						$arrData['productos'] = $arrProds;
						$arrResponse = array('status' => true, 'data' => $arrData);
					}
						$arrResponse['data']['total']=formatMoney($arrResponse['data']['total']);
						$arrResponse['data']['sdescuento']=formatMoney($arrResponse['data']['sdescuento']);
						$arrResponse['data']['pimpuesto']=formatMoney($arrResponse['data']['pimpuesto']);
						$arrResponse['data']['grantotal']=formatMoney($arrResponse['data']['grantotal']);
						$arrResponse['data']['sdescuento']=formatMoney($arrResponse['data']['sdescuento']);
                    //dep($arrResponse);
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}
        
        public function delDetalleVenta($iddetalleventa){
            if($_SESSION['permisosMod']['d']){
                $intIdDetalle = intval($iddetalleventa);
                $requestDelete = $this->model->deleteDetalleVenta($intIdDetalle);
                if($requestDelete)
                {
                    $arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el producto');
                }else{
                    $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el producto.');
                }
                echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
            }
        }

        public function delVenta(){
			if($_POST){
				if($_SESSION['permisosMod']['d']){
					$intIdventa = intval($_POST['idVenta']);
					// Aqui primero devolver los productos de detalle_venta al inventario producto...
					// Si es exitoso, seguir con el borrado de la venta...

					//........

					//$requestRegresarProd = $this->model->regresaProductos($intIdventa);


					$requestDelete = $this->model->deleteVenta($intIdventa);
					if($requestDelete)
					{
						$arrResponse = array('status' => true, 'msg' => 'Se ha cancelado la orden de salida');
					}else{
						$arrResponse = array('status' => false, 'msg' => 'Error al cancelar la orden de salida.');
					}
					echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				}
			}
			die();
		}

		public function getMaxComprobante(){
			//Obtiene el siguiente comprobante para usar en una venta.
			
			$ComprobanteMax="";
			$arrData = $this->model->getComprobanteMax();
			
			$Comprobante=$arrData[rfcempresa];
			$long=strlen($Comprobante);

			$hoy = date("dmY");

			if($long>3){
				$Comprobante = substr($Comprobante, -3);
			}else{
				$Comprobante = "PRU";
			}

			$Max = $arrData[idventa]+1;
			
			$ComprobanteMax = $Comprobante."-".$hoy."-".$Max;

			//dep($ComprobanteMax);die();


			if(strlen($ComprobanteMax) >0){
				$arrResponse = array('status' => true, 'comprobante' => $ComprobanteMax, 'msg' => 'Comprobante siguiente');
			}else {
				$arrResponse = array('status' => false, 'msg' => 'Error con codigo');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			//return $CodigoMax;
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