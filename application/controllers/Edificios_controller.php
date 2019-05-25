<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Edificios_controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//verificar la session de usuario
		$ardat = $this->session->userdata();
		if (!$ardat['login']) {
			redirect(base_url('/Login'));
		}
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	}
	public function index()
	{
		$url = 'http://localhost/UtecTourServices/Edificios/listado';
		//creamos
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

		$result = curl_exec($ch);

		//cerramos
		curl_close($ch);

		$res = json_decode($result);
		$data['lstEdificios'] = json_decode($result);
		$this->load->view('_Layout/Header_Master');
		$this->load->view('Edificios', $data);
		$this->load->view('_Layout/Footer_Master');
	}

	public function Buscar()
	{
		$a = $this->input->post("txtAcronimo");
		$n = $this->input->post("txtNombre");
		$url = base_url('/Tour-Api/Edificios/listaEdificios_fil/?n=' . $n . '&a=' . $a);
		//creamos
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

		$result = curl_exec($ch);

		//cerramos
		curl_close($ch);

		$data['lstEdificios'] = json_decode($result);
		$this->load->view('_Layout/Header_Master');
		$this->load->view('Edificios', $data);
		$this->load->view('_Layout/Footer_Master');
	}

	public function guardarDatos()
	{
		$c = $this->input->post("codedf");
		$n = $this->input->post("txtNombre");
		$o = $this->input->post("txtOrden");
		$l = $this->input->post("txtLatitud");
		$lo = $this->input->post("txtLongitud");
		$a = $this->input->post("txtAcronimo");
		$i = $this->input->post("txtPath");
		$url = base_url('/Tour-Api/Edificios/guardarDatos/?c=' . $c . 'n=' . $n . '&o=' . $o . '&l=' . $l . '&lo=' . $lo . '&a=' . $a . '&i' . $i);
		//creamos nuevo recurso cURL 
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array());

		$result = curl_exec($ch);

		//cerramos el Curl
		curl_close($ch);

		$id = $this->input->POST('codedf');
		if ($id > 0) {
			$do = "Modificado";
			$er = "modificar";
		} else {
			$do = "Agregado";
			$er = "agregar";
		}
		if ($result) {
			$this->session->set_flashdata('success_msg', $do . ' correctamente');
		} else {
			$this->session->set_flashdata('error_msg', 'Error al ' . $er);
		}
		header('location:' . base_url('/TourUtec_Admin/Edificios'));
	}

	public function borrarDatos($ids)
	{
		$url = base_url('/Tour-Api/Edificios/listaEdificios_fil/?c=' . $c);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_POSTFIELDS, array());
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 90);
		$result = curl_exec($ch);
		url_close($ch);
		$ids = $this->input->post('chkBorrar');
		if (!empty($ids)) {
			$result = $this->clEdificio->borrarDatos($ids);
			if ($result) {
				$this->session->set_flashdata('success_msg', 'Los registros seleccionados se eliminaron correctamente');
			} else {
				$this->session->set_flashdata('error_msg', 'Fallo al eliminar registros');
			}
		} else {
			$this->session->set_flashdata('error_msg', 'Seleccione al menos 1 registro para eliminar');
		}
		header('location:' . base_url('/TourUtec_Admin/Edificios'));
	}
}
