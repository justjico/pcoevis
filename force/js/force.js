var width = 960,
    height = 500;

var color = d3.scale.category20();

var force = d3.layout.force()
    .charge(-2000)
    .linkDistance(100)
    .size([width, height]);

var svg = d3.select("#chart").append("svg")
    .attr("width", width)
    .attr("height", height)
    .attr("id","svg");

var nodeExceptions = new Array();

$(function() {
	$( "#legend" ).dialog({ autoOpen:false});
	$( "#shownames").button();
});

function showLegend(){
	$("#legend").dialog('open');
}

$(function() {
	$( "#slider-node-num" ).slider({
		range: "min",
		value: 20,
		min: 1,
		max: 632,
		slide: function( event, ui ) {
			$( "#amount" ).val( ui.value );
		},
		change: function( event, ui ) {
		chgPathway();	
		}
	});
	$( "#amount" ).val( $( "#slider-node-num" ).slider( "value" ) );
});

$(function() {
	$( "#slider-distance" ).slider({
		range: "min",
		value: 100,
		min: 50,
		max: 600,
		step: 50,
		slide: function( event, ui ) {
			$( "#amount-distance" ).val( ui.value );
		},
		change: function( event, ui ) {
		chgPathway();	
		}
	});
	$( "#amount-distance" ).val( $( "#slider-distance" ).slider( "value" ) );
});

function chgPathway()
{

var shownames = $("#shownames").is(':checked');

var select_list_field = document.getElementById('pathway');
var select_list_selected_index = select_list_field.selectedIndex;

var pathname = select_list_field.options[select_list_selected_index].text;

$("#pname").text(pathname);

reloadjson( $("#pathway").val(), pathname, $( "#slider-node-num" ).slider( "value" ), $( "#slider-distance" ).slider( "value" ), $("#edges").val(), shownames );


$("#y").load("single_table.php?pathway=" + $("#pathway").val()+"&limit="+$( "#slider-node-num" ).slider( "value" ));

}	

function addException(key)
{

  if($.inArray(key, nodeExceptions) == -1)  
    nodeExceptions.push(key);
  
  d3.select("#i" + key).remove();
  
  var s = svg.selectAll("line.link").each(function(){  
	  var name = this.id;
	  
	  if(name.indexOf("l"+key+"l") != -1)
		 svg.select("#"+this.id).remove();

  });
  
}
    
function reloadjson(pathway, name, limit, distance, edges, shownames){

if(pathway=="")
{
  pathway = 1;
  name = "Citrate cycle (TCA cycle) (KEGG)";
}

file = "nodes.php?pathway=" + escape(pathway) +"&limit="+limit + "&pathname=" + name + "&edges=" + edges  ;

d3.json(file, function(json) {

  svg.selectAll("line.link").remove();
  svg.selectAll("path").remove();
  svg.selectAll("text").remove();
  svg.selectAll("rect").remove();
  svg.selectAll("#sel").remove();

  force
      .nodes(json.nodes)
      .links(json.links)
      .linkDistance(distance)
      .start();

  $("#strongest").text(json.strongest);
  $("#weakest").text(json.weakest);
  $("#node_list").text(json.node_list);

  var link = svg.selectAll("line.link")
      .data(json.links)
    .enter().append("line")
      .attr("class", "link")
      .attr("id", function(d){ return d.lname; })
      .style("stroke-width", function(d) { return Math.sqrt(d.value); })
      .on("click.sname", function(d,i) { 
	  		
	  var x = d3.mouse(this)[0];
	  var y = d3.mouse(this)[1];
	  
	  var r = svg.append("svg:rect")
	      .attr("width",100)
	      .attr("height",20)
	      .attr("x",x-10)
	      .attr("y",y-15)
	      .attr("fill","cornflowerblue")
	      .attr("fill-opacity",.5);
	  
	  var t = svg.append("svg:text")
	      .attr("x",x)
	      .attr("y",y)
	      .attr("fill",d.lcolor)
	      .text(d.adj);
	      
	  setTimeout(function() {
        t.remove();
        r.remove();
      }, (2 * 1000));
      
      })
      .style("stroke", function(d) { return d.lcolor; });

  var node = svg.selectAll("path")
      .data(json.nodes)
    .enter().append("svg:path")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .attr("d", d3.svg.symbol()
      .size(300)
      .type(function(d) { return d.type; })
      )
      .attr("id",function(d){ return d.id; })
      .style("fill", function(d) { return color(d.group); })
      .style("stroke-width",.5)
      .style("stroke","black")
      .on("click.sname", function(d) { 
      
	  var s = d3.select("#" + d.id);
		
	  var x = s.attr("x");
	  var y = s.attr("y");
	  
	  var r = svg.append("svg:rect")
	      .attr("width",300)
	      .attr("height",20)
	      .attr("x",x-10)
	      .attr("y",y-15)
	      .attr("fill","yellow")
	      .attr("fill-opacity",.5);
	  
	  var t = svg.append("svg:text")
	      .attr("x",x)
	      .attr("y",y)
	      .attr("fill", "darkblue")
	      .text(d.name);
	      
	  setTimeout(function() {
        t.remove();
        r.remove();
      }, (2 * 1000));
      
      })
      .call(force.drag);
      
  if(shownames)
  {
	  var rect = svg.selectAll("rect")
		  .data(json.nodes)
		.enter().append("svg:rect")
			  .attr("x",0)
			  .attr("y",0)
			  .attr("fill","beige")
			  .attr("width",function(d){ return d.size; })
			  .attr("height","15")
			  .attr("stroke-width",".2px")
			  .attr("stroke","black")
			  .attr("fill-opacity",.7);
		  
	  var text = svg.selectAll("text")
		  .data(json.nodes)
		.enter().append("svg:text")
			  .attr("x",0)
			  .attr("y",0)
			  .style("fill","darkblue")
			  .style("font-family","sans-serif")
			  .style("font-size","10px")
			  .text(function(d){ return d.name; });
  }
          

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("x", function(d) { return d.x-2.5; })
    	.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
        .attr("y", function(d) { return d.y-2.5; });
        
    if(shownames)
    {
      rect.attr("transform", function(d) { return "translate(" + (d.x-15) + "," + (d.y+12) + ")"; }); 
      text.attr("transform", function(d) { return "translate(" + (d.x-10) + "," + (d.y+23) + ")"; });      
    }
  });
});

};

function selectPath(key){
  
  var s = d3.select("#i" + key);
    
  var x = s.attr("x");
  var y = s.attr("y");
  
  var cir = svg.append("circle")
        .attr("cx", x)
        .attr("cy", y)
        .attr("r", 60)
        .attr("id","sel")
        .style("fill", "none")
        .style("stroke", "black")
        .style("stroke-opacity", 0)
        .style("stroke-width", 5);
        
      cir.transition()
        .duration(750)
        .attr("r", 12)
        .style("stroke-opacity", 1);
        
    setTimeout(function() {
      cir.remove();
    }, (3 * 1000));
    
};

function stopForce()
{
    alert('Force stop!');
    //force.stop();
    force.aresume(.001);
}

function startDrag()
{
    alert('drag start');
    force.drag();
}

function removeSel()
{
   alert("removing");
   d3.selectAll("#sel").remove();

}
