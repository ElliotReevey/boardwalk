<?php

	class LoginCheck extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->outsidechecker();

		}
		
		function index(){
			
			$data = $this->page_info();
			$this->load->view("home/logincheck",$data);
		 
		}
		
		function submit(){
			
			$data = $this->page_info();
			
			if(isset($_POST['submitButton'])) {
				$antiscript = $_POST['antiScript'];

				if($this->validation->is_numeric($antiscript)) {
					//if($antiscript == $actualcode) {

						$_SESSION['id']=$data['id'];
						header("Location: ".$this->core->get_config_item('base_url')."main");

/*
					} else {
						$data['fail'] = "You must enter the code correctly.";
					}
*/
				} else {
					$data['fail'] = gettext("You must enter a valid anti script code.");
				}
				
				if(isset($data['fail'])) {
					$this->load->view('home/logincheck',$data);
				}
					
			} else {
				$this->load->view("home/logincheck",$data);
			}
		
		}
		
		function page_info(){
			$data = $this->db->query("SELECT id, username FROM users WHERE id = '{$_SESSION['logincheck']}'")->row();
			$data['gamename'] = $this->core->get_config_item('name','application');
			return $data;		
		}
			
	}