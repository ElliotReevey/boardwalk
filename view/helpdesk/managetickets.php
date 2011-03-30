<h1><?=gettext("Manage Tickets")?></h1>

<?php if(isset($messages['fail'])) { echo errorbox($messages['fail']); } elseif(isset($messages['success'])) { successbox($messages['success']); } ?>

<h2><?=gettext("Open Tickets")?></h2>
<?php
	$tickettypes = array("Game Help","Login Problems","Payments","Bug","Idea");
		echo "
			<table>
				<tr>
					<th>".gettext("Ticket ID")."</th>
					<th>".gettext("Created")."</th>
					<th>".gettext("Username")."</th>
					<th>".gettext("Type")."</th>
					<th>".gettext("Subject")."</th>
					<th>".gettext("Action")."</th>
				</tr>
			";
		if(isset($opentickets)) {
		foreach($opentickets as $ticket){
			echo "
				<tr>
					<td>".$ticket['id']."</td>
					<td>".date("H:i d/m/Y",$ticket['createdat'])."</td>
					<td>".$ticket['username']."</td>
					<td>".$tickettypes[$ticket['type']-1]."</td>
					<td>".$ticket['subject']."</td>
					<td><a href='".$site_url."managetickets/taketicket/".$ticket['id']."/'>".gettext("Take Ticket")."</a></td>
				</tr>
			";
		}
		} else {
			echo "
				<tr>
					<td colspan='6' align='center'>".gettext("There are not any open tickets")."</td>
				</tr>			
			";
		}
		echo "</table>";
?>
<h2><?=gettext("Your Tickets")?></h2>
<?php
	$tickettypes = array("Game Help","Login Problems","Payments","Bug","Idea");
		echo "
			<table>
				<tr>
					<th>".gettext("Ticket ID")."</th>
					<th>".gettext("Last Updated")."</th>
					<th>".gettext("Username")."</th>
					<th>".gettext("Type")."</th>
					<th>".gettext("Subject")."</th>
					<th>".gettext("Action")."</th>
				</tr>
			";
		if(isset($yourtickets)) {
		foreach($yourtickets as $yourticket){
			echo "
				<tr>
					<td>".$yourticket['id']; if($yourticket['replied'] == 1){ echo " <span class='replied'>(R)</span>"; } echo "</td>
					<td>".date("H:i d/m/Y",$yourticket['lastaction'])."</td>
					<td>".$yourticket['username']."</td>
					<td>".$tickettypes[$yourticket['type']-1]."</td>
					<td>".$yourticket['subject']."</td>
					<td><a href='".$site_url."helpdesk/view/".$yourticket['id']."/'>".gettext("View")."</a> / <a href='".$site_url."managetickets/passback/".$yourticket['id']."/'>Pass Back</a></td>
				</tr>
			";
		}
		} else {
			echo "
				<tr>
					<td colspan='6' align='center'>".gettext("You have not taken any tickets")."</td>
				</tr>			
			";
		}
		echo "</table>";

?>