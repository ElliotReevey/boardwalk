<?php

	class Inbox extends Controller{
	
		function __construct(){
			
			parent::Controller();
			$this->load->library('session');
			$this->load->library('validation');
			$this->load->helper('form');
			$this->load->model('gamecore');
			$this->gamecore->loginchecker();
		
		}
				
		function index(){
			$this->load->view("404");
		}

		function view(){
			$id = $_SESSION['id'];
			
			$res = $this->db->query("SELECT m.mid, m.seq, m.created_on, m.created_by, m.body, r.status FROM messages_attached r
INNER JOIN messages m ON m.mid=r.mid and m.seq=r.seq
WHERE r.uid='$id' AND r.status IN ('A', 'N')
AND r.seq=(SELECT MAX(rr.seq) FROM messages_attached rr WHERE rr.mid=m.mid AND rr.status in ('A', 'N'))
AND IF (m.seq=1 and m.created_by='$id', 1=0, 1=1)
ORDER BY m.created_on DESC")->rows();
			
			// initialise array
			$inbox = array();
			$unread = 0;
			
			foreach($res as $result):
			
				// Up here were going to fetch the usernames.
				
				$usernames =  $this->db->query("SELECT DISTINCT(r.uid) as uid, p.avatar, u.username from messages_attached r 
		INNER JOIN characters u on u.id=r.uid
		INNER JOIN messages m on m.mid=r.mid
		INNER JOIN profile p on p.playerid=u.id 
		WHERE m.created_by = r.uid AND r.mid='{$result['mid']}' AND r.uid != '$id' ORDER BY m.created_on DESC LIMIT 4")->rows();
		
				// We will array the user with an id.
				$users = array();
				foreach($usernames as $u):
					
					$users[] = array(
						"username" => $u['username'],
						"uid" => $u['uid'],
						"avatar" => $u['avatar']
					);
					
				endforeach;
			
				$inbox[$result['mid']] = array(
					"users" => $users,
					"mid" => $result['mid'],
					"body" => $result['body'],
					"created_on" => $result['created_on'],
					"created_by" => $result['created_by'],
					"status" => $result['status']
				);
				
				if($result['status'] == 'N') {
					$unread++;
				}
			endforeach;
			
			$totalinbox = count($inbox);
			
			$messages = $this->session->displaydata('messages');
			$page = array("inbox"=>$inbox,"messages"=>$messages,"totalinbox"=>$totalinbox,"unread"=>$unread);
			$page['site_url'] = $this->core->get_config_item('base_url');
			
			$this->load->view("communication/inbox",$page);
		}
		
		function conversation($convoid){
			$id = $_SESSION['id'];
			$site_url = $this->core->get_config_item('base_url');

			$res = $this->db->query("SELECT m.mid, m.seq, m.created_on, m.created_by, m.body, r.status, p.avatar, u.username FROM messages_attached r
INNER JOIN messages m ON m.mid=r.mid AND m.seq=r.seq
INNER JOIN profile p ON p.playerid=m.created_by
INNER JOIN characters u ON u.id=m.created_by
WHERE r.uid='$id' AND m.mid='$convoid' AND r.status in ('A', 'N') ORDER BY m.seq ASC")->rows();
			
			if(count($res)){
			
				$conversation = array();
				foreach($res as $result):
					$conversation[] = array(
						"mid" => $result['mid'],
						"body" => $result['body'],
						"created_on" => $result['created_on'],
						"username" => $result['username'],
						"created_by" => $result['created_by'],
						"avatar" => $result['avatar'],
						"status" => $result['status']
					);
				endforeach;

				$usernames = $this->db->query("SELECT DISTINCT(uid) AS uid, u.username FROM messages_attached m INNER JOIN characters u on u.id=m.uid WHERE m.mid='{$result['mid']}'")->rows();
				
				$users = array();
				foreach($usernames as $user):
					$users[] = array(
						"username" => $user['username'],
						"uid" => $user['uid'],
					);
				endforeach;
								
				$this->db->query("UPDATE messages_attached SET status='A' WHERE status='N' AND mid='{$result['mid']}' AND uid='$id'");
				
				$messages = $this->session->displaydata('messages');
				$page = array("conversation"=>$conversation,"involved"=>$users,"messages"=>$messages);			
				$page['site_url'] = $this->core->get_config_item('base_url');
															
				$this->load->view("communication/conversation",$page);
				
			} else {
				header("Location: ".$site_url."inbox/view");
			}
		}
		
		function sent(){
			$id = $_SESSION['id'];
			
			$res = $this->db->query("SELECT m.mid, m.seq, m.created_on, m.created_by, m.body, r.status FROM messages_attached r
INNER JOIN messages m ON m.mid=r.mid AND m.seq=r.seq
WHERE m.created_by='$id' AND r.uid='$id'
AND r.status != 'D'
AND m.seq=(select max(rr.seq) FROM messages_attached rr WHERE rr.mid=m.mid AND rr.status != 'D' AND rr.uid='$id')
ORDER BY created_on DESC")->rows();
			
			// initialise array
			$sent = array();
			
			foreach($res as $result):
			
				// Up here were going to fetch the usernames.
				
				$usernames =  $this->db->query("SELECT DISTINCT(r.uid) as uid, p.avatar, u.username from messages_attached r 
		INNER JOIN characters u on u.id=r.uid
		INNER JOIN messages m on m.mid=r.mid
		INNER JOIN profile p on p.playerid=u.id 
		WHERE r.mid='{$result['mid']}' AND r.uid != '$id' ORDER BY m.created_on DESC LIMIT 4")->rows();
				
				// We will array the user with an id.
				$users = array();
				foreach($usernames as $u):
					$users[] = array(
						"username" => $u['username'],
						"uid" => $u['uid'],
						"avatar" => $u['avatar']
					);
					
				endforeach;
			
				$sent[$result['mid']] = array(
					"users" => $users,
					"mid" => $result['mid'],
					"body" => $result['body'],
					"created_on" => $result['created_on'],
					"created_by" => $result['created_by'],
					"status" => $result['status']
				);
			endforeach;

			$page = array("sent"=>$sent);
			$page['site_url'] = $this->core->get_config_item('base_url');
			
			$this->load->view("communication/sent",$page);
		
		}
		
		function delete($mid){
			$id = $_SESSION['id'];
			$site_url = $this->core->get_config_item('base_url');
			
			
			if(is_numeric($mid)){
				//check they are apart of this message
				$check = $this->db->query("SELECT * FROM messages_attached WHERE mid = '$mid' AND uid = '$id' AND status != 'D'")->row();
				if($check) {
					
					$totaldelete = $this->db->query("SELECT * FROM messages_attached WHERE mid = '$mid' AND uid != '$id' AND status != 'D'")->row();
					if($totaldelete){
						$this->db->query("UPDATE messages_attached SET status = 'D' WHERE mid = '$mid' AND uid = '$id'");				
					} else {
						$this->db->query("DELETE FROM messages_attached WHERE mid = '$mid'");
						$this->db->query("DELETE FROM messages WHERE mid = '$mid'");
					}
					
					$data['success'] = gettext("Message deleted");
					header("Location: ".$site_url."inbox/view/");
		
				} else {
					header("Location: ".$site_url."inbox/view/");
				}
			} else {
				header("Location: ".$site_url."inbox/view/");
			}
			
			$this->session->set_displaydata('messages',$data);

		}
		
		function compose(){
			$this->load->view("communication/compose");
		}
						
	}