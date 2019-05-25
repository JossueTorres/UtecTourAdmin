<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autenticar_controller extends CI_Controller
{

	//Constructor del controlador: aca se agregan referencias a modelos
	public function __construct()
	{
		parent::__construct();
	}

	//funcion principal
	public function index()
	{
		//verificar la session de usuario
		$this->load->view('login');
	}

	public function Login()
	{
		// $this->load->library('session');
		// $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		$_param = array(
			'usr' => $this->input->post("txtUsr"),
			'pass' => $this->input->post("txtPass"),
		);
		//__________________________________________________________________________________
		$postData = '';
		//create name value pairs seperated by &
		foreach ($_param as $k => $v) {
			$postData .= $k . '=' . $v . '&';
		}
		rtrim($postData, '&');

		$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
		$url = 'http://localhost/UtecTourServices/Fun/Login';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);

		curl_setopt($ch, CURLOPT_HEADER, false);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $ua);
		curl_setopt($ch, CURLOPT_COOKIE, 'NID=67=pdjIQN5CUKVn0bRgAlqitBk7WHVivLsbLcr7QOWMn35Pq03N1WMy6kxYBPORtaQUPQrfMK4Yo0vVz8tH97ejX3q7P2lNuPjTOhwqaI2bXCgPGSDKkdFoiYIqXubR0cTJ48hIAaKQqiQi_lpoe6edhMglvOO9ynw; PREF=ID=52aa671013493765:U=0cfb5c96530d04e3:FF=0:LD=en:TM=1370266105:LM=1370341612:GM=1:S=Kcc6KUnZwWfy3cOl; OTZ=1800625_34_34__34_; S=talkgadget=38GaRzFbruDPtFjrghEtRw; SID=DQAAALoAAADHyIbtG3J_u2hwNi4N6UQWgXlwOAQL58VRB_0xQYbDiL2HA5zvefboor5YVmHc8Zt5lcA0LCd2Riv4WsW53ZbNCv8Qu_THhIvtRgdEZfgk26LrKmObye1wU62jESQoNdbapFAfEH_IGHSIA0ZKsZrHiWLGVpujKyUvHHGsZc_XZm4Z4tb2bbYWWYAv02mw2njnf4jiKP2QTxnlnKFK77UvWn4FFcahe-XTk8Jlqblu66AlkTGMZpU0BDlYMValdnU; HSID=A6VT_ZJ0ZSm8NTdFf; SSID=A9_PWUXbZLazoEskE; APISID=RSS_BK5QSEmzBxlS/ApSt2fMy1g36vrYvk; SAPISID=ZIMOP9lJ_E8SLdkL/A32W20hPpwgd5Kg1J');

		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		$output = curl_exec($ch);
		$val = json_decode($output);
		$last = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
		// return array($result, $last);

		//__________________________________________________________________________________

		// if ($this->input->post("txtUsr") == 'admin@mail.com')
		if ($output)
			$valida = true;
		else
			$valida = false;

		//si el nombre de usuario ingresado es correcto enviarlo al controlador Dashboard
		if ($valida == true) {
			$data  = array(
				'usuario' => $_param['usr'],
				'login' => $valida,
				'nom' => $val[0]->{'usr_correo'},
				// 'va' => $val['_log'],
				// 'ou' => $output,
				// 'pd' => $postData,
			);
			// $this->session->set_userdata('logged_in', $session_data);	
			$this->session->set_userdata($data);

			// $usr =  $this->session->userdata("usuario");			
			redirect(base_url('/Edificios'));
			// if($usr=="estudiante")
			// 	redirect(base_url() . "c_estudiante/Inicio");
			// else if($usr=="admin")
			// redirect(base_url() . "c_admin/Inicio");
			// else if($usr=="lab")
			// redirect(base_url() . "c_laboratorio/Inicio");

		} else
			redirect(base_url('/Login'));
	}

	public function logout()
	{
		session_start();
		session_unset();
		session_destroy();
		redirect(base_url('/Login'));
	}
}
