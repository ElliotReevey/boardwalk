<?php

	class Forum extends Model{
				
		function topics($forumid){
			
			if($this->validation->is_numeric($forumid)){
				$id = $_SESSION['id'];
				$site_url = $this->core->get_config_item('base_url');
				if($forumid == 3){
					$userinfo = $this->db->query("SELECT crew FROM characters WHERE id = '$id'")->row();
					$crewid = $userinfo['crew'];
				}
				$foruminfo = $this->db->query("SELECT name,slug FROM forums WHERE id = '$forumid'")->row();
				$data['name'] = $foruminfo['name'];
				$data['slug'] = $foruminfo['slug'];
				$data['site_url'] = $site_url;
				$data['nocrew'] = 0;
				
				if(!isset($crewid)){
					$data['topics'] = $this->db->query("SELECT t.id, t.topicname, t.body, t.creator, t.createdat, t.replies, t.lastposter, t.lastpost, t.likes, u.username AS creatorUsername, uu.username AS lastUsername FROM forum_topics t INNER JOIN characters u ON u.id = t.creator LEFT JOIN characters uu ON uu.id = t.lastposter WHERE forumid = '$forumid' ORDER BY t.lastpost DESC")->rows();
				} else {
					if($userinfo['crew'] != 0) {
						$data['topics'] = $this->db->query("SELECT t.id, t.topicname, t.body, t.creator, t.createdat, t.replies, t.lastposter, t.lastpost, t.likes, u.username AS creatorUsername, uu.username AS lastUsername FROM forum_topics t INNER JOIN characters u ON u.id = t.creator LEFT JOIN characters uu ON uu.id = t.lastposter WHERE forumid = '$forumid' AND crewid = '$crewid' ORDER BY t.lastpost DESC")->rows();
					} else {
						$data['nocrew'] = 1;
					}			
				}
				
				$data['forumtype'] = $forumid;
				$data['forumslug'] = $this->db->query("SELECT slug FROM forums WHERE id = '$forumid'")->get();
				return $data;
			} else {
				header("Location: ".$site_url);
			}
		
		}
		
		function posts($topicid,$forumtype){
		
			$id = $_SESSION['id'];
			$site_url = $this->core->get_config_item('base_url');
			
			if($this->validation->is_numeric($topicid)){
				$exists = $this->db->query("SELECT * FROM forum_topics WHERE id = '$topicid'")->row();
				if($exists) {
					$checkcrew = $this->db->query("SELECT crew FROM characters WHERE id = '$id'")->row();
					if($exists['crewid'] == $checkcrew['crew'] || $exists['crewid'] == 0){
					
						//query to get posts for that topic
						$data['topic'] = $this->db->query("SELECT t.id as topicid, t.topicname, t.body, t.creator, t.createdat, t.likes, u.username, p.avatar, p.signature, u.posts FROM forum_topics t INNER JOIN characters u ON u.id = t.creator INNER JOIN profile p ON p.playerid = t.creator WHERE t.id = '$topicid'")->row();
						$data['posts'] = $this->db->query("SELECT p.id as postid, p.body, p.creator, p.createdat, p.likes, u.username, pr.avatar, pr.signature, u.posts FROM forum_posts p INNER JOIN characters u ON u.id = p.creator INNER JOIN profile pr ON pr.playerid = p.creator WHERE p.topicid = '$topicid' ORDER BY p.createdat ASC")->rows();
					
					} else {
						header("Location: ".$site_url."forum/".$forumtype."/");
					}
				} else {
					header("Location: ".$site_url."forum/".$forumtype."/");
				}
			} else {
				header("Location: ".$site_url."forum/".$forumtype."/");
			}
			
			$data['topicid'] = $topicid;
			$data['site_url'] = $site_url;
			$data['forumslug'] = $this->db->query("SELECT slug FROM forums WHERE id = '{$exists['forumid']}'")->get();
			return $data;
		}
		
		function create($forumtype){
			
			if($this->validation->is_numeric($forumtype)){

				$id = $_SESSION['id'];
				$site_url = $this->core->get_config_item('base_url');
				$data['forumslug'] = $this->db->query("SELECT slug FROM forums WHERE id = '$forumtype'")->get();
				$checkcrew = $this->db->query("SELECT crew FROM characters WHERE id = '$id'")->get();
				if($checkcrew['crew'] || $forumtype != 3){
				
					if(isset($_POST['postTopic'])) {
						$topictitle = $_POST['topicTitle'];
						$topicbody = $_POST['topicBody'];
						
						if($this->validation->checkdata($topictitle,7)){
							if($topicbody){
		
								$bodyStripped = htmlentities(trim($_POST['topicBody']),ENT_QUOTES,'UTF-8');
								$fields['forumid']=$forumtype;
								if($forumtype == 3) {
								$fields['crewid']=$checkcrew['crew'];
								}
								$fields['topicname']=$topictitle;
								$fields['body']=$bodyStripped;
								$fields['creator']=$id;
								$fields['createdat']=time();
								$fields['lastposter']=$id;
								$fields['lastpost']=time();
								$this->db->insert('forum_topics',$fields);
								
								$this->db->query("UPDATE characters SET posts = posts + 1 WHERE id = '$id'");
								
								$data['success'] = gettext("You have successfully created a new topic.");
								
								$this->session->set_displaydata('messages',$data);
								header("Location: ".$site_url."forum/".$data['forumslug']."/");
								exit();
	
							} else {
								$data['fail'] = gettext("Your topic cannot be blank.");
							}
						} else {
							$data['fail'] = gettext("You must enter a valid topic title.");
						}
					}
					
				} else {
					header("Location: ".$site_url."forum/".$forumtype."/");
				}
				
				return $data;
			
			} else {
				header("Location: ".$site_url."forum/".$forumtype."/");
			}
			
		}
		
		function newpost($topicid,$forumtype){
		
			if($this->validation->is_numeric($topicid)){
		
				if(isset($_POST['postReply'])){
					$body = $_POST['topicBody'];
					$id = $_SESSION['id'];
					$site_url = $this->core->get_config_item('base_url');
					$inside = 0;
					
					if($this->validation->is_numeric($topicid)) {
						$topicinfo = $this->db->query("SELECT crewid, slug, creator, topicname FROM forum_topics INNER JOIN forums ON forums.id = forum_topics.forumid WHERE forum_topics.id = '$topicid'")->row();
						$userinfo = $this->db->query("SELECT username, crew FROM characters WHERE id = '$id'")->row();
						if($topicinfo['crewid'] == 0 || ($userinfo['crew'] == $topicinfo['crewid'])){
							$inside = 1;
							if($body) {
								
								$posttime = time();
								$bodyStripped = htmlentities(trim($body),ENT_QUOTES,'UTF-8');
								
								$this->db->query("UPDATE forum_topics SET replies = replies + 1, lastposter = '$id', lastpost = '$posttime' WHERE id = '$topicid'");
								$fields['topicid']=$topicid;
								$fields['body']=$bodyStripped;
								$fields['creator']=$id;
								$fields['createdat']=time();
								$this->db->insert('forum_posts',$fields);
								
								$creatorinfo = $this->db->query("SELECT username FROM characters WHERE id = '{$topicinfo['creator']}'")->row();
								$newpost = sprintf(gettext("Hey %s,<br><br>%s has just left a new post on forum topic \"%s\".<br><br>The post:<br><br>%s<br><br><a href='%sforum/%s/view/%s/'>View the forum topic >></a>"),$creatorinfo['username'],$userinfo['username'],$topicinfo['topicname'],$bodyStripped,$site_url,$topicinfo['slug'],$topicid);
								$this->load->model('gamecore');
								$this->gamecore->mail_notification($topicinfo['creator'],$newpost);
								
								$data['success'] = gettext("Post added.");
							
							} else {
								$data['fail'] = gettext("Your reply cannot be blank.");
							}
						} else {
							header("Location: ".$site_url."forum/".$forumtype."/");						
						}
					} else {
						header("Location: ".$site_url."forum/".$forumtype."/");			
					}
					
					if($inside) {
						$this->session->set_displaydata('messages',$data);
						header("Location: ".$site_url."forum/".$forumtype."/view/".$topicid."/");
						exit();
					}
				}
			
			} else {
				header("Location: ".$site_url."forum/".$forumtype."/");			
			}
		}
		
		function like($topicid,$forumtype){
			
			$inside = 0;
			$site_url = $this->core->get_config_item('base_url');
			$id = $_SESSION['id'];
			
			if($this->validation->is_numeric($topicid)) {
				$topicinfo = $this->db->query("SELECT crewid, slug, topicname, creator FROM forum_topics INNER JOIN forums ON forums.id = forum_topics.forumid WHERE forum_topics.id = '$topicid'")->row();
				$userinfo = $this->db->query("SELECT crew, username FROM characters WHERE id = '$id'")->row();
				if($topicinfo['crewid'] == 0 || ($userinfo['crew'] == $topicinfo['crewid'])){
					$inside = 1;
					$check = $this->db->query("SELECT * FROM forum_topic_likes WHERE topicid = '$topicid' AND playerid = '$id'")->row();
					if(!$check) {
						if($id != $topicinfo['creator']) {
						
							$this->db->query("UPDATE forum_topics SET likes = likes + 1 WHERE id = '$topicid'");
							$fields['topicid']=$topicid;
							$fields['playerid']=$id;
							$this->db->insert('forum_topic_likes',$fields);
							
							$data['success'] = sprintf(gettext("You have liked the topic: %s"),$topicinfo['topicname']);
							
							$creatorinfo = $this->db->query("SELECT username FROM characters WHERE id = '{$topicinfo['creator']}'")->row();
							$newpost = sprintf(gettext("Hey %s,<br><br>%s liked forum topic \"%s\".<br><br><a href='%sforum/%s/view/%s/'>View the forum topic >></a>"),$creatorinfo['username'],$userinfo['username'],$topicinfo['topicname'],$site_url,$topicinfo['slug'],$topicid);
							$this->load->model('gamecore');
							$this->gamecore->mail_notification($topicinfo['creator'],$newpost);

						} else {
							$data['fail'] = gettext("You cannot like your own topics.");
						}
					} else {
						$data['fail'] = gettext("You have already liked this topic.");
					}
				} else {
					header("Location: ".$site_url."forum/".$forumtype."/");				
				}
			} else {
				header("Location: ".$site_url."forum/".$forumtype."/");				
			}
			
			return $data;
		}
		
		function postlike($postid,$forumtype){
		
			$inside = 0;
			$site_url = $this->core->get_config_item('base_url');
			$id = $_SESSION['id'];
		
			if($this->validation->is_numeric($postid)) {
				$postinfo = $this->db->query("SELECT topicid, body, creator FROM forum_posts WHERE id = '$postid'")->row();
				$topicinfo = $this->db->query("SELECT crewid, slug, topicname, creator FROM forum_topics INNER JOIN forums ON forums.id = forum_topics.forumid WHERE forum_topics.id = '{$postinfo['topicid']}'")->row();
				$userinfo = $this->db->query("SELECT crew, username FROM characters WHERE id = '$id'")->row();
				if($topicinfo['crewid'] == 0 || ($userinfo['crew'] == $topicinfo['crewid'])){
					$inside = 1;
					$check = $this->db->query("SELECT * FROM forum_post_likes WHERE postid = '$postid' AND playerid = '$id'")->row();
					if(!$check) {
						if($id != $postinfo['creator']) {
					
							$this->db->query("UPDATE forum_posts SET likes = likes + 1 WHERE id = '$postid'");
							$fields['postid']=$postid;
							$fields['playerid']=$id;
							$this->db->insert('forum_post_likes',$fields);
							
							$data['success'] = gettext("You have liked the post.");
	
							$strippedpost = htmlentities(trim($postinfo['body']),ENT_QUOTES,'UTF-8');
							$creatorinfo = $this->db->query("SELECT username FROM characters WHERE id = '{$topicinfo['creator']}'")->row();
							$newpost = sprintf(gettext("Hey %s,<br><br>%s liked your post in the forum topic \"%s\".<br><br>The post:<br><br>%s<br><br><a href='%sforum/%s/view/%s/'>View the forum topic >></a>"),$creatorinfo['username'],$userinfo['username'],$topicinfo['topicname'],$strippedpost,$site_url,$topicinfo['slug'],$postinfo['topicid']);
							$this->load->model('gamecore');
							$this->gamecore->mail_notification($topicinfo['creator'],$newpost);
					
						} else {
							$data['fail'] = gettext("You cannot like your own posts.");
						}
					} else {
						$data['fail'] = gettext("You have already liked this post.");
					}
				} else {
					header("Location: ".$site_url."forum/".$forumtype."/");				
				}
			} else {
				header("Location: ".$site_url."forum/".$forumtype."/");				
			}

			if($inside) {
				$this->session->set_displaydata('messages',$data);
				header("Location: ".$site_url."forum/".$forumtype."/view/".$postinfo['topicid']."/");
				exit();
			}
		
		}
	
	}