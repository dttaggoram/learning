<?php include('logcheck.php');?>
<?php
$query_surveys = sprintf("SELECT papername,Papers.paperid,mark,PreSurvey.studentid AS 'presurvey' ,PostSurvey.studentid AS 'postsurvey' 
FROM Papers 
INNER JOIN ClassPapers ON Papers.paperid = ClassPapers.paperid 
INNER JOIN Class ON ClassPapers.classid = Class.classid 
INNER JOIN Student ON Class.classid = Student.classid 
INNER JOIN Questions ON Papers.paperid = Questions.paperid 
LEFT JOIN Marks ON Questions.questionid = Marks.questionid 
LEFT JOIN PreSurvey ON Papers.paperid = PreSurvey.paperid 
LEFT JOIN PostSurvey ON Papers.paperid = PostSurvey.paperid 
WHERE Student.studentid = %s 
GROUP BY papername
ORDER BY ClassPapers.date DESC
LIMIT 1", GetSQLValueString($row_user['studentid'], "int"));
$surveys = mysql_query($query_surveys, $learning) or die(mysql_error());
$row_surveys = mysql_fetch_assoc($surveys);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.png">

    <title>Own Your Learning - Charts</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    <script src="js/d3.v3.min.js"></script>
    <script src="js/jquery-1.6.min.js"></script>
    <style>

#chart {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 8px;
  position: relative;
}

form {
  position: absolute;
  right: 10px;
  top: 10px;
}

.node {
  border: solid 1px white;
  font: 6px sans-serif;
  line-height: 12px;
  overflow: hidden;
  position: absolute;
  text-indent: 2px;
}

</style>



        <?php
    if ($row_surveys['presurvey'] == null) {
    echo ("<SCRIPT LANGUAGE='JavaScript'>
    window.alert('You need to complete the pre-survey first!')
    window.location.href='http://localhost/learning/surveys.php?pid=".$row_surveys['paperid']."';
    </SCRIPT>");
  }
    ?>
  </head>

  <body>
  <?php include('nav.php'); ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
         <?php include('sidebar.php'); ?> 
        </div>
        <div id='main' class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Charts</h1>
<div id="chart"></div>
<script>
var dataurl = 'chartsparse.php?pid=' + 1;

var w = ($('#main').width() - 80),
    h = 100,
    x = d3.scale.linear().range([0, w]),
    y = d3.scale.linear().range([0, h]),
    //color = d3.scale.category20c(),
    color = {algebra:'#330066',datahandling:'#993300',number:'#006633',ssm:'#999900'},
    root,
    node,
    root1,
    node1;

d3.json(dataurl, function(data) {

  var grades = [1,2,3,4,5];
  var area = ['Algebra','Data Handling','Number','SSM'];

  d = grades.map(function (e){
    return {
      name: 'flare',
      grade: e,
      children: area.map(function (f){
        var size = 0;
        var areagradesupertopics = [];

        data.filter(function(g) { return g.area == f && g.grade == e}).forEach(function (g) {
          size += Number(g.marks);
          var result = $.grep(areagradesupertopics, function(e){ return e.name == g.supertopic; });
          if (result == '') areagradesupertopics.push({name:g.supertopic, mark: Number((g.QuestionMark/g.marks)*g.topicmarks), size: Number(g.topicmarks)});
          if (result != '') areagradesupertopics[areagradesupertopics.length-1].mark = areagradesupertopics[areagradesupertopics.length-1].mark + Number((g.QuestionMark/g.marks)*g.topicmarks);
          if (result != '') areagradesupertopics[areagradesupertopics.length-1].size = areagradesupertopics[areagradesupertopics.length-1].size + Number(g.topicmarks);

          })
          return {
          name: f,
          total: size,
          color: '#fff',
          children: areagradesupertopics
          }
        })

        

        }
    
      })

//Grade A*
  node = root = d[0];
console.log(d[0]);
 var treemap = d3.layout.treemap()
    .round(false)
    .size([w, h])
    .sticky(true)
    .value(function(d) { return d.size; });
 
var nodes = treemap.nodes(root)
    .filter(function(d) { return !d.children; });

var svg = d3.select("#chart").append("div")
  .attr("id","gradeD")
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
    .style("display","block")
  .append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(.5,.5)");

  var cell = svg.selectAll("g")
      .data(nodes)
    .enter().append("svg:g")
      .attr("class", "cell")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .on("click", function(d) { return zoom(node == d.parent ? root : d.parent); });

  cell.append("svg:rect")
      .attr("width", function(d) { return d.dx - 1; })
      .attr("height", function(d) { return d.dy - 1; })
      .style("fill", function(d) { return color[d.parent.name.toLowerCase().replace(/\s/g, '')]})
      .style("opacity", function(d) { return 1-(d.mark/d.size) });

  cell.append("svg:text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .attr("fill","#fff")
      .text(function(d) { return d.name; })
      .style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; });

  svg.append("svg:rect")
      .attr("width", "50")
      .attr("height", "100")  
      .attr("x", "-50")
      .attr("y", "0")
      .attr("fill","#999");

  d3.select(window).on("click", function() { zoom(root); });

  d3.select("select").on("change", function() {
    treemap.value(this.value == "size" ? size : count).nodes(root);
    zoom(node);
  });

//Grade A
  node1 = root1 = d[1];
  
 var treemap1 = d3.layout.treemap()
    .round(false)
    .size([w, h])
    .sticky(true)
    .value(function(d) { return d.size; });

var nodes1 = treemap1.nodes(root1)
      .filter(function(d) { return !d.children; });

var svg1 = d3.select("#chart").append("div")
  .attr("id","gradeC")
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
    .style("display","block")
  .append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(.5,.5)");

var cell1 = svg1.selectAll("g")
      .data(nodes1)
      .enter().append("svg:g")
      .attr("class", "cell")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .on("click", function(d) { return zoom(node1 == d.parent ? root1 : d.parent); });

  cell1.append("svg:rect")
      .attr("width", function(d) { return d.dx - 1; })
      .attr("height", function(d) { return d.dy - 1; })
      .style("fill", function(d) { return color[d.parent.name.toLowerCase().replace(/\s/g, '')]})
      .style("opacity", function(d) { return 1-(d.mark/d.size) });

  cell1.append("svg:text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .attr("fill","#fff")
      .text(function(d) { return d.name; })
      .style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; });

  d3.select(window).on("click", function() { zoom(root1); });

  d3.select("select").on("change", function() {
    treemap.value(this.value == "size" ? size : count).nodes1(root1);
    zoom(node1);
  });


//Grade C
  node2 = root2 = d[2];
  
 var treemap2 = d3.layout.treemap()
    .round(false)
    .size([w, h])
    .sticky(true)
    .value(function(d) { return d.size; });

var nodes2 = treemap2.nodes(root2)
      .filter(function(d) { return !d.children; });

var svg2 = d3.select("#chart").append("div")
  .attr("id","gradeC")
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
  .append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(.5,.5)");

var cell2 = svg2.selectAll("g")
      .data(nodes2)
      .enter().append("svg:g")
      .attr("class", "cell")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .on("click", function(d) { return zoom(node2 == d.parent ? root2 : d.parent); });

  cell2.append("svg:rect")
      .attr("width", function(d) { return d.dx - 1; })
      .attr("height", function(d) { return d.dy - 1; })
      .style("fill", function(d) { return color[d.parent.name.toLowerCase().replace(/\s/g, '')]})
      .style("opacity", function(d) { return 1-(d.mark/d.size) });

  cell2.append("svg:text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .attr("fill","#fff")
      .text(function(d) { return d.name; })
      .style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; });

  d3.select(window).on("click", function() { zoom(root2); });

  d3.select("select").on("change", function() {
    treemap.value(this.value == "size" ? size : count).nodes2(root2);
    zoom(node2);
  });

//Grade C
  node3 = root3 = d[3];
  
 var treemap3 = d3.layout.treemap()
    .round(false)
    .size([w, h])
    .sticky(true)
    .value(function(d) { return d.size; });

var nodes3 = treemap3.nodes(root3)
      .filter(function(d) { return !d.children; });

var svg3 = d3.select("#chart").append("div")
  .attr("id","gradeC")
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
  .append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(.5,.5)");

var cell3 = svg3.selectAll("g")
      .data(nodes3)
      .enter().append("svg:g")
      .attr("class", "cell")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .on("click", function(d) { return zoom(node3 == d.parent ? root3 : d.parent); });

  cell3.append("svg:rect")
      .attr("width", function(d) { return d.dx - 1; })
      .attr("height", function(d) { return d.dy - 1; })
      .style("fill", function(d) { return color[d.parent.name.toLowerCase().replace(/\s/g, '')]})
      .style("opacity", function(d) { return 1-(d.mark/d.size) });

  cell3.append("svg:text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .attr("fill","#fff")
      .text(function(d) { return d.name; })
      .style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; });

  d3.select(window).on("click", function() { zoom(root3); });

  d3.select("select").on("change", function() {
    treemap.value(this.value == "size" ? size : count).nodes3(root3);
    zoom(node3);
  });

//Grade D
  node4 = root4 = d[4];
  
 var treemap4 = d3.layout.treemap()
    .round(false)
    .size([w, h])
    .sticky(true)
    .value(function(d) { return d.size; });

var nodes4 = treemap4.nodes(root4)
      .filter(function(d) { return !d.children; });

var svg4 = d3.select("#chart").append("div")
  .attr("id","gradeC")
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
  .append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(.5,.5)");

var cell4 = svg4.selectAll("g")
      .data(nodes4)
      .enter().append("svg:g")
      .attr("class", "cell")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .on("click", function(d) { return zoom(node4 == d.parent ? root4 : d.parent); });

  cell4.append("svg:rect")
      .attr("width", function(d) { return d.dx - 1; })
      .attr("height", function(d) { return d.dy - 1; })
      .style("fill", function(d) { return color[d.parent.name.toLowerCase().replace(/\s/g, '')]})
      .style("opacity", function(d) { return 1-(d.mark/d.size) });

  cell4.append("svg:text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .attr("fill","#fff")
      .text(function(d) { return d.name; })
      .style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; });

  d3.select(window).on("click", function() { zoom(root4); });

  d3.select("select").on("change", function() {
    treemap.value(this.value == "size" ? size : count).nodes4(root4);
    zoom(node4);
  });

function size(d) {
  return d.size;
}

function count(d) {
  return 1;
}

function zoom(d) {
  var kx = w / d.dx, ky = h / d.dy;
  x.domain([d.x, d.x + d.dx]);
  y.domain([d.y, d.y + d.dy]);

  var t = svg.selectAll("g.cell").transition()
      .duration(d3.event.altKey ? 7500 : 750)
      .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

  t.select("rect")
      .attr("width", function(d) { return kx * d.dx - 1; })
      .attr("height", function(d) { return ky * d.dy - 1; })

  t.select("text")
      .attr("x", function(d) { return kx * d.dx / 2; })
      .attr("y", function(d) { return ky * d.dy / 2; })
      .style("opacity", function(d) { return kx * d.dx > d.w ? 1 : 0; });

  node = d;
  d3.event.stopPropagation();
}

});



</script>
          
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  </body>
</html>

