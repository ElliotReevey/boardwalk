<?php

	class Banned extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->model('gamecore');
			$this->gamecore->middlechecker();
			
		}
		
		function index(){
			
			$id = $_SESSION['logincheck'];
			$data = $this->db->query("SELECT ban_reason, ban_time, username, email FROM banned b INNER JOIN characters c ON c.id = b.playerid INNER JOIN users u ON u.id = c.userid WHERE b.playerid = '$id'")->row();
			if($data) {
				$data['gamename'] = $this->core->get_config_item('name','application');
				$data['systememail'] = 	$this->core->get_config_item('default_system_email');
				$this->load->view("home/banned",$data);
			} else {
				if(isset($_SESSION['id'])){
					header("Location: ".$this->core->get_config_item('base_url')."main");
				} else {
					header("Location: ".$this->core->get_config_item('base_url'));
				}
			}

		}
		
	}