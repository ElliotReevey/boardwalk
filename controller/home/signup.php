<?php

	class Signup extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->outsidechecker();
		
		}
		
		function index(){
						
			$data['preload'] = $this->preLoadedData();
			$this->load->view("home/signup",$data);
		
		}

		function submit(){
		
			if(isset($_POST['submitButton'])) {
				$email = $_POST['emailAddress'];
				$confirm = $_POST['confirmEmail'];
				$gender = $_POST['genderType'];
				$age = $_POST['ageAmount'];
				$referral = $_POST['referralCode'];
				$antiscript = $_POST['antiScript'];
				$terms = $_POST['termsOfService'];
				$day = $_POST['selectDay'];
				$month = $_POST['selectMonth'];
				$year = $_POST['selectYear'];
				$country = $_POST['selectCountry'];
				$currentip = $_SERVER['REMOTE_ADDR'];
				
				if($this->validation->valid_email($email)) {
					if($this->validation->valid_email($confirm)){
						if($email == $confirm) {
							if($this->validation->is_numeric($day) && $this->validation->is_numeric($month) && $this->validation->is_numeric($year)) {
								$country_check = $this->db->query("SELECT * FROM countries WHERE value = '$country'");
								if($country_check) {
									if($this->validation->alpha_loose($gender)) {
										if($this->validation->is_numeric($age)) {
											if($this->validation->is_numeric($antiscript)) {
												//if($antiscript == $actualcode) {
													if($this->validation->is_numeric($terms)) {
														$check = $this->db->query("SELECT email FROM users WHERE email = '$email'")->row();
														if(!$check) {
														
															//Password
															$this->load->library('utilities');
															$password = $this->utilities->generateToken(10);
															$md5password = md5($password);
															
															//Send the email
															$this->load->library('mail');
															$this->mail
			    					        				    ->setTo($email,"")
												                ->setSubject("Welcome to ".$this->core->get_config_item('name','application'))
												                ->setPlain("Welcome to ".$this->core->get_config_item('name','application')."
												                
		You can now login at ".$this->core->get_config_item('base_url')." using the below password and email address.
		
		Your email: ".$email."
		Your password: ".$password."
		
		This password is case-sensitive. After logging in you can easily change your password!
		
		".$this->core->get_config_item('name','application')." Staff
		".$this->core->get_config_item('base_url'))
												                ->setHtml("<h2>Welcome to ".$this->core->get_config_item('name','application')."</h2>
												                You can now login at ".$this->core->get_config_item('base_url')." using the below password and email address.<br><br>
												                Your email: ".$email."<br>
												                Your password: ".$password."<br><br>
												                
												                This password is case-sensitive. After logging in you can easily change your password!<br><br>
												                ".$this->core->get_config_item('name','application')." Staff<br>
												                ".$this->core->get_config_item('base_url'))
												                ->send();
														
															//Insert new row
															$fields['email']=$email;
															$fields['password']=$md5password;
															$fields['signup_ip']=$currentip;
															$this->db->insert('users',$fields);
															$newuserid = mysql_insert_id();
		
															if($this->validation->checkdata($referral,4)){
																$ref_check = $this->db->query("SELECT * FROM referrals WHERE code = '$referral'")->row();
																if($ref_check) {
																	if($referral == $ref_check['code'] && $email == $ref_check['email']){
																		if($ref_check['ip'] != $currentip) {
																			$existing_check = $this->db->query("SELECT * FROM users WHERE signup_ip = '$currentip'")->row();
																			if(!$existing_check) {
																			
																				$time = time();
																				$this->model->load('referral');
																				$this->referral->referral_stage_1($newuserid,$ref_check['playerid']);
																				$this->db->query("UPDATE referrals SET status = '1', activated_time = '$time' WHERE id = '{$ref_check['id']}'");
																			
																			} else {
																				//Send both a message - (signed up previously)
																				$fail_inviter = "Thank you for referring your friends to ".$this->core->get_config_item('name','application').".
																				
																				One of the people you referred to the game has signed up however they were deemed to already have an account on ".$this->core->get_config_item('name','application')." therefore unforuntaly you will not receive a reward for this referral.
																				
																				We hope your future referrals are successful and can ensure that we will endavour to reward those who make a successful referral. If you have more friends to invite you can do so [url=".$this->core->get_config_item('base_url')."personal/referafriend/]here[/url].
																				
																				".$this->core->get_config_item('name','application')." Staff";
																				$fail_invitee = "Thank you for signing up to ".$this->core->get_config_item('name','application')." using a referral code.
																				
																				A successfull referral is rewarded however it has come to our attention that this is not your first account on ".$this->core->get_config_item('name','application')." therefore your referral was not successful.
																				
																				If you want to earn yourself a reward its quick and simple. Just invite your friends to come and play ".$this->core->get_config_item('name','application')." here.
																				
																				".$this->core->get_config_item('name','application')." Staff";
																				$this->gamecore->mail_notification($newuserid,$fail_invitee);
																				$this->gamecore->mail_notification($ref_check['playerid'],$fail_inviter);
																				$this->referral_failed($ref_check['id']);
																			}						
																		} else {
																			//Send both a message - (cannot refer yourself)
																			$fail_inviter = "Thank you for referring your friends to ".$this->core->get_config_item('name','application').".
																				
																			One of the people you referred to the game has signed up however they were deemed to be using the same IP address as this account therefore unforuntaly you will not receive a reward for this referral.
																				
																			We hope your future referrals are successful and can ensure that we will endavour to reward those who make a successful referral. If you have more friends to invite you can do so [url=".$this->core->get_config_item('base_url')."personal/referafriend/]here[/url].
																				
																			".$this->core->get_config_item('name','application')." Staff";
																			$fail_invitee = "Thank you for signing up to ".$this->core->get_config_item('name','application')." using a referral code.
																				
																				A successfull referral is rewarded however it has come to our attention that the user who referred you is using the same IP address as you therefore your referral was not successful.
																				
																				If you want to earn yourself a reward its quick and simple. Just invite your friends to come and play ".$this->core->get_config_item('name','application')." here.
																				
																				".$this->core->get_config_item('name','application')." Staff";
																			$this->gamecore->mail_notification($newuserid,$fail_invitee);
																			$this->gamecore->mail_notification($ref_check['playerid'],$fail_inviter);
																			$this->referral_failed($ref_check['id']);
																		}
																	}
																}
															}
															
															header("Location: ".$this->core->get_config_item('base_url')."home/signup/success/");
		
														} else {
															$data['fail'] = gettext("This email address has already been used.");
														}		
													} else {
														$data['fail'] = gettext("You must agree to the terms of service.");
													}
		/*
												} else {
													$data['fail'] = "You must enter the code correctly.";
												}
		*/
											} else {
												$data['fail'] = gettext("You must enter a valid anti script code.");
											}
										} else {
											$data['fail'] = gettext("You must select your age.");
										}
									} else {
										$data['fail'] = gettext("You must select your gender.");
									}
								} else {
									$data['fail'] = gettext("You must select a valid country.");
								}
							} else {
								$data['fail'] = gettext("You must enter a valid date of birth.");
							}
						} else {
							$data['fail'] = gettext("Your email address must match your confirmed email address.");
						}
					} else {
						$data['fail'] = gettext("You must re-enter your email address.");
					}
				} else {
					$data['fail'] = gettext("You must enter a valid email address.");
				}
				
				if(isset($data['fail'])) {
					$data['preload'] = $this->preLoadedData();
					$this->load->view('home/signup',$data);
				}
								
			} else {
				$data['preload'] = $this->preLoadedData();			
				$this->load->view("home/signup");
			}	
		
		}
		
		function success(){
			$this->load->view("home/signup_success");
		}
		
		function referral_failed($referral_id){
			$this->db->query("UPDATE referrals SET status = '4' WHERE id = '$referral_id'");
		}
		
		function preLoadedData(){
			$data['countries'] = $this->db->query("SELECT * FROM countries ORDER BY top DESC, value ASC")->rows();
			return $data;
		}
		
	}