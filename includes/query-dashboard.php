<?php
require_once dirname( __FILE__ ) . '/lib.php';
$lib_function = new Lib();
?>

<h1> Site Performance </h1>

<table cellpadding=5 cellspacing=0>
<?php
if(!isset($_GET['gid']))
{
	global $wpdb;
	$query = "SELECT * FROM wp_performance";
	$result = $wpdb->get_results($query);
?>
<a href="../?req=blogvault"><div class="top_btn"> + Generate New Report </div></a>
	<tr>
		<th> ID </th><th> Performance ID </th><th> Total Queries </th><th> Query Time </th>
		<th> Page Load Time </th><th> Generated At </th><th> Query Report </th><th> JS Report </th>
		<th> CSS Report </th>
	</tr>
	<?php
		foreach($result as $data)
		{
	?>
			<tr>
				<td><?php echo $data->id; ?></td><td><?php echo $data->gid; ?></td>
				<td><?php echo $data->num_queries; ?></td><td><?php echo $data->query_time; ?></td>
				<td><?php echo $data->pageload_time; ?></td><td><?php echo $data->created_at; ?></td>
				<td> <a href='admin.php?page=query-dashboard&type=query&gid=<?php echo $data->gid; ?>'>
						View Report </a></td>
				<td> <a href='admin.php?page=query-dashboard&type=js&gid=<?php echo $data->gid; ?>'>
						View Report</a> </td>
				<td> <a href='admin.php?page=query-dashboard&type=css&gid=<?php echo $data->gid; ?>'>
						View Report</a> </td>
			</tr>
	<?php
		}
	?>
<?php
}else if(isset($_GET['gid']) && isset($_GET['type'])){
		global $wpdb;
		$gid = $_GET['gid'];
		$type = $wpdb->_real_escape($_GET['type']);
		if($type == "query")
		{
			$query = "SELECT * FROM wp_querydata WHERE gid='".$gid."'";
			$result = $wpdb->get_results($query);
?>
			<table cellpadding=5 cellspacing=0 style="width:900px;">
				<tr>
					<th> S.No </th><th> Performance ID </th><th> QUERY </th><th> Time </th><th> Stack </th>
					<th> Results </th><th> Component </th>
				</tr>
		<?php
			foreach($result as $key => $data){
		?>
				<tr>
					<td><?php echo $key+1; ?></td><td><?php echo $data->gid; ?></td>
					<td><?php echo $data->query; ?></td><td><?php echo $data->time; ?></td>
					<td><?php echo $data->stack; ?></td><td><?php echo $data->results; ?></td>
					<td><?php echo $data->component; ?></td>
				</tr>
		<?php
			}
		?>
		</table>
	<?php
		}else{	    
			if($type == 'js')
				$script_type = 'JS';
			else
				$script_type = 'CSS';

			$query = "SELECT * FROM wp_scriptdata WHERE gid='".$gid."' AND type='".$type."'";
			$result = $wpdb->get_results($query);
	?>  
			<tr>
				<th> S.No </th><th> Performance ID </th><th> Type </th><th> Position </th><th> Handle </th>
				<th> Source </th><th> Version </th><th> Dependencies </th><th> Component </th>
			</tr>
			<?php
				foreach($result as $key => $data){
			?>
    		<tr>
					<td><?php echo $key+1; ?></td><td><?php echo $data->gid; ?></td>
					<td><?php echo $data->type; ?></td><td><?php echo $data->position; ?></td>
					<td><?php echo $data->handle; ?></td><td><?php echo $data->source; ?></td>
					<td><?php echo $data->version; ?></td><td><?php echo $data->dependencies; ?></td>
					<td><?php echo $data->component; ?></td>
				</tr>
  		<?php
    		}
			?>  
<?php }


}
?>
</table>
