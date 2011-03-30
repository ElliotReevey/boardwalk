<?php

	class Compose extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');			
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
		
		function index(){
			
			$this->load->view("communication/compose");

		}
		
		function send(){
		
			if(isset($_POST['sendMessage'])){
				$sendto = isset($_POST['sendTo']) ? $_POST['sendTo'] : 0;
				$body = $_POST['messageBox'];
				$id = $_SESSION['id'];
				$mid = isset($_POST['mid']) ? $_POST['mid'] : 0;
				$yourusername = $this->db->query("SELECT username FROM characters WHERE id = '$id'")->get();
				
				//Check if new compose or existing
				$errors = 0;$invalid = 0;$notexist = 0;$notyou = 0;$notalive = 0;$reply = 0;

				if(!empty($mid)){
					$rows = $this->db->query("SELECT DISTINCT(uid) as uid FROM messages_attached m WHERE mid='$mid'")->rows();
					$row = $this->db->query("SELECT MAX(seq)+1 AS seq FROM messages WHERE mid='$mid'")->row();
					$seq = $row['seq'];
					$reply = 1;
				} else {
					$seq = 1;
					$usernames = explode(',',$sendto);
					$usernames = array_unique($usernames);
					$rows = array();
										
					//Validate each username here
					foreach($usernames as $username){
						$username = trim($username);
						if($this->validation->userName($username)){
						$check = $this->db->query("SELECT * FROM characters WHERE username = '$username'")->row();
							if($check){
								if($check['id'] != $id) {
									if($check['status'] == 'Alive'){
										
										$rows[] = array('uid'=>$check['id']);
									
									} else {
										$notalive+=1;
										$notalive_usernames[] = $username;
									}
								} else {
									$notyou+=1;
								}
							} else {
								$notexist+=1;
								$notexist_usernames[] = $username;
							}							
						} else {
							$invalid+=1;
							$invalid_usernames[] = $username;
						}
					}
					
					$rows[] = array('uid'=>$id);
				}
				
				if($invalid == 0) {
					if($notexist == 0) {
						if($notyou == 0) {
							if($notalive == 0) {
								if($body){
									if(count($rows) > 0) {
								
										$messageStripped = htmlentities(trim($_POST['messageBox']),ENT_QUOTES,'UTF-8');
										
										$fields['mid']=$mid;
										$fields['seq']=$seq;
										$fields['created_on']=time();
										$fields['created_on_ip']=$_SERVER['REMOTE_ADDR'];						
										$fields['created_by']=$id;						
										$fields['body']=$messageStripped;				
										$this->db->insert('messages',$fields);
										
										if(empty($mid)){
											$mid = mysql_insert_id();
										}
										
										foreach($rows as $row){
											$fields_attach = array();
											$fields_attach['mid']=$mid;
											$fields_attach['seq']=$seq;
											$fields_attach['uid']=$row['uid'];
											$fields_attach['status']=$row['uid'] == $id ? 'A' : 'N';					
											$this->db->insert('messages_attached',$fields_attach);
										}
								
										$data['success'] = "Your message has been sent.";
			
									} else {
										$data['fail'] = "You must include at least one username to send your message to.";
									}
								} else {
									$data['fail'] = "You cannot send blank messages.";
								}
							} else {
								if(count($notalive_usernames) > 1) {
									$last = array_pop($notalive_usernames);
									$noalive = implode(', ', $notalive_usernames) . ' and ' . $last;
								} else {
									$nousers = $notalive_usernames[0];
								}
								$data['fail'] = sprintf(gettext("Message not sent, the following users are not Alive: %s."),$noalive);
							}
						} else {
							$data['fail'] = gettext("You cannot send messages to yourself.");
						}
					} else {
						if(count($notexist_usernames) > 1) {
							$last = array_pop($notexist_usernames);
							$notusers = implode(', ', $notexist_usernames) . ' and ' . $last;
						} else {
							$notusers = $notexist_usernames[0];
						}
						$data['fail'] = sprintf(gettext("Message not sent, the following users do not exist: %s."),$notusers);
					}
				} else {
					if(count($invalid_usernames) > 1) {
						$last = array_pop($invalid_usernames);
						$invusers = implode(', ', $invalid_usernames) . ' and ' . $last;
					} else {
						$invusers = $invalid_usernames[0];
					}
					$data['fail'] = sprintf(gettext("Message not sent, the following usernames are invalid: %s."),$invusers);
				}
				
				if($reply == 0) {
					$this->load->view("communication/compose",$data);
				} else {
					$this->session->set_displaydata('messages',$data);
					$site_url = $this->core->get_config_item('base_url');
					header("Location: ".$site_url."inbox/conversation/".$mid."/");
					exit();
				}
			
			} else {
				$this->load->view("communication/compose");
			}
		
		}
		
	}