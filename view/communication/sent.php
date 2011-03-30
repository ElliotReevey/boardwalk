<h1><?=gettext("Sent")?></h1>

<?php if(isset($fail)) { echo errorbox($fail); } elseif(isset($success)) { successbox($success); } ?>

<div class="contentContainer">
	<?php
	if(count($sent) > 0) {
		foreach($sent as $message){ ?>
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
						echo $message['users'][0]['username'];
					} else {
						echo "Notification";
					}
					?>
					
				</div>
				<div class="lastReply"><?=date("d F",$message['created_on'])?> at <?=date("H:i",$message['created_on'])?></div>
			</div>
			<div class="messageContents" onclick="window.location.href='<?=$site_url?>inbox/conversation/<?=$message['mid']?>/'">
				<?=$message['body']?>
			</div>
		</div>	
	<?php } 
	} else { ?>
		<div class="emptyInbox"><?=gettext("No messages in your sent box")?></div>
	<?php }	?>
</div>