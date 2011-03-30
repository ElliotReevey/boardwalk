<h1><?=sprintf(gettext("%s Forum"),$name)?></h1>

<?php if(isset($messages['fail'])) { echo errorbox($messages['fail']); } elseif(isset($messages['success'])) { successbox($messages['success']); } ?>

<div class="contentContainer">
	<div class="forumNavigation">
		<div class="forumPagination">Pages: 1 2 -></div>
		<div class="forumButtons">
			<div class="forumButton"><?=gettext("Add Reply")?></div>
			<div class="forumButton"><a href="<?=$site_url?>forum/<?=$forumslug?>/create/"><?=gettext("Start New Topic")?></a></div>
		</div>
	</div>
<?php if($forumtype != 3 || $nocrew != 1) {?>
	<div class="forumHeaders">
		<div>Topic</div>
		<div>Started By</div>
		<div>Stats</div>
		<div>Last Post Info</div>
	</div>
	<?php 
	if(count($topics) > 0) {
	foreach($topics as $topic) {?>
	<div class="topicRow">
		<div class="topicName"><a href="<?=$site_url?>forum/<?=$slug?>/view/<?=$topic['id']?>/"><?=$topic['topicname']?></a></div>
		<div class="topicCreator">
			<?=$topic['creatorUsername']?>
			<div class="topicAddLike"><a href="<?=$site_url?>forum/<?=$slug?>/like/<?=$topic['id']?>/">+</a></div>	
		</div>
		<div class="topicStats">
			<div class="topicReplies"><?=$topic['replies']?></div>
			<div class="topicLikes"><?=$topic['likes']?></div>
		</div>
		<div class="lastPostInfo">
			<div class="lastPost"><?=date("H:i d/m/Y",$topic['lastpost'])?></div>
			<div class="lastPoster"><?=$topic['lastUsername']?></div>
		</div>
	</div>
	<?php 
		} 
	} else {?>
		<div class="noTopics"><?=gettext("No topics in this forum.")?></div>
	<?php }
	} else {?>
		<?=gettext("You are not apart of a crew.")?>
	<?php } ?>
</div>