<?php

/*	
 	
	Copyright 2014  pixeltradr.com  (email : dbudhia@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/


 /**
  * Decalre initial $limit and $offset values
  * 
  */
 $limit 		= 10;
 $offset 	= 0;
 
 if($_GET['paged'] > 1) $offset = ($_GET['paged'] - 1) * $limit;

 //----------------------------------------------------------------
 
 /**
  * get list of interested users
  * 
  */ 
 global $wpdb;
 $table_name 		= $wpdb->prefix . "awesome_interest_table";
 $sql				= "SELECT * FROM `".$table_name."` ORDER BY `id` DESC LIMIT ".$offset.",".$limit;
 $query 			= $wpdb->get_results($sql);
 $count 			= $wpdb->get_var(("SELECT COUNT(*) FROM $table_name")); 
 $sum_wedding		= $wpdb->get_var(("SELECT SUM(event_wedding) as total_wedding FROM $table_name")); 
 $sum_reception		= $wpdb->get_var(("SELECT SUM(event_reception) as total_reception FROM $table_name")); 
 $sum_attendees		= $wpdb->get_var(("SELECT SUM(count) as total_attendees FROM $table_name")); 

 //----------------------------------------------------------------

 
 /**
  * pagination 
  * 
  */
 $_GET['paged'] > 1 ? $current = $_GET['paged'] : $current = 1;
 $calc_pages = $count / $limit;
 $args = array(
    'base' 			 => @add_query_arg('paged','%#%'),
    'total'        => (round($calc_pages, 0) < $calc_pages) ? (round($calc_pages, 0)+1) : round($calc_pages),
    'current'      => $current,
    'show_all'     => true,
    'type'         => 'plain',
    'add_args'     => 'page=awesome_interest'
 ); 
 
 //----------------------------------------------------------------
 

 /**
  * set column heads for the table
  * 
  */
 $columns = array (
 	'name'				=> 'Name',
 	'count'				=> 'How many attendees',
 	'event_wedding'		=> 'Wedding',
 	'event_reception'	=> 'Reception'

 );
 register_column_headers('interest_count', $columns)

 //---------------------------------------------------------------- 
 
?>

<div class='wrap'>
	<h2><?php _e('\'Awesome - Interest\'', 'awesome_interest'); ?></h2>
		
	<p><?php echo 'Total Wedding '. $sum_wedding ?></p>
	<p><?php echo 'Total Reception '. $sum_reception ?></p>	
	<p><?php echo 'Total Attendees '. $sum_attendees ?></p>	
	
	<div class="tablenav"><div class='tablenav-pages'>		
		<?php echo paginate_links( $args ); ?> 
	</div></div>
	<table class="wp-list-table widefat  pages" cellspacing="0">
		<thead>
			<tr>
				<?php print_column_headers('interest_count')?>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<?php print_column_headers('interest_count')?>
			</tr>
		</tfoot>
		<tbody>
			
			<?php if($query):?>
				<?php foreach ($query as $row): ?>
				<tr>
					<td><?php echo $row->name; ?></td>
					<td><?php echo $row->count; ?></td>
					<td><?php if($row->event_wedding == 1){ echo "yes";}else{echo "-";} ?></td>
					<td><?php if($row->event_reception == 1){ echo "yes";}else{echo "-";} ?></td>						
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan="3"> No results to display</td>
				</tr>
			<?php endif;?>
			
		</tbody>
	</table>
	<div class="tablenav"><div class='tablenav-pages'>	
		<?php echo paginate_links( $args ); ?> 
	</div></div>
	
</div>




<?php 
/* End of file admin_page.php */
?>