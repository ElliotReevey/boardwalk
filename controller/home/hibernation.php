<?php

	class Hibernation extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->model('gamecore');
			$this->gamecore->middlechecker();
			
		}
		
		function index(){
			
			$id = $_SESSION['logincheck'];
			$data = $this->db->query("SELECT hibernation_time, username, email FROM hibernation INNER JOIN users ON users.id = hibernation.playerid WHERE hibernation.playerid = '$id'")->row();
			if($data) {
				$data['gamename'] = $this->core->get_config_item('name','application');
				$data['systememail'] = 	$this->core->get_config_item('default_system_email');
				$this->load->view("home/hibernation",$data);
			} else {
				if(isset($_SESSION['id'])){
					header("Location: ".$this->core->get_config_item('base_url')."main");
				} else {
					header("Location: ".$this->core->get_config_item('base_url'));
				}
			}

		}
		
	}