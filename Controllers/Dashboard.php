<?php
    class Dashboard extends Controllers{
        public function __construct(){
            //sessionStart();
            parent::__construct();

            session_start();
			//session_regenerate_id(true);
			if(empty($_SESSION['login']))
			{
				header('Location: '.base_url().'/login');
			}
			getPermisos(1);
        }

        public function dashboard(){
            $data['page_id'] = 2;
            $data['nempresa'] = $this->model->empresa();
            $data['page_tag'] = "Dashboard - ".$data['nempresa'];
            $data['page_title'] = "Dashboard - ". $data['nempresa'];
            $data['page_name'] = "dashboard";
            $data['page_functions_js'] = "functions_dashboard.js";
            $data['usuarios'] = $this->model->cantUsuarios();
            $data['clientes'] = $this->model->cantClientes();
            $data['proveedores'] = $this->model->cantProveedores();
            $data['empresas'] = $this->model->cantEmpresas();
            $data['categorias'] = $this->model->cantCategorias();
            $data['productos'] = $this->model->cantProductos();
            $data['ingresos'] = $this->model->cantIngresos();
            $data['ventas'] = $this->model->cantVentas();
            $data['ultimasVentas'] = $this->model->ultimasVentas();
            $data['ultimasVentasNF'] = $this->model->ultimasVentasNF();
            

            $anio = date('Y');
            $mes  = date('m');
            $data['categoMes'] = $this->model->selectCategoMes($anio, $mes);
            $data['ventasMDia'] = $this->model->selectVentasMes($anio, $mes);
            $data['ventasAnio'] = $this->model->selectVentasAnio($anio);
            //dep($data['ventasAnio']);exit;
            //dep(Meses());exit;
            //dep($data['ventasMDia']);exit;
            $this->views->getView($this,"dashboard",$data);
        }

        public function tipoCategoMes(){
            if($_POST){
                $grafica = "categoMes";
                $nFecha = str_replace(" ","",$_POST['fecha']);
                $arrFecha = explode('-',$nFecha);
                $mes = $arrFecha[0];
                $anio = $arrFecha[1];
                $categos = $this->model->selectCategoMes($anio,$mes);
                $script = getFile("Template/Modals/graficas",$categos);
                echo $script;
                //dep($categos);die();
                //dep($data);die();
            }
        }

        public function ventasMes(){
            if($_POST){
                $grafica = "ventasMes";
                $nFecha = str_replace(" ","",$_POST['fecha']);
                $arrFecha = explode('-',$nFecha);
                $mes = $arrFecha[0];
                $anio = $arrFecha[1];
                $ventas = $this->model->selectVentasMes($anio,$mes);
                $script = getFile("Template/Modals/graficas",$ventas);
                echo $script;
                //dep($categos);die();
                //dep($data);die();
            }
        }

        public function ventasAnio(){
            if($_POST){
                $grafica = "ventasAnio";
                $anio = intval($_POST['anio']);
                $ventas = $this->model->selectVentasAnio($anio);
                $script = getFile("Template/Modals/graficas",$ventas);
                echo $script;
                //dep($categos);die();
                //dep($data);die();
            }
        }
    }
?>
