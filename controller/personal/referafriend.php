<?php

	class ReferAFriend extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
		
		function index(){
						
			$this->load->view("personal/referafriend");

		}

		function submit(){
		
			if(isset($_POST['submitButton'])) {
				$email = $_POST['emailAddress'];
				$id = $_SESSION['id'];
				$user = $this->gamecore->userinfo($id,"*");
				
				if($this->validation->valid_email($email)) {
					$check_existing = $this->db->query("SELECT * FROM users WHERE email = '$email'")->row();
					if(!$check_existing) {
						$check_already = $this->db->query("SELECT * FROM referrals WHERE email = '$email'")->row();			
						if(!$check_already) {
							
							//Generate code
							$this->load->library('utilities');
							$code = $this->utilities->generateToken(10);
							
							//Insert into database
							$fields['playerid']=$id;
							$fields['email']=$email;
							$fields['ip']=$_SERVER["REMOTE_ADDR"];
							$fields['code']=$code;
							$this->db->insert('referrals',$fields);
							
							//Send the email
							$this->load->library('mail');
							$this->mail
            				    ->setTo($email,'')
				                ->setSubject("An invitation to ".$this->core->get_config_item('name','application'))
				                ->setPlain("Hey,

I thought you might be interested in playing ".$this->core->get_config_item('name','application')." - ".$this->core->get_config_item('base_url')."

By signing up using the referral code below we will both receive rewards to help us in the game. Once you have signed up you can refer people to and earn some great rewards.

Its quick and easy to register:

- Click the link - ".$this->core->get_config_item('base_url')."/home/signup
- Enter $code into the referral box
- Sign in to ".$this->core->get_config_item('name','application')."

My username is ".$user['username']." so be sure to contact me when you sign up, ill be waiting for you in game!

".$this->core->get_config_item('name','application')." Staff
".$this->core->get_config_item('base_url'))
				                ->setHtml("<h2>Inviation to ".$this->core->get_config_item('name','application')."</h2>
				                Hey,<br><br>
I thought you might be interested in playing ".$this->core->get_config_item('name','application')." - ".$this->core->get_config_item('base_url')."<br><br>
By signing up using the referral code below we will both receive rewards to help us in the game. Once you have signed up you can refer people to and earn some great rewards.<br><br>
Its quick and easy to register:<br><br>
- Click the link - ".$this->core->get_config_item('base_url')."/home/signup<br>
- Enter <b>$code</b> into the referral box<br>
- Sign in to ".$this->core->get_config_item('name','application')."<br><br>
My username is <b>".$user['username']."</b> so be sure to contact me when you sign up, ill be waiting for you in game!<br><br>
".$this->core->get_config_item('name','application')." Staff<br>
".$this->core->get_config_item('base_url'))
				                ->send();
						
							$data['success'] = "Your invite has been sent!";

						} else {
							$data['fail'] = "This user has already been referred.";
						}
					} else {
						$data['fail'] = "This user is already registered.";
					}
				} else {
					$data['fail'] = "You must enter a valid email address.";
				}

				$this->load->view("personal/referafriend",$data);

			}
		
		}
		
	}