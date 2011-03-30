<?php

	class UsersOnline extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
		
		function index(){
			
			$this->load->library('redis');	
			//$this->redis->flushall();
			$time = time()-600;
			$time2 = time()-300;
				
			if(!$this->redis->get('playersonline')){
				$playersonline = $this->db->query("SELECT id, username FROM characters WHERE lastonline > '$time' AND status = 'Alive' AND helpdesk != '1' AND admin != '1'")->rows();
				$this->redis->setex('playersonline', 600, serialize($playersonline));
			}			
			if(!$this->redis->get('staffonline')){
				$staffonline = $this->db->query("SELECT id, username FROM characters WHERE lastonline > '$time2' AND status = 'Alive' AND (helpdesk = '1' OR admin = '1')")->rows();
				$this->redis->setex('staffonline', 300, serialize($staffonline));
			}
						
			$playersonline = unserialize($this->redis->get('playersonline'));
			$staffonline = unserialize($this->redis->get('staffonline'));
						
			$data = array("playersonline"=>$playersonline,"staffonline"=>$staffonline);
			$this->load->view("world/usersonline",$data);

		}
	
	}


?>