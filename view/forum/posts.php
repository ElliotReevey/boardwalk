<?php if(isset($messages['fail'])) { echo errorbox($messages['fail']); } elseif(isset($messages['success'])) { successbox($messages['success']); } ?>

<div class="contentContainer">
	<div class="forumNavigation">
		<div class="forumPagination">Pages: 1 2 -></div>
		<div class="forumButtons">
			<div class="forumButton"><?=gettext("Add Reply")?></div>
			<div class="forumButton"><a href="<?=$site_url?>forum/<?=$forumslug?>/create/"><?=gettext("Start New Topic")?></a></div>
		</div>
	</div>
	<div class="topicContainer">
		<div class="topicHeader"><?=$topic['topicname']?></div>
		<div class="topicInfo">
			<div class="topicCreator"><?=$topic['username']?></div>
			<div class="postNumber">#1</div>
		</div>
		<div class="bodyContainer">
			<div class="posterInfo">
				<div class="playerAvatar"></div>
				<div class="playerRank"><?=gettext("Rank:")?></div>
				<div class="forumStats">
					<div class="statsHeader"><?=gettext("Posts:")?></div>
					<div class="statResult"><?=number_format($topic['posts'])?></div>
				</div>
			</div>
			<div class="postContainer">
				<div class="createdTime"><?=sprintf(gettext("Posted %s"),date("d F Y - H:i A",$topic['createdat']))?></div>
				<div class="postBody"><?=$topic['body']?></div>
			</div>
			<div class="userSignature"><?=$topic['signature']?></div>
		</div>
		<div class="additionalStats">
			<div class="replyQuote"><?=gettext("Quote Post")?></div>
			<div class="postLikes"><?=sprintf(gettext("Likes: %s"),$topic['likes'])?></div>
			<div class="topicAddLike"><a href="<?=$site_url?>forum/<?=$forumslug?>/topiclike/<?=$topic['topicid']?>/">+</a></div>
		</div>
	</div>
	<?php foreach($posts as $postk => $postv) { ?>
	<div class="topicContainer">
		<div class="topicInfo">
			<div class="topicCreator"><?=$postv['username']?></div>
			<div class="postNumber">#<?=$postk+2?></div>
		</div>
		<div class="bodyContainer">
			<div class="posterInfo">
				<div class="playerAvatar"></div>
				<div class="playerRank"><?=gettext("Rank:")?></div>
				<div class="forumStats">
					<div class="statsHeader"><?=gettext("Posts:")?></div>
					<div class="statResult"><?=number_format($postk['posts'])?></div>
				</div>
			</div>
			<div class="postContainer">
				<div class="createdTime"><?=sprintf(gettext("Posted %s"),date("d F Y - H:i A",$postv['createdat']))?></div>
				<div class="postBody"><?=$postv['body']?></div>
			</div>
			<div class="userSignature"><?=$postv['signature']?></div>
		</div>
		<div class="additionalStats">
			<div class="replyQuote"><?=gettext("Quote Post")?></div>
			<div class="postLikes"><?=sprintf(gettext("Likes: %s"),$postv['likes'])?></div>
			<div class="addPostLike"><a href="<?=$site_url?>forum/<?=$forumslug?>/postlike/<?=$postv['postid']?>/">+</a></div>
		</div>
	</div>
	<?php } ?>
	<?=form_open_this("forum/".$forumslug."/newpost/".$topicid)?>
	<div class="topicContainer">
		<div class="topicHeader"><?=gettext("Reply")?></div>
		<div class="createContent">
			<textarea name="topicBody" class="textArea"><?=set_value('topicBody')?></textarea>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Post")?>" name="postReply">
		</div>				
	</div>
	</form>
</div>