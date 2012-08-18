<?php

function get_type(&$name)
{
    $count = 0;
     
    $name = str_ireplace("(KEGG)","",$name,$count);
     
    if($count > 0)
      return "circle";
    
    $name = str_ireplace("(Static Module)","",$name,$count);
    
    if($count > 0)
      return "triangle-up";
      
    $name = str_ireplace("(Reactome)","",$name,$count);
    
    if($count > 0)
      return "square";
      
    $name = str_ireplace("(Wikipathways)","",$name,$count);
    $name = str_ireplace("(Netpath)","",$name,$count);
      
    return "diamond";
}


//TODO : Replace with a real font metric class
function get_size($str)
{
  $arr = str_split($str);
  $size = 0;
  
  foreach($arr as $c)
  {
    
    $ord = ord($c);
    
    if($c == 'i' ||
       $c == 'I' ||
       $c == "l" ||
       ($ord >= 48 &&
       $ord <= 57))
      {
        $size += 3;
        continue;
      }
      
        
    if ( $c == " " ||
         ($ord >= 97 && 
         $ord <= 122 &&
         $c != "m" &&
         $c != "w" ))
    {
        $size += 4;
        continue;
    }
    
    if( $c == "m" ||
     $c == "w" ||
     $c == "y" )
     {
       $size += 9;
       continue;
    }
  
    $size += 4;
  }
  
  return $size;

}

function add_node_table($row, $pathway, &$table, &$node_names, $order)
{

    $PA_id = $row['PA_id'];
    $PB_id = $row['PB_id'];
    
    if($row['PA_id'] == $pathway)
    {
      $target  = $row['PB_id']; 
      $node_names[$PB_id] = $row['PathwayB'];
      
      if(!isset($node_names[$PA_id]))
        $node_names[$PA_id] = $row['PathwayA'];      
    }  
    else
    {
      $target = $row['PA_id'];
      $node_names[$PA_id] = $row['PathwayA'];
      
      if(!isset($node_names[$PA_id]))
        $node_names[$PB_id] = $row['PathwayB'];   
	}
	
	$sort = $order;
	
	if($order == "Adjacency")
	    $sort = "Adj_correlation";
	
	if($row[$sort] > 0)
	    $color = 'green';
	else
	    $color = 'red';
	
	$overlap_metric = number_format($row['OverlapMetric'],5);
	$corr = number_format($row['Correlation'],5);
	$adj_corr = number_format($row['Adj_correlation'],5);
	$adj = number_format($row['Adjacency'],5);
		
	$table[$target]['overlap'] = $row['Overlap'];
	$table[$target]['adj'] = $adj;
	$table[$target]['overlap_metric'] = $overlap_metric;
	$table[$target]['corr'] = $corr;
	$table[$target]['adj_corr'] = $adj_corr;
	$table[$target]['color'] = $color;
}

function add_node($row, $pathway, &$output, &$link_qty, $order)
{

    $links = &$output["links"];
    $nodes = &$output["nodes"];
    $node_list = &$output["node_list"];
    
    if($row['PA_id'] == $pathway)
    {
      $node['name'] = preg_replace('/[^0-9a-zA-Z_ -,]/',"",$row['PathwayB']);
      $target  = $row['PB_id']; 
    }  
    else
    {
      $node['name'] = preg_replace('/[^0-9a-zA-Z_ -,]/',"",$row['PathwayA']);
      $target = $row['PA_id'];
	}
	
	$node['size'] = get_size($node['name']);
	$node['id'] = "i".$target;	
	$node['type'] = get_type($node['name']);
	
	  	
	if(!in_array($target,$node_list))
	{
	  $node_list[] = $target;	  	
	  $node['group'] = 2;
	  $nodes[] = $node;
	}
	
	
	$sort = $order;
	
	if($order == "Adjacency")
	    $sort = "Adj_correlation";
	
	    
	if($row[$sort] > 0)
	    $link['lcolor'] = 'green';
	else
	    $link['lcolor'] = 'red';
	
	if(!isset($link_qty[$row['PA_id']]))
	  $link_qty[$row['PA_id']] = 1;
	else
	  $link_qty[$row['PA_id']] += 1;
	
	
	if(!isset($link_qty[$row['PB_id']]))
	  $link_qty[$row['PB_id']] = 1;
	else
	  $link_qty[$row['PB_id']] += 1;

	$link['value'] = ceil($row[$order]*100);
	$link['source'] = $row['PA_id'];
	$link['target'] = $row['PB_id'];  
	$link['lname'] = "l".$row['PA_id']."l".$row['PB_id']."l";
	$link['adj'] = number_format($row[$order],5)."";
	$links[] = $link;
}

function prune_nodes(&$output, &$link_qty)
{

  $links = &$output["links"];
  $nodes = &$output["nodes"];
  $node_list = &$output["node_list"];
  
	$links_out = array();
	$nodes_list_out = array();
	$nodes_out = array();
	
	foreach($links as &$link)
	{
	  $source = $link['source'];
	  $target = $link['target'];
	
	  if($link_qty[$source] < 2)
		   continue;
	  else if($link_qty[$target] < 2)
		   continue;
	  
	  $links_out[] = $link;		 
	  
	  if(!in_array($source,$nodes_list_out))
	    $nodes_list_out[] = $source;
	    
	  if(!in_array($target,$nodes_list_out))
	    $nodes_list_out[] = $target;
	}
	
	foreach($nodes as &$node)
	{
	  $id = substr($node['id'],1);
	  if(in_array($id,$nodes_list_out))
	  {
	    $nodes_out[] = $node;
	  }
	}

	$links = $links_out;
	$nodes = $nodes_out;
	$node_list = $nodes_list_out;

}

function reindex_nodes(&$output)
{
    $links = &$output["links"];
    $nodes = &$output["nodes"];
    
    $node_list = array();
    
    foreach($nodes as &$node)
    {
      $id = substr($node['id'],1);
      $node_list[$id] = count($node_list);
    }    
    
    foreach($links as &$link)
    {
      $link['source'] = $node_list[$link['source']];
      $link['target'] = $node_list[$link['target']];
    }
}

function getOrder($order)
{
    switch ($order){
		case 0:
			return "Adjacency";
			break;
		case 1:
			return "Correlation";
			break;
		case 2:
			return "Adj_correlation";
			break;
		case 3:
			return "OverlapMetric";	
			break;
		default:
			return "Adjacency";
			break;
    }
    
    return "";
}
