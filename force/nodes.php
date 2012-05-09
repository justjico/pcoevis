<?php

require_once("functions.php");

if(!isset($_REQUEST['pathway']))
return;

if(!isset($_REQUEST['limit']) || $_REQUEST['limit'] > 630)
  $limit = 20;
else
  $limit = $_REQUEST['limit'];

$exceptions = array();
if(isset($_REQUEST['exceptions']))
  $exceptions = explode(",",$_REQUEST['exceptions']);

$edges = 0;
if(isset($_REQUEST['edges']))
  $edges = $_REQUEST['edges'];

require_once("connect.php");

$pathway = mysql_real_escape_string($_REQUEST['pathway']);

$pathname = $pathway;

if(isset($_REQUEST['pathname']))
  $pathname = mysql_real_escape_string($_REQUEST['pathname']);

if(count($exceptions) > 1)
  {
    $exceptions = implode(",",$exceptions);
    $query = sprintf("SELECT * from net where (PA_id = '%s' OR PB_id = '%s') AND (PA_id NOT IN ($exceptions) AND PB_id NOT IN ($exceptions)) ORDER BY Adjacency DESC LIMIT $limit",$pathway,$pathway);
  }
else
  {
    $query = sprintf("SELECT * from net where PA_id = '%s' OR PB_id = '%s' ORDER BY Adjacency DESC LIMIT $limit",$pathway,$pathway);
  }
// Perform Query
$result = mysql_query($query);

// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}


$nodes = array();
$link_qty = array();

$node['id'] = "i".$pathway;
$node['group'] = 1;
$node['size'] = get_size($pathname);
$node['type'] = get_type($pathname);
$node['name'] = $pathname;

$nodes[] = $node;

$output["nodes"] = $nodes;
$output["links"] = array();
$output["node_list"] = array();


$first = TRUE;

$strongest = 0;
$weakest = 0;

while ($row = mysql_fetch_assoc($result)) {

    if( $first )
    {
      $strongest = $row['Adjacency'];
      $first = FALSE;
    }
         
    add_node($row, $pathway, $output, $link_qty);
	
	$weakest = $row['Adjacency'];
}

mysql_free_result($result);

if($edges > 0)
{

	$nlist = implode(",", $output['node_list']);
	
	$limitquery = " LIMIT $limit";
	
	$query2 = sprintf("SELECT * from net where PA_id IN(%s) AND PB_id IN(%s) AND Adjacency > %d ORDER by Adjacency DESC ",$nlist,$nlist,$strongest);
	
	if($edges == 1)
	  $query2.= $limitquery;
	
	// Perform Query
	$result2 = mysql_query($query2);
	
	// Check result
	// This shows the actual query sent to MySQL, and the error. Useful for debugging.
	if (!$result2) {
		$message  = 'Invalid query: ' . mysql_error() . "\n";
		$message .= 'Whole query: ' . $query2;
		die($message);
	}
	
	while ($row = mysql_fetch_assoc($result2)) {         
		add_node($row, $pathway, $output, $link_qty);
	}

	// Free the resources associated with the result set
	// This is done automatically at the end of the script
	mysql_free_result($result2);
	
	prune_nodes($output, $link_qty);
     //print_r($output);

}

reindex_nodes($output);

$output["strongest"] = $strongest;
$output["weakest"] = $weakest;
$output["node_list"] = implode(",",$output['node_list']);

echo json_encode($output);


?>