<?php

require_once("functions.php");

if(!isset($_REQUEST['pathway']))
return;

if(!isset($_REQUEST['limit']) || $_REQUEST['limit'] > 630)
  $limit = 20;
else
  $limit = $_REQUEST['limit'];

require_once("connect.php");

$pathway = mysql_real_escape_string($_REQUEST['pathway']);

$pathname = $pathway;

if(isset($_REQUEST['pathname']))
  $pathname = mysql_real_escape_string($_REQUEST['pathname']);

$query = sprintf("SELECT * from net where PA_id = '%s' OR PB_id = '%s' ORDER BY Adjacency DESC LIMIT $limit",$pathway,$pathway);

// Perform Query
$result = mysql_query($query);

// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}

$table = array();
$node_names = array();
$node_list = array();

$first = TRUE;
$strongest = 0;

while ($row = mysql_fetch_assoc($result)) {
    if( $first )
    {
      $strongest = $row['Adjacency'];
      $first = FALSE;
    }
    
    add_node_table($row, $pathway, $table, $node_names, $node_list);
}

mysql_free_result($result);

$first = TRUE;
$num_pathways = count($table);

echo "<table border='1' cellpadding='0' cellspacing='0' id='table1'>";
echo "<thead>";
echo "<tr>";
echo "<th>Pathway name</th>";
echo "<th>Gene Overlap</th>";
echo "<th>Adjacency</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

foreach($table as $key => &$row)
{
  echo "<tr>";
  echo "<td><a href='#$key' onclick='selectPath($key);'>$node_names[$key]</a>&nbsp;&nbsp;<a href='#$key-remove' onclick='addException($key)'>Remove</a></td>";
  echo "<td class='data'>$row[overlap]</td>";
  echo "<td class='data'><font color='$row[color]'>$row[value]</font></td>";
  echo "</tr>";
}

echo "</tbody>";

echo "</table>";

echo "<center><a href='#' onclick='chgPathway()'>Restore Removed Nodes</a></center>";



?>