<?php

	class MyAccount extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
		
		function index(){
			
			$data['preload'] = $this->preLoadedData();
			$this->load->view("personal/myaccount",$data);

		}
		
		function preLoadedData(){
			$id = $_SESSION['id'];
			$data['hibernation_auth'] = $this->db->query("SELECT * FROM hibernation_auth WHERE playerid = '$id'")->row();
			$data['profile'] = $this->db->query("SELECT quote FROM profile WHERE playerid = '$id'")->row();
			$data['friends'] = $this->db->query("SELECT friendid, username FROM friends_list INNER JOIN characters ON characters.id = friends_list.friendid WHERE friends_list.playerid = '$id'")->rows();
			$data['ignoring'] = $this->db->query("SELECT ignoreid, username FROM ignore_list INNER JOIN characters ON characters.id = ignore_list.ignoreid WHERE ignore_list.playerid = '$id'")->rows();
			$data['site_url'] = $this->core->get_config_item('base_url');
			return $data;
		}

		function changepassword(){
			
			if(isset($_POST['changePassword'])){
				$current = $_POST['currentPassword'];
				$newpassword = $_POST['newPassword'];
				$confirmnew = $_POST['confirmNewPassword'];
				$id = $_SESSION['id'];
				
				$user = $this->gamecore->userinfo($id,"*");
				if(strlen($newpassword) >= 6){
					if(md5($current) == $user['password']) {
						if($newpassword == $confirmnew) {
							
							$newpasswordmd5 = md5($newpassword);
							$this->db->query("UPDATE characters SET password = '$newpasswordmd5' WHERE id = '$id'");
							$data['success'] = "You have successfully changed your password.";
					
						} else {
							$data['fail'] = "You must confirm your new password correctly.";
						}
					} else {
						$data['fail'] = "You current password is incorrect.";
					}
				} else {
					$data['fail'] = "Your new password must be at least 6 characters long.";
				}
			
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);

			} else {
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);			
			}
		
		}
		
		function addfriend(){
		
			if(isset($_POST['addFriend'])) {
				$friend = $_POST['friendName'];
				$id = $_SESSION['id'];
				
				if($this->validation->userName($friend)){
					$check = $this->db->query("SELECT * FROM characters WHERE username = '$friend'")->row();
					if($check){
						if($check['status'] != "Dead") {	
							$already = $this->db->query("SELECT * FROM friends_list WHERE playerid = '$id' AND friendid = '{$check['id']}'")->row();
							if(!$already) {
								if($check['id'] != $id) {
								
									$fields['playerid']=$id;
									$fields['friendid']=$check['id'];
									$this->db->insert('friends_list',$fields);
									$data['success'] = $check['username']." has been added to your friends list.";
					
								} else {
									$data['fail'] = "You cannot add yourself to your friends list.";
								}
							} else {
								$data['fail'] = "This user is already on your friends list.";
							}
						} else {
							$data['fail'] = "You cannot add users who are dead to your friends.";
						}						
					} else {
						$data['fail'] = "The username you entered was not found.";
					}
				} else {
					$data['fail'] = "You must enter a valid username.";
				}
		
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
		
			} else {
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
			}
		
		}
		
		function deletefriend(){
			$segment = $this->uri->segment_array();
			$friendid = $segment[4];
			$id = $_SESSION['id'];
			
			if($segment[3] == 'deletefriend') {
				if($this->validation->is_numeric($friendid)){
					$check = $this->gamecore->userinfo($friendid,"*");
					if($check) {
						$still_friend = $this->db->query("SELECT * FROM friends_list WHERE playerid = '$id' AND friendid = '$friendid'")->row();						if($still_friend) {

							$this->db->query("DELETE FROM friends_list WHERE id = '{$still_friend['id']}'");
							$data['success'] = $check['username']." has been removed from your friends list.";
													
						} else {
							$data['fail'] = "This user is not one of your friends.";
						}
					} else {
						$data['fail'] = "Invalid user.";
					}
				}
			}
			
			$data['preload'] = $this->preLoadedData();
			$this->load->view("personal/myaccount",$data);
					
		}
				
		function addignore(){
		
			if(isset($_POST['addIgnore'])) {
				$ignore = $_POST['ignoreName'];
				$id = $_SESSION['id'];
				
				if($this->validation->userName($ignore)){
					$check = $this->db->query("SELECT * FROM characters WHERE username = '$ignore'")->row();
					if($check){
						if($check['status'] != "Dead") {	
							$already = $this->db->query("SELECT * FROM ignore_list WHERE playerid = '$id' AND ignoreid = '{$check['id']}'")->row();
							if(!$already) {
								if($check['id'] != $id) {
							
									$fields['playerid']=$id;
									$fields['ignoreid']=$check['id'];
									$this->db->insert('ignore_list',$fields);
									$data['success'] = $check['username']." has been added to your ignore list.";
					
								} else {
									$data['fail'] = "You cannot add yourself to your ignore list.";
								}
							} else {
								$data['fail'] = "This user is already on your ignore list.";
							}
						} else {
							$data['fail'] = "You cannot add users who are dead to your ignore list.";
						}						
					} else {
						$data['fail'] = "The username you entered was not found.";
					}
				} else {
					$data['fail'] = "You must enter a valid username.";
				}
		
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
		
			} else {
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
			}
		
		}

		function deleteignore(){
			$segment = $this->uri->segment_array();
			$ignoreid = $segment[4];
			$id = $_SESSION['id'];
			
			if($segment[3] == 'deleteignore') {
				if($this->validation->is_numeric($ignoreid)){
					$check = $this->gamecore->userinfo($ignoreid,"*");
					if($check) {
						$still_ignore = $this->db->query("SELECT * FROM ignore_list WHERE playerid = '$id' AND ignoreid = '$ignoreid'")->row();						if($still_ignore) {

							$this->db->query("DELETE FROM ignore_list WHERE id = '{$still_ignore['id']}'");
							$data['success'] = $check['username']." has been removed from your ignore list.";
													
						} else {
							$data['fail'] = "This user is not on your ignore list.";
						}
					} else {
						$data['fail'] = "Invalid user.";
					}
				}
			}
			
			$data['preload'] = $this->preLoadedData();
			$this->load->view("personal/myaccount",$data);
					
		}
		
		function takeabreak(){
		
			if(isset($_POST['submitHibernation'])) {
				$days = $_POST['hibernationDays'];
				$id = $_SESSION['id'];

				if($this->validation->is_numeric($days)){		
					if($days >= 1) {
						if($days <= 28) {
							$check = $this->db->query("SELECT * FROM hibernation_auth WHERE playerid = '$id'")->row();
							if(!$check) {
								$previous_hib = $this->db->query("SELECT * FROM hibernation WHERE playerid = '$id'")->row();
								if(!$previous_hib){
								
									$code = rand(1111,9999);
									$fields['playerid']=$id;
									$fields['code']=$code;
									$fields['days']=$days;
									$fields['time']=time();
									$this->db->insert('hibernation_auth',$fields);
									$user = $this->gamecore->userinfo($id,"username, email");

									//Send the email
									$this->load->library('mail');
									$this->mail
		            				    ->setTo($user['email'],$user['username'])
						                ->setSubject("Take a break confirmation for ".$user['username']." on ".$this->core->get_config_item('name','application'))
						                ->setPlain("Hi ".$user['username'].",

You have requested to put your account into Hibernation for ".$check['days']." days.
Use the confirmation code below to confirm the take a break request.

Confirmation Code: ".$code."

Hibernation requests are deleted once every 24 hours. If you take longer than 24 hours you will need to repeat the previous step.

".$this->core->get_config_item('name','application')." Staff")
						                ->setHtml("<h2>Confirm Take a Break request on ".$this->core->get_config_item('name','application')."</h2>					                
Hi ".$user['username'].",<br><br>
You have requested to put your account into Hibernation for ".$check['days']." days.<br>
Use the confirmation code below to confirm the take a break request.<br><br>
Confirmation Code: ".$code."<br><br>
Hibernation requests are deleted once every 24 hours. If you take longer than 24 hours you will need to repeat the previous step.<br><br>
".$this->core->get_config_item('name','application')." Staff")
						                ->send();
									$data['success'] = "Step 1 Complete - To confirm you own the account an email with the confirmation code has been sent to the email address for this account. Retrieve the code and paste it in the box below.";
								
								} else {
									$data['fail'] = "You have already been in hibernation, you can only enter hibernation once per account.";
								}
							} else {
								$data['fail'] = "You have already requested to enter hibernation.";
							}
						} else {
							$data['fail'] = "You cannot go into hibernation for longer than 28 days.";
						}
					} else {
						$data['fail'] = "You must go into hibernation for at least 1 day.";
					}
				} else {
					$data['fail'] = "You must enter a valid number.";
				}

				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
		
			} else {
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
			}
		
		}
		
		function takeabreakconfirm(){
		
			if(isset($_POST['submitHibernationConfirm'])){
				$code = $_POST['confirmationCode'];
				$id = $_SESSION['id'];
			
				if($this->validation->is_numeric($code)) {
					$check = $this->db->query("SELECT * FROM hibernation_auth WHERE playerid = '$id'")->row();
					if($check['code'] == $code) {
						$previous_hib = $this->db->query("SELECT * FROM hibernation WHERE playerid = '$id'")->row();
						if(!$previous_hib){
					
							$fields['playerid']=$id;
							$fields['hibernation_time']=(time()+($check['days']*86400));
							$this->db->insert('hibernation',$fields);
							$this->db->query("DELETE FROM hibernation_auth WHERE playerid = '$id'");
							header("Location: ".$this->core->get_config_item('base_url')."home/hibernation/");
						
						} else {
							$data['fail'] = "You have already been in hibernation, you can only enter hibernation once per account.";
						}					
					} else {
						$data['fail'] = "The confirmation code you entered was incorrect, try again.";
					}			
				} else {
					$data['fail'] = "You must enter a valid confirmation code.";
				}
				
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
			
			} else {
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
			}
		
		}
		
		function profilequote(){
		
			if(isset($_POST['updateQuote'])) {
				$quote = strip_tags($_POST['profileQuote']);
				$id = $_SESSION['id'];
			
				if($quote) {
			
					$this->db->query("UPDATE profile SET quote = '$quote' WHERE playerid = '$id'");
					$data['success'] = "Profile quote updated.";
			
				} else {
					$data['fail'] = "You did not enter a profile quote.";
				}
				
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
			
			} else {
				$data['preload'] = $this->preLoadedData();
				$this->load->view("personal/myaccount",$data);
			}
		
		}

		
	}