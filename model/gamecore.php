<?php

	class GameCore extends Model{
				
		function loginchecker(){
			$id = $_SESSION['id'];
			
			if(isset($_SESSION['id'])){
				$ban_check = $this->db->query("SELECT * FROM banned WHERE playerid = '$id'")->row();
				$hibernation_check = $this->db->query("SELECT * FROM hibernation WHERE playerid = '$id'")->row();
				
				if($ban_check && $ban_check['ban_time'] > time()) {
					header("Location: ".$this->core->get_config_item('base_url')."home/banned");				
				} elseif($hibernation_check && $hibernation_check['hibernation_time'] > time()){
					header("Location: ".$this->core->get_config_item('base_url')."home/hibernation");					
				} else {
					$time = time();
					$this->db->query("UPDATE characters SET lastonline = '$time' WHERE id = '$id'");
				}
				
			} else {
				header("Location: ".$this->core->get_config_item('base_url'));
			}

		}
		
		function middlechecker(){
			
			if(!isset($_SESSION['logincheck'])){
				header("Location: ".$this->core->get_config_item('base_url'));
			}
			
		}

		function outsidechecker(){
			
			if(isset($_SESSION['id'])){
				header("Location: ".$this->core->get_config_item('base_url')."main/");
			}

		}
		
		function mail_notification($id,$message){

			$messageStripped = htmlentities(trim($message),ENT_QUOTES,'UTF-8');
										
			$fields['seq']=1;
			$fields['created_on']=time();
			$fields['created_on_ip']=$_SERVER['REMOTE_ADDR'];						
			$fields['created_by']=0;						
			$fields['body']=$messageStripped;				
			$this->db->insert('messages',$fields);
			
			$mid = mysql_insert_id();
			
			$fields_attach = array();
			$fields_attach['mid']=$mid;
			$fields_attach['seq']=1;
			$fields_attach['uid']=$id;
			$fields_attach['status']='N';					
			$this->db->insert('messages_attached',$fields_attach);

			$fields_attach = array();
			$fields_attach['mid']=$mid;
			$fields_attach['seq']=1;
			$fields_attach['uid']=0;
			$fields_attach['status']='N';					
			$this->db->insert('messages_attached',$fields_attach);
			
			
		}
		
		function userinfo($id,$fields){
			
			$join = false;
			if(strlen(strstr($fields,"u."))>0 || strlen(strstr($fields,"c."))>0) {
				// a field with user. has been used. Force a join.
				$join = true;
			}
			
			if($join) {
				$userinfo = $this->db->query("SELECT $fields FROM characters c INNER JOIN users u ON u.id = c.userid WHERE c.id = '$id'")->row();
			} else {
				$userinfo = $this->db->query("SELECT $fields FROM characters WHERE id = '$id'")->row();
			}
			
			return $userinfo;
		}
	
	}