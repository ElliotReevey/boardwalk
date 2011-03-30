<h1><?=gettext("Helpdesk")?></h1>

<?php if(isset($messages['fail'])) { echo errorbox($messages['fail']); } elseif(isset($messages['success'])) { successbox($messages['success']); } ?>

<div class="contentContainer">

	<a href="<?=$site_url?>helpdesk/create/"><?=gettext("Create a new ticket")?></a>
	
	<?php
		$tickettypes = array("Game Help","Login Problems","Payments","Bug","Idea");
		$ticketstatus = array("Open","Processing","Closed");
			echo "
				<table>
					<tr>
						<th>".gettext("Ticket ID")."</th>
						<th>".gettext("Last Reply")."</th>
						<th>".gettext("Type")."</th>
						<th>".gettext("Subject")."</th>
						<th>".gettext("Status")."</th>
						<th>".gettext("Action")."</th>
					</tr>
				";
			if(isset($yourtickets)) {
			foreach($yourtickets as $ticket){
				echo "
					<tr>
						<td>".$ticket['id']."</td>
						<td>".date("H:i d/m/Y",$ticket['last_action'])."</td>
						<td>".$tickettypes[$ticket['type']-1]."</td>
						<td>".$ticket['subject']."</td>
						<td>".$ticketstatus[$ticket['status']]."</td>
						<td><a href='".$site_url."helpdesk/view/".$ticket['id']."/'>".gettext("View")."</a></td>
					</tr>
				";
			}
			} else {
				echo "
					<tr>
						<td colspan='6' align='center'>".gettext("You do not have any open tickets")."</td>
					</tr>			
				";
			}
			echo "</table>";
	?>
	
	<h1><?=gettext("Staff Members")?></h1>
	<h3><?=gettext("Admins")?></h3>
	<h3><?=gettext("Helpdesk")?></h3>
	<?php
	if(isset($helpdesks)) {
		echo "<ul>";
			foreach($helpdesks as $helpdesk) {
				echo "<li>".$helpdesk['username']."</li>";
			}
		echo "</ul>";
	} else {
		echo gettext("There are currently no helpdesk members.");
	} ?>
</div>