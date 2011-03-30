<?php
	foreach ($involved as $usersinvolved) {
		$username[] = $usersinvolved['username'];
	}
	$last = array_pop($username);
?>

<?php if($conversation[0]['created_by'] != 0) { ?>
<h1><?=sprintf(gettext("Conversation with %s and %s"),implode(', ', $username),$last)?></h1>
<?php } else {?>
<h1><?=gettext("Message from Notification");?></h1>
<?php } ?>

<?php if(isset($messages['fail'])) { echo errorbox($messages['fail']); } elseif(isset($messages['success'])) { successbox($messages['success']); } ?>

<div class="contentContainer">
		<?php foreach($conversation as $message){ ?>
		<div class="messageContainer" id="<?=$message['mid']?>">
			<div class="avatarContainer">
				<div class="avatarImage"><img src="<?=$site_url?>assets/images/avatars/<?=$message['avatar']?>"></div>
			</div>
			<div class="infoContainer">
				<div class="messageInfo">
					<div class="messageFrom"><?php if($message['username'] == 'State') { echo "Notification"; } else { echo $message['username']; } ?></div>
					<div class="messageDate"><?=date("d F Y",$message['created_on'])?> at <?=date("H:i",$message['created_on'])?></div>	
				</div>
			</div>
			<div class="messageContents">
				<?=$message['body']?>
			</div>
		</div>	
		<?php } if($conversation[0]['created_by'] != 0) {?>
		<?=form_open_this("compose/send")?>
		<div class="replyContainer">
			<div class="replyLeft">
				<?=gettext("Reply:")?>
			</div>
			<div class="replyRight">
				<textarea name="messageBox" class="textArea"><?=set_value('messageBox')?></textarea>
			</div>
			<div class="replySubmit">
				<input type="hidden" name="mid" value="<?=$message['mid']?>" />
				<input type="submit" name="sendMessage" class="submitButton" value="<?=gettext("Reply")?>" />
			</div>
		</div>
		</form>
		<?php } ?>
</div>