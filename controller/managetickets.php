<?php

	class ManageTickets extends Controller{
	
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
			$site_url = $this->core->get_config_item('base_url');
			
			$check = $this->db->query("SELECT * FROM helpdesk_members WHERE characterid = '$id'");
			
			if($check) {			
				$opentickets = $this->db->query("SELECT h.id as id, h.createdat as createdat, h.type as type, h.subject as subject, h.status as status, c.username as username FROM helpdesk_tickets h INNER JOIN characters c ON c.id = h.playerid WHERE h.status = '0' AND h.taken_by = '0' ORDER BY h.id DESC")->rows();
				$yourtickets = $this->db->query("SELECT h.id as id, h.last_action as lastaction, h.type as type, h.subject as subject, h.status as status, h.replied as replied, c.username as username FROM helpdesk_tickets h INNER JOIN characters c ON c.id = h.playerid WHERE h.status = '1' AND h.taken_by = '$id' ORDER BY h.id DESC")->rows();
				$site_url = $this->core->get_config_item('base_url');

				$messages = $this->session->displaydata('messages');
				$data = array("opentickets"=>$opentickets,"yourtickets"=>$yourtickets,"site_url"=>$site_url,"messages"=>$messages);
				$this->load->view("helpdesk/managetickets",$data);
			} else {
				header("Location: ".$site_url."main/");
			}
		}
		
		function taketicket($ticketid){
			$id = $_SESSION['id'];
			$hdmember = $this->db->query("SELECT * FROM helpdesk_members WHERE characterid = '$id'")->row();
			$site_url = $this->core->get_config_item('base_url');

			if($hdmember) {
				if($this->validation->is_numeric($ticketid)) {
					$check = $this->db->query("SELECT * FROM helpdesk_tickets WHERE id = '$ticketid'")->row();
					if($check) {
						if($check['status'] == 0) {
							if($check['playerid'] != $id) {
						
								$time = time();
								$this->db->query("UPDATE helpdesk_tickets SET status = '1', taken_by = '$id', last_action = '$time' WHERE id = '$ticketid'");
								$this->db->query("UPDATE helpdesk_members SET tickets_open = tickets_open + '1', tickets_taken = tickets_taken + '1', last_active = '$time' WHERE characterid = '$id'");				
								
								$data['success'] = gettext("You have successfully taken the ticket.");
								$this->session->set_displaydata('messages',$data);
								header("Location: ".$site_url."helpdesk/view/".$ticketid."/");
						
							} else {
								$data['fail'] = gettext("You cannot take your own tickets.");
							}
						} else {
							$data['fail'] = gettext("This ticket is no longer open.");
						}
					} else {
						$data['fail'] = gettext("This ticket is not valid.");
					}
				} else {
					$data['fail'] = gettext("This ticket is not valid.");
				}
			} else {
				header("Location: ".$site_url."main/");
			}
			
			if(isset($data['fail'])){
				$this->session->set_displaydata('messages',$data);
				header("Location: ".$site_url."managetickets/");
			}
		}
		
		function passback($ticketid){
			$id = $_SESSION['id'];
			$hdmember = $this->db->query("SELECT * FROM helpdesk_members WHERE characterid = '$id'")->row();
			$site_url = $this->core->get_config_item('base_url');

			if($hdmember) {
				if($this->validation->is_numeric($ticketid)) {
					$check = $this->db->query("SELECT * FROM helpdesk_tickets WHERE id = '$ticketid'")->row();
					if($check) {
						if($check['status'] == 1) {
							if($check['taken_by'] == $id) {
							
									$time = time();
									$this->db->query("UPDATE helpdesk_tickets SET status = '0', taken_by = '0', last_action = '$time' WHERE id = '$ticketid'");
									$this->db->query("UPDATE helpdesk_members SET tickets_open = tickets_open - '1', tickets_taken = tickets_taken - '1', last_active = '$time' WHERE characterid = '$id'");				
									
									$data['success'] = gettext("You have successfully passed the ticket back.");
									$this->session->set_displaydata('messages',$data);
									header("Location: ".$site_url."managetickets/");
							
							} else {
								$data['fail'] = gettext("You cannot pass back a ticket which you haven't taken.");
							}
						} else {
							$data['fail'] = gettext("This ticket is no longer open.");
						}
					} else {
						$data['fail'] = gettext("This ticket is not valid.");
					}
				} else {
					$data['fail'] = gettext("This ticket is not valid.");
				}
			} else {
				header("Location: ".$site_url."main/");
			}
			
			if(isset($data['fail'])){
				$this->session->set_displaydata('messages',$data);
				header("Location: ".$site_url."managetickets/");
			}
		}
		
						
	}