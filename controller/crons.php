<?php
/***** CRONS *****/

/*** DAILY ***/

/** CLOSE OLD HD TICKETS **/
$1weekago = time()-604800;

$oldtickets = $this->db->query("SELECT * FROM helpdesk_tickets WHERE status = '1' AND last_action < '$1weekago'")-rows();
$site_url = $this->core->get_config_item('base_url');
foreach($oldtickets as $ticket) {
	
	$this->db->query("UPDATE helpdesk_tickets SET status = '2' WHERE id = '{$ticket['id']}'");
	$this->db->query("UPDATE helpdesk_tickets SET tickets_open = tickets_open - '1', tickets_closed = tickets_closed + 1 WHERE characterid = '{$ticket['taken_by']}'");
	$ticketclosed = sprintf(gettext("Due to inactivity your Helpdesk Ticket with the subject \"%s\" has been closed.<br><br>Should you still require assistance regarding this matter please create a new <a href='%shelpdesk/'>Helpdesk Ticket</a>."),$ticket['subject'],$site_url);
	$this->gamecore->mail_notification($ticket['playerid'],$ticketclosed);

}



?>