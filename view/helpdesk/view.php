<h1><?=gettext("Helpdesk")?></h1>

<?php if(isset($messages['fail'])) { echo errorbox($messages['fail']); } elseif(isset($messages['success'])) { successbox($messages['success']); } ?>

<div class="contentContainer">
	<div class="messageDetails">
		<div class="ticketID"><?=gettext("Ticket")?> # <?=$theticket['id']?></div>
		<div class="ticketSubject"><?=gettext("Subject:")?> <?=$theticket['subject']?></div>
		<div class="ticketStatus"><?=gettext("Ticket status:")?> <?=$theticket['status']?></div>
	</div>
	<?php if($closed == 1){?>
	<?=form_open_this("helpdesk/close")?>
		<div class="closeContainer">
			<div class="closeHeader"><?=gettext("Confirm close ticket")?></div>
			<div class="closeSatisfactory"><?=gettext("Was the answer your received satisfactory?")?> <?=gettext("Yes")?> <input type="radio" name="satisfactoryAnswer" value="1"> <?=gettext("No")?> <input type="radio" name="satisfactoryAnswer" value="0"></div>
			<div class="closeConfirm"><?=gettext("Are you sure you want to close this ticket?")?></div>
			<div class="closeButtons"><input type="submit" class="submitButton" value="<?=gettext("Yes")?>" name="closeTicket"> <input type="submit" class="submitButton" value="<?=gettext("No")?>" name="leaveTicket"><input type="hidden" name="ticketID" value="<?=$theticket['id']?>"></div>
		</div>	
	</form>
	<?php } ?>
	<div class="ticketNav">
	<?php if($theticket['playerid'] == $id) {?>
		<div class="fl"><a href="<?=$site_url?>helpdesk/"><?=gettext("Return to Tickets")?></a></div>
		<div class="fr"><a href="<?=$site_url?>helpdesk/view/<?=$theticket['id']?>/close/"><?=gettext("Close Ticket")?></a></div>
	<?php } else { ?>
		<div class="fl"><a href="<?=$site_url?>managetickets/"><?=gettext("Back to Manage Tickets")?></a></div>
	<?php } ?>
	</div>

	<div class="ticketReplies">
		<div class="ticketInfo"><?=sprintf(gettext("Original ticket message posted %s at %s"),date("d/m/Y",$theticket['createdat']),date("H:i:s",$theticket['createdat']))?></div>
		<div class="ticketBody"><?=$theticket['body']?></div>
	</div>

	<?php foreach($replies as $reply) {?>
		<div class="ticketReplies">
			<div class="ticketInfo"><?=sprintf(gettext("Response by %s posted %s at %s"),$reply['username'],date("d/m/Y",$reply['createdat']),date("H:i:s",$reply['createdat']))?></div>
			<div class="ticketBody"><?=$reply['body']?></div>
		</div>
	<?php } ?>
	
	<?=form_open_this("helpdesk/reply")?>
		<div class="ticketReply">
			<h4><?=gettext("Reply")?></h4>
			<textarea name="ticketReply" rows="6" cols="80" class="textArea"><?=set_value("ticketReply")?></textarea>
			<div class="inputButton">
				<input type="submit" class="submitButton" value="<?=gettext("Submit")?>" name="addReply">
				<input type="hidden" name="ticketID" value="<?=$theticket['id']?>">
			</div>
		</div>
	</form>
</div>