<?php

	class Main extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();

		}
		
		function index(){
				
			$this->load->view("main");
		 
		}
			
	}