<?php

	class Helpdesk extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
				
		function index(){
			
			$id = $_SESSION['id'];
			$yourtickets = $this->db->query("SELECT * FROM helpdesk_tickets WHERE playerid = '$id' AND status != '2' ORDER BY last_action DESC")->rows();
			$helpdesks = $this->db->query("SELECT h.characterid, c.username FROM helpdesk_members h INNER JOIN characters c ON c.id = h.characterid")->rows();
			$site_url = $this->core->get_config_item('base_url');
			$messages = $this->session->displaydata('messages');

			$data = array("yourtickets"=>$yourtickets,"site_url"=>$site_url,"messages"=>$messages,"helpdesks"=>$helpdesks);
			$this->load->view("helpdesk/tickets",$data);
			
		}
		
		function create(){
			
			$this->load->view("helpdesk/create");
			
		}
		
		function send(){
		
			if(isset($_POST['submitTicket'])) {
				$type = $_POST['subjectType'];
				$subject = $_POST['ticketSubject'];
				$body = $_POST['messageBox'];
				$antiscript = $_POST['antiScript'];
				$id = $_SESSION['id'];
				$site_url = $this->core->get_config_item('base_url');
				
				if($this->validation->is_numeric($type)){
					if($this->validation->checkdata($subject,7)){
						if($body) {
							if($this->validation->is_numeric($antiscript)){
						
								$bodyStripped = htmlentities(trim($_POST['messageBox']),ENT_QUOTES,'UTF-8');
								$fields['playerid']=$id;
								$fields['body']=$bodyStripped;
								$fields['type']=$type;
								$fields['subject']=$subject;
								$fields['last_action']=time();
								$fields['createdat']=time();
								$this->db->insert('helpdesk_tickets',$fields);
								$helpdeskid = mysql_insert_id();
								
								$helpdeskmembers = $this->db->query("SELECT characterid, username FROM helpdesk_members hm INNER JOIN characters c ON c.id = hm.characterid WHERE c.status = 'Alive'")->rows();
								$newticket = sprintf(gettext("There is a new Helpdesk Ticket which needs attention.<br><br><a href='%shelpdesk/view/%s/'>Click here to view ticket</a>"),$site_url,$helpdeskid);
								foreach($helpdeskmembers as $member) {
									$this->gamecore->mail_notification($member['characterid'],$newticket);
								}
														
								$data['success'] = gettext("Your ticket has been sent, you will receive a response shortly.");
								$this->session->set_displaydata('messages',$data);
								header("Location: ".$site_url."helpdesk/");

							} else {
								$data['fail'] = gettext("You must enter a valid anti script code.");
							}
						} else {
							$data['fail'] = gettext("You must enter your message.");
						}
					} else {
						$data['fail'] = gettext("You must enter a valid subject.");
					}
				} else {
					$data['fail'] = gettext("You must select a valid ticket type.");
				}
				
				if(isset($data['fail'])) {
					$this->load->view("helpdesk/create",$data);
				}
				
			} else {
				$this->load->view("helpdesk/create");
			}
			
		}
		
		function view($ticketid){
			
			$id = $_SESSION['id'];
			$site_url = $this->core->get_config_item('base_url');
			$segment = $this->uri->segment_array();
			
			if($this->validation->is_numeric($ticketid)) {
				$ticketdet = $this->db->query("SELECT * FROM helpdesk_tickets WHERE id = '$ticketid'")->row();
				if($ticketdet) {
					if($id == $ticketdet['playerid'] || $id == $ticketdet['taken_by']) {
								
						$this->load->model('hdfunctions');
						$ticketdet['status'] = $this->hdfunctions->ticketStatus($ticketdet['status']);										
						$replies = $this->db->query("SELECT h.ticketid as ticketid, h.playerid as playerid, h.body as body, h.createdat as createdat, c.username as username FROM helpdesk_responses h INNER JOIN characters c ON c.id = h.playerid WHERE h.ticketid = '$ticketid' ORDER BY h.id ASC")->rows();
	
						$messages = $this->session->displaydata('messages');
						if(isset($segment[4]) == 'close') {
							$closeticket = 1;
						} else {
							$closeticket = 0;
						}
						
						$data = array("theticket"=>$ticketdet,"replies"=>$replies,"site_url"=>$site_url,"messages"=>$messages,"closed"=>$closeticket,"id"=>$id);
						$this->load->view("helpdesk/view",$data);
					
					} else {
						header("Location: ".$site_url."helpdesk/");
					}
				} else {
					header("Location: ".$site_url."helpdesk/");
				}
			} else {
				header("Location: ".$site_url."helpdesk/");
			}
			
		}
		
		
		function reply(){
			
			if(isset($_POST['addReply'])) {
				$body = $_POST['ticketReply'];
				$ticketid = $_POST['ticketID'];
				$id = $_SESSION['id'];
				$site_url = $this->core->get_config_item('base_url');
				$time = time();
				
				if($this->validation->is_numeric($ticketid)){
					if($body) {
						$check = $this->db->query("SELECT * FROM helpdesk_tickets WHERE id = '$ticketid'")->row();
						if($check) {
							if($check['playerid'] == $id || $check['taken_by'] == $id) {
								
								$bodyStripped = htmlentities(trim($_POST['ticketReply']),ENT_QUOTES,'UTF-8');
								$fields['ticketid']=$ticketid;
								$fields['playerid']=$id;
								$fields['body']=$bodyStripped;
								$fields['createdat']=time();
								$this->db->insert('helpdesk_responses',$fields);
								
								$this->db->query("UPDATE helpdesk_tickets SET last_action = '$time' WHERE id = '$ticketid'");
								
								if($check['taken_by'] != 0){
									if($check['taken_by'] == $id) { $receiver = $check['playerid']; } else { $receiver = $check['taken_by']; }
									if($check['taken_by'] == $id) {
										$this->db->query("UPDATE helpdesk_members SET last_active = '$time'");
										if($check['replied'] == 0){
											$this->db->query("UPDATE helpdesk_tickets SET replied = '1' WHERE id = '$ticketid'");
										}
									}
									$newreply = sprintf(gettext("The following Helpdesk Ticket has received a new response:<br><br>Subject: %s<br><a href='%shelpdesk/view/%s/'>Click here to view ticket</a>"),$check['subject'],$site_url,$ticketid);
									$this->gamecore->mail_notification($receiver,$newreply);
								}
															
								$data['success'] = gettext("The message has been successfully posted.");
								$this->session->set_displaydata('messages',$data);
								header("Location: ".$site_url."helpdesk/view/".$ticketid."/");
						
							} else {
								header("Location: ".$site_url."helpdesk/");
							}
						} else {
							header("Location: ".$site_url."helpdesk/");
						}
					} else {
						$data['fail'] = gettext("You must enter your message.");
					}
				} else {
					header("Location: ".$site_url."helpdesk/");
				}
								
				if(isset($data['fail'])) {
					$this->session->set_displaydata('messages',$data);
					header("Location: ".$site_url."helpdesk/view/".$ticketid."/");
				}
				
			}
			
		}
		
		function close(){
		
			$site_url = $this->core->get_config_item('base_url');

			if(isset($_POST['closeTicket'])) {
				$ticketid = $_POST['ticketID'];
				$satisfaction = $_POST['satisfactoryAnswer'];
				$id = $_SESSION['id'];
				$time = time();
				$additional = '';
				
				if($this->validation->is_numeric($ticketid)){
					if($this->validation->is_numeric($satisfaction)){
						$check = $this->db->query("SELECT * FROM helpdesk_tickets WHERE id = '$ticketid'")->row();
						if($check) {
							if($check['status'] != 2) {
								if($check['playerid'] == $id) {
							
									$this->db->query("UPDATE helpdesk_members SET tickets_closed = tickets_closed + '1', thumbsup = thumbsup + '$satisfaction', tickets_open = tickets_open - '1' WHERE characterid = '{$check['taken_by']}'");
									$this->db->query("UPDATE helpdesk_tickets SET status = '2', last_action = '$time' WHERE id = '$ticketid'");
									
									if($satisfaction == 1) {
										$additional = gettext("<br><br>Your helpdesk responses were marked as satisfactory and therefore you have been given a thumbs up!");
									}
									
									$user = $this->gamecore->userinfo($check['playerid'],"username");
									$ticketclosed = sprintf(gettext("The Helpdesk Ticket \"%s\"has been closed by %s.%s"),$check['subject'],$user['username'],$additional);
									$this->gamecore->mail_notification($check['taken_by'],$ticketclosed);
									
									$data['success'] = gettext("The ticket has been closed.");
									$this->session->set_displaydata('messages',$data);
									header("Location: ".$site_url."helpdesk/");
		
								} else {
									header("Location: ".$site_url."helpdesk/");			
								}
							} else {
								$data['fail'] = gettext("This ticket has already been closed.");
							}
						} else {
							header("Location: ".$site_url."helpdesk/");			
						}
					} else {
						$data['fail'] = gettext("You must select whether the response to your ticket was satisfactory.");
					}				
				} else {
					header("Location: ".$site_url."helpdesk/");			
				}
			}
			
			if(isset($_POST['leaveTicket'])) {
				$ticketid = $_POST['ticketID'];
				header("Location: ".$site_url."helpdesk/view/".$ticketid."/");			
			}			
		
		
		}
		
						
	}