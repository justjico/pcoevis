<?php

$file_name = "pathnet.Fsqrnk.exp.txt";

require_once("connect.php");

$query = "select id,name from pathway_names";
$result = mysql_query($query);

if (!$result) {
    die('Invalid query: ' . mysql_error());
}

$pathway_id = array();

while ($row = mysql_fetch_assoc($result)) {
    $pathway_id[$row['name']] = $row['id'];
}

$file_array = file($file_name);

foreach ($file_array as $line_number=>$line)
{
// Handle the line
    //echo $line_number. " -  " . $line;
//Pathway.A	Genes.A	Pathway.B	Genes.B	Overlap	Overlap.metric	Correlation	Adj.Correlation	Adjacency
    list($pathway_a,$genes_a,$pathway_b,$genes_b,$overlap,$overlap_metric,$correlation,$adj_correlation,$adjacency) = explode("\t",$line);
    echo $line_number . " - ". $pathway_id[$pathway_a] . "=>" . $pathway_a . " : " .$pathway_id[$pathway_b] . "=>" . $pathway_b . "\n" ;  
    $ins_query = "INSERT INTO pathways (PathwayA, PA_id, GenesA, PathwayB, PB_id, GenesB, Overlap, OverlapMetric, Correlation, Adj_correlation, Adjacency)" .
    " values ('".mysql_real_escape_string($pathway_a)."', $pathway_id[$pathway_a], $genes_a, '".mysql_real_escape_string($pathway_b)."', $pathway_id[$pathway_b], $genes_b, $overlap, $overlap_metric, $correlation, $adj_correlation, $adjacency) ";
    $result = mysql_query($ins_query);
if (!$result) {
    die('Invalid query: ' . mysql_error() . $ins_query);
}
    
}
