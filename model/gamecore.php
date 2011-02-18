<?php

	class GameCore extends Model{
				
		function loginchecker(){
			session_start();
			$id = $_SESSION['id'];
			
			if(isset($_SESSION['id'])){
				$ban_check = $this->db->query("SELECT * FROM banned WHERE playerid = '$id'")->row();
				$hibernation_check = $this->db->query("SELECT * FROM hibernation WHERE playerid = '$id'")->row();
				
				if($ban_check && $ban_check['ban_time'] > time()) {
					header("Location: ".$this->core->get_config_item('base_url')."home/banned");				
				} elseif($hibernation_check && $hibernation_check['hibernation_time'] > time()){
					header("Location: ".$this->core->get_config_item('base_url')."home/hibernation");					
				}
				
			} else {
				header("Location: ".$this->core->get_config_item('base_url'));
			}

		}
		
		function middlechecker(){
			session_start();
			
			if(!isset($_SESSION['logincheck'])){
				header("Location: ".$this->core->get_config_item('base_url'));
			}
			
		}

		function outsidechecker(){
			session_start();
			
			if(isset($_SESSION['id'])){
				header("Location: ".$this->core->get_config_item('base_url')."main/");
			}

		}
		
		function mail_notification($id,$message){
		//Mail the player	
			
			
		}
	
	}