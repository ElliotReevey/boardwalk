<?php

	class Index extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->outsidechecker();

		}
		
		function index(){
				
			$this->load->view("home/index");
		 
		}
	
		function submit(){
			if(isset($_POST['submitButton'])) {
				$email = $_POST['emailAddress'];
				$password = $_POST['password'];
				$md5password = md5($password);
				
				if($this->validation->valid_email($email)) {
					if($password) {
						//Check the email address and password match
						$check = $this->db->query("SELECT id, email, password, username FROM users WHERE email = '$email' ORDER BY id DESC LIMIT 1")->row();
						if($check) {
							if($check['password'] == $md5password) {
								$_SESSION['logincheck']=$check['id'];
													
								$ban_check = $this->db->query("SELECT * FROM banned WHERE playerid = '{$check['id']}'")->row();
								$hibernation_check = $this->db->query("SELECT * FROM hibernation WHERE playerid = '{$check['id']}'")->row();
								
								if($ban_check && $ban_check['ban_time'] > time()) {
									header("Location: ".$this->core->get_config_item('base_url')."home/banned");				
								} elseif($hibernation_check && $hibernation_check['hibernation_time'] > time()){
									header("Location: ".$this->core->get_config_item('base_url')."home/hibernation");					
								} elseif($check['username']) {
									header("Location: ".$this->core->get_config_item('base_url')."home/logincheck");
								} elseif($check['username'] == "") {
									header("Location: ".$this->core->get_config_item('base_url')."home/choosecharacter");
								}
														
							} else {
								$data['fail'] = gettext("The password you entered was incorrect.");
							}
						} else {
							$data['fail'] = gettext("Email address not found in our system.");
						}												
					} else {
						$data['fail'] = gettext("You must enter a password.");
					}
				} else {
					$data['fail'] = gettext("You must enter a valid email address.");
				}
				
				if(isset($data['fail'])) {
					$this->load->view('home/index',$data);
				}
								
			} else {
				$this->load->view("home/index");
			}	

		}
		
	}