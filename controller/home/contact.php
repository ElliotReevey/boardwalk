<?php

	class Contact extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->outsidechecker();

		}
		
		function index(){
		
			$this->load->view("home/contact");
					
		}
		
		function submit(){
		
			if(isset($_POST['submitButton'])) {
				$name = $_POST['fullName'];
				$email = $_POST['emailAddress'];
				$subject = $_POST['subjectType'];
				$message = $_POST['messageBox'];
				$antiscript = $_POST['antiScript'];
				
				if($this->validation->alpha_loose($name)) {
					if($this->validation->valid_email($email)) {
						if($this->validation->checkdata($subject,4)){
							if($message) {
								if($this->validation->is_numeric($antiscript)) {
									//if($antiscript == $actualcode) {

										//Send the email
										$this->load->library('mail');
										$this->mail
							                ->setSubject($this->core->get_config_item('name','application')." - Contact Form")
							                ->setPlain($this->core->get_config_item('name','application')." Contact Form
							                
The following message has been sent via the contact form on ".$this->core->get_config_item('name','application').".
							                
Subject: ".$subject."
Name: ".$name."
Message: ".stripslashes($message)."
							                
Replies go to: ".$email."")
							                ->setHtml("<h2>".$this->core->get_config_item('name','application')." Contact Form</h2>
							                The following message has been sent via the contact form on ".$this->core->get_config_item('name','application').".<br><br>
							                Subject: ".$subject."<br>
							                Name: ".$name."<br>
							                Message: ".nl2br(stripslashes($message))."<br><br>
							                Replies go to: ".$email."")
							                ->setSystem()
							                ->send();
							                									
										header("Location: ".$this->core->get_config_item('base_url')."home/contact/success/");
										
/*
									} else {
										$data['fail'] = "You must enter the code correctly.";
									}
*/
								} else {
									$data['fail'] = gettext("You must copy the code correctly.");
								}
							} else {
								$data['fail'] = gettext("You must enter a valid message.");
							}
						} else {
							$data['fail'] = gettext("You must select a subject.");
						}
					} else {
						$data['fail'] = gettext("You must enter a valid email address.");
					}
				} else {
					$data['fail'] = gettext("You must enter a valid name.");
				}
				
				if(isset($data['fail'])) {
					$this->load->view('home/contact',$data);
				}
			} else {
				$this->load->view("home/contact");
			}	
		}
		
		function success(){
		
			$this->load->view("home/contact_success");
		
		}
				
	}