<?php

	class Logout extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();

		}
		
		function index(){
			
			session_unset("id");
			session_unset("logincheck");
			$_SESSION = array(); /* Clear varaibles */
			session_destroy(); /* End session */
			setcookie (session_name(), '', time()-300, '/', '', 0); /* Destroy cookies */
			header("Location: ".$this->core->get_config_item('base_url'));
		 
		}
			
	}