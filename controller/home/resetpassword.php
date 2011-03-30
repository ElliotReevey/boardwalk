<?php

	class ResetPassword extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->outsidechecker();

		}
		
		function index(){

			$this->load->view("home/resetpassword");
			
		}
		
		function auth($string=false){
			if(preg_match('/^[A-Za-z0-9]{45}+$/',$string)){
				$check = $this->db->query("SELECT * FROM reset_password WHERE code = '$string'")->row();
				if($check) {
					$data['token'] = $string;
					$this->load->view("home/resetpassword",$data);
				} else {
					$this->load->view("404");
				}
			} else {
			$this->load->view("404");
			}
		}

		function submit(){
			$data['token'] = $_POST['token'];
						
			if(isset($_POST['submitButton'])) {
				$password = $_POST['newPassword'];
				$confirmpassword = $_POST['confirmNewPassword'];
				$token = $_POST['token'];
				
				if($password) {
					if($confirmpassword) {
						if($password == $confirmpassword){
							if($token) {
							$check = $this->db->query("SELECT * FROM reset_password WHERE code = '$token'")->row();
								if($check) {
									
									$md5password = md5($password);
									$this->db->query("UPDATE users SET password = '$md5password' WHERE email = '{$check['email']}'");
									$this->db->query("DELETE FROM reset_password WHERE id = '{$check['id']}'");
										
									header("Location: ".$this->core->get_config_item('base_url')."home/resetpassword/success/");
										
								} else {
									$data['fail'] = gettext("The token provided was incorrect, please check you typed it correctly.");
								}
							} else {
								$data['fail'] = gettext("Invalid token, please try clicking the link from your email again.");
							}
						} else {
							$data['fail'] = gettext("Your password must match your confirmed password.");
						}
					} else {
						$data['fail'] = gettext("You must confirm your password.");
					}
				} else {
					$data['fail'] = gettext("You must enter a password.");
				}

				if(isset($data['fail'])) {
					$this->load->view('home/resetpassword',$data);
				}

			} else {
				$this->load->view("home/resetpassword");
			}
		
		}

		function success(){
		
			$this->load->view("home/resetpassword_success");
		
		}
	
	}