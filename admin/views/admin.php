<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
global $wpdb;
$my_plugin_table = $wpdb->prefix . 'rsvp_guests';

if(isset($_POST['submitbulkaction']))
{
	switch($_POST['action2']){
		case 'delete':					
			foreach ((array) $_POST['guest'] as $id) {				
				$wpdb->delete( $my_plugin_table, array( 'id' => $id ) );
			}
			break;
		default:
			echo('<h1>'.$_POST['action2'].' is not a valid action</h1>');
			break;
	}	
}

$getSql = "SELECT * FROM $my_plugin_table";
$result = $wpdb->get_results($getSql);


$guestRows="";
foreach ($result as $key=>$user) {
	$rowStyle = $key%2==0?'alternate':'';
    $guestRows.= '<tr id="user-'.$user->id.'" class="'.$rowStyle.'">'.
    '<th scope="row" class="check-column">'.
		'<label class="screen-reader-text" for="cb-select-'.$user->id.'">Select '.$user->email.'</label>'.
		'<input type="checkbox" name="guest[]" id="guest_'.$user->id.'" class="administrator" value="'.$user->id.'">'.
		'</th>'.
		'<td class="name column-name">'.$user->name.'</td>'.
		'<td class="email column-email"><a href="mailto:'.$user->email.'" title="E-mail: '.$user->email.'">'.$user->email.'</a></td>'.
		'<td class="events column-role">'.$user->event.'</td>'.
		'<td class="guests column-guests">'.$user->num_guests.'</td>'.							
		'</tr>';		
}



?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<h3>Confirmed guests</h3>
	<form method="post" action="">
		<table class="wp-list-table widefat fixed users" cellspacing="0">
			<thead>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
						<input id="cb-select-all-1" type="checkbox">
					</th>				
					<th scope="col" id="name" class="manage-column column-name sortable desc" style="">
						<a href="">
							<span>Name</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="email" class="manage-column column-email sortable desc" style="">
						<a href="">
							<span>E-mail</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="events" class="manage-column column-events sortable desc" style="">
						<a href="">
							<span>Event(s)</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="guests" class="manage-column column-guests sortable desc" style="">
						<a href="">
							<span>Guests</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>				
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column" style="">
						<label class="screen-reader-text" for="cb-select-all-2">Select All</label>
						<input id="cb-select-all-2" type="checkbox">
					</th>				
					<th scope="col" class="manage-column column-name sortable desc" style="">
						<a href="">
							<span>Name</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-email sortable desc" style="">
						<a href="">
							<span>E-mail</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-events sortable desc" style="">
						<a href="">
							<span>Event(s)</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" class="manage-column column-guests sortable desc" style="">
						<a href="">
							<span>Guests</span>
							<span class="sorting-indicator"></span>
						</a>
					</th>	
				</tr>
			</tfoot>

			<tbody id="the-list" data-wp-lists="list:user">
				<?php					
					echo($guestRows)
				?>				
			</tbody>
		</table>
		<div class="tablenav bottom">
			<div class="alignleft actions bulkactions">
				<select name="action2">
					<option value="-1" selected="selected">Bulk Actions</option>
					<option value="delete">Delete</option>
				</select>
				<input type="submit" name="submitbulkaction" id="submitbulkaction" class="button action" value="Apply">
			</div>	
		</div>
	</form>
	<h3>Settings</h3>
	<form method="post" action="options.php">
		<table class="form-table">
			<tbody>
				<tr>
					<th></th>
					<td></td>			
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>