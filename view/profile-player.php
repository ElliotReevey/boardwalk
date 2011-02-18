<h1><?=sprintf(gettext("Profile of %s"),$userinfo['username'])?></h1>

<h2>Information</h2>
<div class="contentContainer">
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Name:")?></div>
		<div class="inputField"><?=$userinfo['username']?></div>
	</div>
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Status:")?></div>
		<div class="inputField"><?=$userinfo['status']?></div>
	</div>
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Start date:")?></div>
		<div class="inputField"><?=date("H:i d/m/Y",$userinfo['signup_date'])?></div>
	</div>
	<div class="fieldHolder">
		<div class="labelItem"><?=gettext("Profile quote:")?></div>
		<div class="inputField"><?=$profile['quote']?></div>
	</div>
</div>