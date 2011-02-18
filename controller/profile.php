<?php

	class Profile extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->model('gamecore');
			$this->load->library('validation');
			$this->gamecore->loginchecker();
		
		}
		
		function index(){
			
			$this->load->view("404");

		}
		
		function player($id=false){
			if($id && $this->validation->is_numeric($id)) {
				$check = $this->db->query("SELECT id, username, signup_date, status FROM users WHERE id = '$id'")->row();
				if($check) {
					
					$profile = $this->db->query("SELECT quote FROM profile WHERE playerid = '$id'")->row();
					$data['profile']=$profile;
					$data['userinfo']=$check;
					$this->load->view('profile-player',$data);
			
				} else {
					$this->load->view("404");
				}
			} else {
				$this->load->view("404");
			}
		}

		function crew($id){
			echo "hello crew ".$id;
			
		}
						
		
	}