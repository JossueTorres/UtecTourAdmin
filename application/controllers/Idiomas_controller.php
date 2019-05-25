<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Idiomas_controller extends CI_Controller
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
		$url = 'http://localhost/UtecTourServices/Idiomas/listado';
		//creamos
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

		$result = curl_exec($ch);

		//cerramos
		curl_close($ch);

		$res = json_decode($result);
		$data['listado'] = json_decode($result);
		$this->load->view('_Layout/Header_Master');
		$this->load->view('Idiomas', $data);
		$this->load->view('_Layout/Footer_Master');
	}
}
