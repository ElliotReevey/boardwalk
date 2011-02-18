<?php

	class ChooseCharacter extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->outsidechecker();
			
		}
		
		function index(){
			
			$this->load->view("home/choosecharacter");

		}

		function submit(){
		
			if(isset($_POST['submitButton'])) {
				$username = $_POST['characterName'];
				$gender = $_POST['genderType'];
				$location = $_POST['startLocation'];
				$userid = $_SESSION['logincheck'];
				
				$check = $this->db->query("SELECT username FROM users WHERE id = '$userid'")->row();
				if($check['username'] == '') {
					if($this->validation->characterName($username)){				
						if($this->validation->alpha_loose($gender)) {
							if($this->validation->alpha_loose($location)) {
								$usernamecheck = $this->db->query("SELECT * FROM users WHERE username = '$username'")->row();
								if(!$usernamecheck) {
									
									$_SESSION['id'] = $userid;				
									//Update the user
									$this->db->query("UPDATE users SET username = '$username', chargender = '$gender', location = '$location' WHERE id = '$userid'");
									header("Location: ".$this->core->get_config_item('base_url')."main");

	
								} else {
									$data['fail'] = gettext("This username has already been taken, please try again.");
								}
							} else {
								$data['fail'] = gettext("You must choose a starting location.");
							}					
						} else {
							$data['fail'] = gettext("You must select a gender for your character.");
						}
					} else {
						$data['fail'] = sprintf(gettext("%s"),$this->validation->retErrors());
					}
				} else {
					header("Location: ".$this->core->get_config_item('base_url')."main");
				}
				
				if(isset($data['fail'])) {
					$this->load->view('home/choosecharacter',$data);
				}
							
			} else {
				$this->load->view("home/index");
			}
		
		
		}
		
	}