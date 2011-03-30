<h1><?=gettext("Inbox")?></h1>

<?php if(isset($messages['fail'])) { echo errorbox($messages['fail']); } elseif(isset($messages['success'])) { successbox($messages['success']); } ?>

<div class="contentContainer">
	<div>
		<a href="/boardwalk/inbox/compose/"><?=gettext("Compose")?></a> | <a href="/boardwalk/inbox/sent/"><?=gettext("Sent")?></a> 
	</div>
	<div>
		<?=sprintf(gettext("Total Messages: %s | Unread %s"),$totalinbox,$unread)?>
	</div>

	<?php
	if(count($inbox) > 0) {
		foreach($inbox as $message){ ?>
		<div class="messageContainer" id="<?=$message['mid']?>">
			<div class="messageSelect"><input type="checkbox" name="messageID" value="<?=$message['mid']?>"></div>
			<div class="avatarImage"><img src="<?=$site_url?>/assets/images/avatars/<?=$message['users'][0]['avatar']?>"></div>
			<div class="infoContainer">
				<div class="usersInvolved">
				
					<?php 
					if(count($message['users']) > 1){
						$last = array_pop($message['users']);
						foreach($message['users'] as $users){
							echo $users['username'].', ';	
						}
						echo $last['username'];
					} elseif(count($message['users']) == 1) {
						if($message['users'][0]['username'] == 'State'){
							echo "Notification";
						} else {
							echo $message['users'][0]['username'];
						}
					}
					?>
					
				</div>
				<div class="lastReply"><?=date("d F",$message['created_on'])?> at <?=date("H:i",$message['created_on'])?></div>
			</div>
			<div class="messageContents" onclick="window.location.href='<?=$site_url?>inbox/conversation/<?=$message['mid']?>/'">
				<?=$message['body']?>
			</div>
			<div class="deleteMessage"><a href="<?=$site_url?>inbox/delete/<?=$message['mid']?>/">X</a></div>
		</div>	
	<?php } 
	} else { ?>
		<div class="emptyInbox"><?=gettext("No messages in your inbox")?></div>
	<?php }	?>
</div>