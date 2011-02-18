<?php

	class ForgottenPassword extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->outsidechecker();
		
		}
		
		function index(){
						
			$this->load->view("home/forgottenpassword");
		
		}

		function submit(){
		
			if(isset($_POST['submitButton'])) {
				$email = $_POST['emailAddress'];
				
					if($this->validation->valid_email($email)) {
						$check = $this->db->query("SELECT username, email FROM users WHERE email = '$email'")->row();
						if($check) {
							$lastreset = $this->db->query("SELECT * FROM reset_password WHERE email = '$email'")->row();
							if(!$lastreset) {
							
								//Generate reset code
								$this->load->library('utilities');
								$code = $this->utilities->generateToken(75);
								
								//Insert into database
								$fields['email']=$email;
								$fields['code']=$code;
								$fields['time']=time();
								$this->db->insert('reset_password',$fields);
	
								//Send the email
								$this->load->library('mail');
								$this->mail
	            				    ->setTo($check['email'],$check['username'])
					                ->setSubject($this->core->get_config_item('name','application')." - Forgotten Password")
					                ->setPlain($this->core->get_config_item('name','application')." Reset Password Request
					                
We have recently received a request to reset the password for your Street Crime account attached to this email address.
	
If this request was made by you please follow the instructions below to reset your street crime password. If not please simply ignore or delete this email.
	
Click the link below or copy it into your web browser:
".$this->core->get_config_item('base_url')."home/resetpassword/auth/".$code."/
	
These links will be valid for 1 day only, after that you will have to make a new request.
	
".$this->core->get_config_item('name','application')." Staff
".$this->core->get_config_item('base_url'))
					                ->setHtml("<h2>".$this->core->get_config_item('name','application')." Reset Password Request</h2>
					                We have recently received a request to reset the password for your Street Crime account attached to this email address.<br><br>
	If this request was made by you please follow the instructions below to reset your street crime password. If not please simply ignore or delete this email.<br><br>
	Click the link below or copy it into your web browser:<br>
	".$this->core->get_config_item('base_url')."home/resetpassword/auth/".$code."/<br><br>
	These links will be valid for 1 day only, after that you will have to make a new request.<br><br>
	".$this->core->get_config_item('name','application')." Staff<br>
	".$this->core->get_config_item('base_url'))
					                ->send();
								
								header("Location: ".$this->core->get_config_item('base_url')."home/forgottenpassword/success/");
							
							} else {
								$data['fail'] = gettext("You have requested a password reset recently, check your email for more information.");
							}
						} else {
							$data['fail'] = gettext("The email address entered was not found in our system.");
						}										
					} else {
						$data['fail'] = gettext("You must enter a valid email address.");
					}
				
				if(isset($data['fail'])) {
					$this->load->view('home/forgottenpassword',$data);
				}
								
			} else {
				$this->load->view("home/forgottenpassword");
			}	
		
		}

		function success(){
		
			$this->load->view("home/forgottenpassword_success");
		
		}
	
	}