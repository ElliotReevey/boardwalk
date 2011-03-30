<?php

	class Unsetcookie extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();

		}
		
		function index(){
			
			unset($_COOKIE['locale']);
			setcookie("locale", "", time()-(86400*365));
			echo "in here";
			
			print_r($_COOKIE['locale']);
		 
		}
			
	}