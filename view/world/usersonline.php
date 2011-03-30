<h1><?=gettext("Users Online")?></h1>

<div class="contentContainer">
<p><?=gettext("Staff online in the last 5 minutes:")?></p>
<?php
	if(count($staffonline) >= 1) {
		if(count($staffonline) > 1){
			$last = array_pop($staffonline);
			foreach($staffonline as $staff){
				echo $staff['username'].', ';	
			}
			echo $last['username'];
		} elseif(count($staffonline) == 1) {
			echo $staffonline[0]['username'];
		} 
	} else {
		echo gettext("There is currently no staff online.");
	}
?>

<p><?=gettext("Users online in the last 10 minutes:")?></p>
<?php
	if(count($playersonline) >= 1) {
		if(count($playersonline) > 1){
			$last = array_pop($playersonline);
			foreach($playersonline as $player){
				echo $player['username'].', ';	
			}
			echo $last['username'];
		} elseif(count($playersonline) == 1) {
			echo $playersonline[0]['username'];
		} 
	} else {
		echo gettext("There is currently no players online.");
	}
?>
<p><?=sprintf(gettext("Total: %s"),count($playersonline+$staffonline))?></p>
</div>