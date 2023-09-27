<?php 

	class EmpresasModel extends Mysql
	{
		private $intIdEmpresa;
		private $strNombreEmpresa;
		private $strRfcEmpresa;
		private $strDireccionEmpresa;
		private $intTelEmpresa;
		private $intCelEmpresa;
		private $strEmailEmpresa;
		private $intStatus;
		private $strRuta;
		private $strImagen;
		private $intlistCiudad;
		private $strCP;
		private $strNumExt;
        private $strNumInt;
        private $strColonia;
        private $intlistEstado;
        private $intlistRegimen;



		public function __construct()
		{
			parent::__construct();
		}

		public function selectEmpresas(){
            $sql = "SELECT e.idempresa, e.nombreempresa, e.rfcempresa, e.direccionempresa, e.ciudadempresa, 
                e.cpempresa, e.telempresa, e.celempresa, e.emailempresa, e.status, e.ciudadempresa, 
                (select img from imagenempresa where empresaid = e.idempresa limit 1) as img,
				(select nombre from municipios where municipios.id=e.ciudadempresa) as nciudad,
				(select nombre from estados where estados.id=e.estado)as nestado,        
                (select descripcion_reg from rfiscal where rfiscal.id=e.regfiscal) as nregfiscal
            FROM empresas e
            WHERE e.status != 0";
			$request = $this->select_all($sql);
		return $request;
		}	

        /*string $ruta, poner antes de status*/
		public function insertEmpresa(string $nombre, string $rfc, string $direccion, int $tel, string $email, int $status, string $strNumExt, string $strNumInt, string $strColonia, string $strCP, int $intlistEstado, int $intlistCiudad, int $intlistRegimen, $cel){
			
			$this->strNombreEmpresa = $nombre;
			$this->strRfcEmpresa = $rfc;
			$this->strDireccionEmpresa = $direccion;
			$this->intlistCiudad = $intlistCiudad;
			$this->strCP = $strCP;
			$this->intTelEmpresa = $tel;
			$this->intCelEmpresa= $cel;
			$this->strEmailEmpresa = $email;
			$this->intStatus = $status;

			$this->strNumExt = $strNumExt;
			$this->strNumInt = $strNumInt;
			$this->strColonia = $strColonia;
			$this->intlistEstado = $intlistEstado;
			$this->intlistRegimen = $intlistRegimen;

			$return = 0;
			$sql = "SELECT * FROM empresas WHERE (rfcempresa = trim('{$this->strRfcEmpresa}') OR trim(nombreempresa = '{$this->strNombreEmpresa}') AND status != 0)";
			$request = $this->select_all($sql);
			
			//dep($request);die();
			if(empty($request))
			{
                /*ruta,  poner antes de status*/  /*,? poner al final de values*/
				$query_insert  = "INSERT INTO empresas(nombreempresa, rfcempresa, direccionempresa, ciudadempresa, cpempresa, telempresa, emailempresa, status, numext, numint, colonia, estado, regfiscal, celempresa) 
								  VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                //$this->strRuta, poner antes de intStatus
	        	$arrData = array($this->strNombreEmpresa,
        						$this->strRfcEmpresa,
        						$this->strDireccionEmpresa,
								$this->intlistCiudad,
        						$this->strCP,
        						$this->intTelEmpresa,
								$this->strEmailEmpresa,
        						$this->intStatus,
								$this->strNumExt,
								$this->strNumInt,
								$this->strColonia,
								$this->intlistEstado,
								$this->intlistRegimen,
								$this->intCelEmpresa);
                //dep($query_insert);
                //dep($arrData);
                //die();
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}
        // string $ruta, poner antes de status
		public function updateEmpresa(int $idempresa, string $nombre, string $rfc, string $direccion, int $tel, string $email, int $status, string $strNumExt, string $strNumInt, string $strColonia, string $strCP, int $intlistEstado, int $intlistCiudad, int $intlistRegimen, int $cel){			                                    																		
			$this->intIdEmpresa = $idempresa;
			$this->strNombreEmpresa = $nombre;
			$this->strRfcEmpresa = $rfc;
			$this->strDireccionEmpresa = $direccion;
			$this->intlistCiudad = $intlistCiudad;
			$this->strCP = $strCP;
			$this->intTelEmpresa = $tel;
			$this->intCelEmpresa = $cel;
			$this->strEmailEmpresa = $email;
			$this->intStatus = $status;
			$this->strNumExt = $strNumExt;
			$this->strNumInt = $strNumInt;
			$this->strColonia = $strColonia;
			$this->intlistEstado = $intlistEstado;
			$this->intlistRegimen = $intlistRegimen;


			$return = 0;
			$sql = "SELECT * FROM empresas WHERE (rfcempresa = '{$this->strRfcEmpresa}' OR nombreempresa = '{$this->strNombreEmpresa}' ) AND idempresa != $this->intIdEmpresa";
			$request = $this->select_all($sql);
			if(empty($request))
			{
                //ruta=?,
				$sql = "UPDATE empresas 
						SET nombreempresa=?,
							rfcempresa=?,
							direccionempresa=?,
							ciudadempresa=?,
							cpempresa=?,
							telempresa=?,
							emailempresa=?,
							status=?,
							numext=?,
							numint=?,
							colonia=?,
							estado=?,
							regfiscal=?,
							celempresa=? 
						WHERE idempresa = $this->intIdEmpresa ";
                 // $this->strRuta,
				$arrData = array($this->strNombreEmpresa,
        						$this->strRfcEmpresa,
        						$this->strDireccionEmpresa,
								$this->intlistCiudad,
        						$this->strCP,
        						$this->intTelEmpresa,
								$this->strEmailEmpresa,
        						$this->intStatus,
								$this->strNumExt,
								$this->strNumInt,
								$this->strColonia,
								$this->intlistEstado,
								$this->intlistRegimen,
								$this->intCelEmpresa);

	        	$request = $this->update($sql,$arrData);
	        	$return = $request;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectEmpresa(int $idempresa){
			$this->intIdEmpresa = $idempresa;
			$sql = "SELECT e. *,
			(select nombre from municipios where municipios.id=e.ciudadempresa) as nciudad,
			(select nombre from estados where estados.id=e.estado)as nestado,        
            (select descripcion_reg from rfiscal where rfiscal.id=e.regfiscal) as nregfiscal
					FROM empresas e
					WHERE idempresa = $this->intIdEmpresa";
			$request = $this->select($sql);
			//dep($request);
			return $request;

		}


		public function insertImage(int $idempresa, string $imagen){
			$this->intIdEmpresa = $idempresa;
			$this->strImagen = $imagen;
			$query_insert  = "INSERT INTO imagenempresa(empresaid,img) VALUES(?,?)";
	        $arrData = array($this->intIdEmpresa,
        					$this->strImagen);
	        $request_insert = $this->insert($query_insert,$arrData);
	        return $request_insert;
		}

		public function selectImages(int $idempresa){
			$this->intIdEmpresa = $idempresa;
			$sql = "SELECT empresaid,img
					FROM imagenempresa 
					WHERE empresaid = $this->intIdEmpresa";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deleteImage(int $idempresa, string $imagen){
			$this->intIdEmpresa = $idempresa;
			$this->strImagen = $imagen;
			$query  = "DELETE FROM imagenempresa WHERE empresaid = $this->intIdEmpresa AND img = '{$this->strImagen}'";
			//dep($query);die();
	        $request_delete = $this->delete($query);
	        return $request_delete;
		}

		public function deleteEmpresa(int $idempresa){
			$this->intIdEmpresa = $idempresa;
            $sqle = "SELECT * FROM persona WHERE idempresa = $this->intIdEmpresa";
            $requeste = $this->select_all($sqle);
            if(empty($requeste)){
				$this->intIdEmpresa = $idempresa;
				$sql = "UPDATE empresas SET status = ? WHERE idempresa = $this->intIdEmpresa ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				return $request;
			}else {
                $request = 'exist';
            }
		}

		public function selectRegimen(){
            $sql = "SELECT id, clave_reg, descripcion_reg
                    FROM rfiscal 
                    order by id ASC";
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

	}
 ?>