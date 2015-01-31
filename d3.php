<!DOCTYPE html>

<?php
session_start();
?>
<meta charset="utf-8">
<style>

.node circle {
fill: rgba(139, 160, 223, 0.82);
stroke: rgba(141, 179, 212, 0.93);
stroke-width: 2.5px;
}

.node {
  font: 12px sans-serif;
  font-style: italic;
  font-family: cursive;

}

.link {
fill: none;
stroke: rgba(181, 243, 188, 0.81);
stroke-width: 2.5px;
}

.text {
  font-weight: bolder;
  
}

</style>
<body>
<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>

<div id="dom-target" style="display: none;">
  
</div>

<script>

var diameter = 700;

var tree = d3.layout.tree()
    .size([360, diameter / 2 - 120])
    .separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

var diagonal = d3.svg.diagonal.radial()
    .projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

var svg = d3.select("body").append("svg")
    .attr("width", diameter + 1000)
    .attr("height", diameter + 1000)
  .append("g")
    .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");


  
  var root = <?php echo json_encode($_SESSION['dthree']); ?>;


  var nodes = tree.nodes(root),
      links = tree.links(nodes);

  var link = svg.selectAll(".link")
      .data(links)
    .enter().append("path")
      .attr("class", "link")
      .attr("d", diagonal);

  var node = svg.selectAll(".node")
      .data(nodes)
    .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; });

  node.append("circle")
      .attr("r", 16);

  node.append("text")
      .attr("dy", ".31em")
      .attr("class", "text")
      .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
      .attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
      .text(function(d) { return d.name; });


d3.select(self.frameElement).style("height", (diameter + 1000) + "px");

</script>
</body>
</html>
