<?php

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

require_once("connect.php");

$query = 'SELECT id,name from pathway_names ORDER BY name ASC';

// Perform Query
$result = mysql_query($query);

// Check result
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Force Test</title>
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="d3/d3.v2.min.js"></script>

<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.18.custom.css" rel="Stylesheet" />	
<link type="text/css" rel="stylesheet" href="css/force.css"/>
</head>
<body>
<meta charset="utf-8">
<div class="content">
<h2 id="pname"></h2>

<div class="demo">
<table border=0 id='tableMenu'>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td rowspan="3"><input type="checkbox" id="shownames" onChange="chgPathway()" /><label for="shownames">Show names</label></td>
    </tr>
<tr>
	<td width="170"><label for="amount">Nodes to display:</label><input type="text" size="4" id="amount" style="border:0; color:#f6931f; font-weight:bold;" /></td>
	<td><div id="slider-node-num" style="width:300px"></div></td>
</tr>
<tr>
	<td><label for="amount-distance">Distance:</label><input type="text" size ="4" id="amount-distance" style="border:0; color:#f6931f; font-weight:bold;" /></td>
	<td><div id="slider-distance" style="width:300px"></div></td>
</tr>

</table>

</div>
<br />
    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="pathForm" id="pathForm">
    <table border=0 id="dropMenu">
    <tr>
    <td>
    <select onChange="chgPathway()" id="pathway" name="pathway">
        <option selected>Select Pathway from the list</option>
    <?php
    // Use result
	while ($row = mysql_fetch_assoc($result)) {
		echo "\t\t<option value='$row[id]'>$row[name]</option>\n";
	}
	
	// Free the resources associated with the result set
	mysql_free_result($result);
    ?>
    </select>
    </td>
    </tr>
    <tr>
    <td>
    <select onChange="chgPathway()" id="order" name="order">
        <option selected value="0">Adjacency</option>
        <option value="1">Correlation</option>
        <option value="2">Adjusted Correlation</option>
        <option value="3">Overlap Metric</option>
    </select>
    </td>
    </tr>
    <tr>
    <td>
    <select onChange="chgPathway()" id="edges" name="edges">
        <option selected value="0">Only direct connections</option>
        <option value="1">Top edges</option>
        <option value="2">All edges</option>
    </select>
    </td>
    </tr>
    </table>
    </form>

    <div id="chart"></div>
    <script type="text/javascript" src="js/force.js"></script>
    <div style='text-align:right; margin-right:200px'><a href='#' onclick='showLegend();'>Show Legend</a></div>
		<div id="legend" title="Legend">
			<table border='1'>
			<tr>
			  <th width='100px'>Symbol</th>
			  <th>Source</th>
			</tr>
			<tr>
			  <td align='center'><img src="img/square.gif"></td>
			  <td>Reactome</td>
			</tr>
			<tr>
			  <td align='center'> <img src="img/circle.gif"></td>
			  <td>KEGG</td>
			</tr>
			<tr>
			  <td align='center'><img src="img/triangle.gif"></td>
			  <td>Static Module</td>
			</tr>
			<tr>
			  <td align='center'><img src="img/rombus.gif"></td>
			  <td>Wikipaths</td>
			</tr>
			</table>
		</div>

    <div id="y" class="container_12">
    </div>
    </div>

</body>
</html>
