<?php if(isset($fail)) { echo errorbox($fail); } elseif(isset($success)) { successbox($success); } ?>

<div class="contentContainer">
<?=form_open_this("forum/".$forumslug."/create")?>
	<div class="topicContainer">
		<div class="topicName"><?=gettext("Posting A New Topic In Game Forum")?></div>
		<div class="createSub"><?=gettext("Topic Information")?></div>
		<div class="createContent">
			<div class="fieldHolder">
				<div class="labelItem"><?=gettext("Topic Title:")?></div>
				<div class="inputField"><input type="text" name="topicTitle" class="text" value="<?=set_value('topicTitle')?>"></div>
			</div>		
		</div>
		<div class="createSub"><?=gettext("Topic")?></div>
		<div class="createContent">
			<textarea name="topicBody" class="textArea"><?=set_value('topicBody')?></textarea>
		</div>
		<div class="createSub"><?=gettext("Options")?></div>

		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Post New Topic")?>" name="postTopic">
		</div>
	</div>
</form>
</div>