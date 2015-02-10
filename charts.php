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
ORDER BY ClassPapers.date DESC", GetSQLValueString($row_user['studentid'], "int"));
$surveys = mysql_query($query_surveys, $learning) or die(mysql_error());
$row_surveys = mysql_fetch_assoc($surveys);

$pid = "-1";
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
}

$query_feedback = sprintf("SELECT Topics.topicid,area,supertopic,topic,mwclip,((SUM(mark)/SUM(marks))) AS 'score', grade
FROM Topics
JOIN QuestionTopics ON Topics.topicid = QuestionTopics.topicid
JOIN Questions ON QuestionTopics.questionid = Questions.questionid
JOIN ClassQuestionsLearnt ON Questions.questionid = ClassQuestionsLearnt.questionid
JOIN Marks ON Questions.questionid = Marks.questionid
WHERE Marks.studentid = %s AND learnt = 1 AND Questions.paperid = %s
GROUP BY Topics.topicid
ORDER BY area,supertopic,grade DESC,score", GetSQLValueString($row_user['studentid'], "int"),GetSQLValueString($pid, "int"));
$feedback = mysql_query($query_feedback, $learning) or die(mysql_error());
$row_feedback = mysql_fetch_assoc($feedback);

$data = array(); 
    for ($x = 0; $x < mysql_num_rows($feedback); $x++) {
        $data[] = mysql_fetch_assoc($feedback);
    }


$query_score = sprintf("SELECT SUM(mark) AS score 
FROM Marks 
INNER JOIN Questions ON Marks.questionid = Questions.questionid
WHERE Marks.studentid = %s AND Questions.paperid = %s
", GetSQLValueString($row_user['studentid'], "int"),GetSQLValueString($pid, "int"));
$score = mysql_query($query_score, $learning) or die(mysql_error());
$row_score = mysql_fetch_assoc($score);

$query_grade = sprintf("SELECT * 
FROM GradeBoundaries 
WHERE paperid = %s AND marks <=
(SELECT SUM(mark) AS 'score' 
FROM Marks 
INNER JOIN Questions ON Marks.questionid = Questions.questionid
WHERE Marks.studentid = %s AND Questions.paperid = %s)
ORDER BY marks DESC
LIMIT 1", GetSQLValueString($pid, "int"), GetSQLValueString($row_user['studentid'], "int"),GetSQLValueString($pid, "int"));
$grade = mysql_query($query_grade, $learning) or die(mysql_error());
$row_grade = mysql_fetch_assoc($grade);


$query_nextgrade = sprintf("SELECT * 
FROM GradeBoundaries 
WHERE paperid = %s AND marks >
(SELECT SUM(mark) AS 'score' 
FROM Marks 
INNER JOIN Questions ON Marks.questionid = Questions.questionid
WHERE Marks.studentid = %s AND Questions.paperid = %s)
ORDER BY marks ASC
LIMIT 1", GetSQLValueString($pid, "int"), GetSQLValueString($row_user['studentid'], "int"),GetSQLValueString($pid, "int"));
$nextgrade = mysql_query($query_nextgrade, $learning) or die(mysql_error());
$row_nextgrade = mysql_fetch_assoc($nextgrade);

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
	<?php include("stylesheets.php");?>



    <style>

#chart {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 8px;
  position: relative;
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
          	<div class="page-header"><span class="h1">Charts</span>
			<form method="GET" action="charts.php" class="form-inline pull-right">
			Select a paper: 
	          		<select name="pid" class="form-control">
	                  <?php
	                  	mysql_data_seek($row_surveys);
						echo "<option>Please select a paper</option>";
	                  do {
	                    echo "<option value=".$row_surveys['paperid'];
	                    if ($_GET['pid'] == $row_surveys['paperid']) echo " selected ";
	                    echo ">".$row_surveys['papername']."</option>";
	                    } while ($row_surveys = mysql_fetch_assoc($surveys));
	                  
	                  ?>
	            </select>
	            <button type="submit" class="btn btn-default">Select</button><span style="margin-left:50px"></span>
	          	<span class="pull-right">All Grades - <input type="checkbox" id="gradebutton" data-on-text="All" data-off-text="My" data-off-color="warning"> - My Grades</span>
          	</form>
          	</div>
          	<div class="row" style="height:250px">
          		<div class="col-md-4 text-center">
	          		<h4>You achieved a<br />
	          		<span class="h1"><?php echo $row_grade['boundary'];?></span><br />
	          		<?php echo $row_score['score'];?>/200<br /><br />
	          		Get <?php echo $row_nextgrade['marks']-$row_score['score']." more marks<br />and you'll get ".$row_nextgrade['boundary'];?>
	          		</h4>
          		</div>
          		<div class="col-md-7">
          			<div id="legend">
          			<br />
	          		<ul class="list-group">
					  <li class="list-group-item gradientalgebra">
					  	<center>
					  	<ul class="list-inline">
					  	<li class="pull-left" style="color:#999; opactiy:0.5;">Most correct</li>
					    <li style=" text-shadow: 0px 0px 10px #fff;"><strong>Algebra</strong></li>
					    <li class="pull-right" style="color:#fff">Revise more</li>
					    </ul>
					    </center>
					  </li>
					  <li class="list-group-item gradientnumber">
					  	<center>
					  	<ul class="list-inline">
					  	<li class="pull-left" style="color:#999; opactiy:0.5;">Most correct</li>
					    <li style=" text-shadow: 0px 0px 10px #fff;"><strong>Number</strong></li>
					    <li class="pull-right" style="color:#fff">Revise more</li>
					    </ul>
					    </center>
					  </li>
					  <li class="list-group-item gradientssm">
					  	<center>
					  	<ul class="list-inline">
					  	<li class="pull-left" style="color:#999; opactiy:0.5;">Most correct</li>
					    <li style=" text-shadow: 0px 0px 10px #fff;"><strong>Shape, Space &amp; Measure</strong></li>
					    <li class="pull-right" style="color:#fff">Revise more</li>
					    </ul>
					    </center>
					  </li>
					  <li class="list-group-item gradientdata">
					  	<center>
					  	<ul class="list-inline">
					  	<li class="pull-left" style="color:#999; opactiy:0.5;">Most correct</li>
					    <li style=" text-shadow: 0px 0px 10px #fff;"><strong>Data Handling</strong></li>
					    <li class="pull-right" style="color:#fff">Revise more</li>
					    </ul>
					    </center>
					  </li>
					</ul>
					</div>
					<div id="details" class="hidden">
					</div>
          		</div>

          	</div>
          	<br />
		 	<div id="chart">
			</div>
        </div>
      </div>
    </div>

<?php include("scripts.php");?>
<script>
   	var letter = '<?php echo $row_grade['boundary'];?>';
	var number = letter.charCodeAt(0)-64;
	var feedback = <?php echo json_encode($data);?>

var dataurl = 'chartsparse.php?pid=' + <?php echo $_GET['pid'];?>;

var w = ($('#main').width() - 80),
    h = 70,
    x = d3.scale.linear().range([0, w]),
    y = d3.scale.linear().range([0, h]),
    //color = d3.scale.category20c(),
    color = {algebra:'#d9534f',number:'#f0ad4e',ssm:'#5cb85c',datahandling:'#337ab7'},
    root,
    node;

d3.json(dataurl, function(data) {

	var grades = [1,2,3,4,5];
	var gradeletters = ["A*","A","B","C","D"];
	var area = ['Algebra','Data Handling','Number','SSM'];

	d = grades.map(function (e){
		return {
			name: 'flare',
			grade: e,
			children: area.map(function (f){
				var size = 0;
				var areagradesupertopics = [];

				data.filter(function(g) { return g.area == f && g.grade == e}).forEach(function (g) {
					size += Number(g.topicmarks);
					var result = $.grep(areagradesupertopics, function(e){ return e.name == g.supertopic; });
					if (result == '' && g.learnt == 0) areagradesupertopics.push({name:g.supertopic, mark: Number(g.topicmarks), size: Number(g.topicmarks)});
					if (result == '' && g.learnt == 1) areagradesupertopics.push({name:g.supertopic, mark: Number((g.QuestionMark/g.marks)*g.topicmarks), size: Number(g.topicmarks)});
					if (result != '' && g.learnt == 0) { areagradesupertopics[areagradesupertopics.length-1].mark = areagradesupertopics[areagradesupertopics.length-1].mark + Number(g.topicmarks); }
					if (result != '' && g.learnt == 1) { areagradesupertopics[areagradesupertopics.length-1].mark = areagradesupertopics[areagradesupertopics.length-1].mark + Number((g.QuestionMark/g.marks)*g.topicmarks); }
					if (result != '') areagradesupertopics[areagradesupertopics.length-1].size = areagradesupertopics[areagradesupertopics.length-1].size + Number(g.topicmarks);

					})
					return {
					name: f,
					total: size,
					color: '#fff',
					children: areagradesupertopics,
					}
				})

				

				}
		
			})
//Grade A*
  node = root = d;
for (a=0; a<5; a++) {

 var treemap = d3.layout.treemap()
    .round(false)
    .size([w, h])
    .sticky(true)
    .value(function(d) { return d.size; });
 
var nodes = treemap.nodes(root[a])
    .filter(function(d) { return !d.children; });

var svg = d3.select("#chart").append("div")
	.attr("id","divgrade" + a)
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
    .style("display","block")
  .append("svg:svg")
	.attr("id","grade" + a)
    .attr("width", w+50)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(25,.5)");

  var cell = svg.selectAll("g")
      .data(nodes)
    .enter().append("svg:g")
      .attr("class", "cell")
      .attr("transform", function(d) { return "translate(" + d.x +  "," + d.y + ")"; })
      .on("click", function(d) { return zoom(node == d.parent ? root[0] : d.parent,d.parent.parent.grade,d.name); });



  cell.append("svg:rect")
      .attr("width", function(d) { return d.dx - 1; })
      .attr("height", function(d) { return d.dy - 1; })
      .attr("x", 0)
      .attr("y", 0)
      .style("fill", function(d) { return color[d.parent.name.toLowerCase().replace(/\s/g, '')]})
      .style("opacity", function(d) { return 1.1-(d.mark/d.size) });

  cell.append("svg:text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35m")
      .attr("text-anchor", "middle")
      .attr("fill", function(d) { return (d.mark/d.size) < 0.7 ? "#fff" : "#999" })
      .attr("font-size","1.5em")
      .text(function(d) { return d.name; })
      .style("opacity", function(d) { d.w = this.getComputedTextLength(); return d.dx > d.w ? 1 : 0; });

  svg.append("svg:rect")
      .attr("width", "25")
      .attr("height", "100")  
      .attr("x", "-25")
      .attr("y", "0")
      .attr("fill","#999")
  svg.append("svg:text")
      .attr("x", "-12.5")
      .attr("y", h/2)
      .attr("dy", ".35em")
      .attr("fill","#fff")
      .attr("text-anchor", "middle")
      .attr("font-size","1.5em")
      .text(function(d) { return gradeletters[a]});

  svg.append("svg:rect")
      .attr("width", "25")
      .attr("height", "100")  
      .attr("x", w)
      .attr("y", "0")
      .attr("fill","#999")
  svg.append("svg:text")
      .attr("x", w+12.5)
      .attr("y", h/2)
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .attr("fill","#fff")
      .attr("font-size","1.5em")
      .text(function(d) { return gradeletters[a]});




	$(document).ready(function(){
	if (number >= 2) $('#divgrade0').addClass("hidden");
    if (number >= 3) $('#divgrade1').addClass("hidden");
    if (number >= 4) $('#divgrade2').addClass("hidden");
	});

}


function size(d) {
  return d.size;
}

function count(d) {
  return 1;
}

function zoom(d,i,j) {
  var kx = w / d.dx, ky = h / d.dy;
  x.domain([d.x, d.x + d.dx]);
  y.domain([d.y, d.y + d.dy]);

var sps = "<h4>" + d.name + " - " + j + "</h4><table class='table col-md-11'>";
  for (b=0;b<feedback.length;b++) {
  	if (feedback[b].supertopic == j && feedback[b].area == d.name ) {
  		if (feedback[b].grade == i) {
  			sps += "<tr class='"

  			if(d.name == "Algebra") sps += "danger";
  			if(d.name == "Number") sps += "warning";
  			if(d.name == "SSM") sps += "success";
  			if(d.name == "Data Handling") sps += "info";

  		sps += "'><td class='col-md-8'><a target='_blank' href='http://www.mathswatchvle.com/video/clips/" + feedback[b].mwclip + ".swf'>" + feedback[b].topic + "</a> - <strong>" 
  		+ gradeletters[feedback[b].grade-1] + "</strong></td>";
  		} else {
  		sps += "<tr><td class='col-md-4'><a target='_blank' href='http://www.mathswatchvle.com/video/clips/" + feedback[b].mwclip + ".swf'>" + feedback[b].topic + "</a> - <strong>" 
  		+ gradeletters[feedback[b].grade-1] + "</strong></td>";
  		}	
  		sps += "<td class='col-md-6'><div class='progress'>"		  
  			+ "<div class='progress-bar ";
  			if(d.name == "Algebra") sps += "progress-bar-danger";
  			if(d.name == "Number") sps += "progress-bar-warning";
  			if(d.name == "SSM") sps += "progress-bar-success";
  			sps += " progress-bar-striped active' role='progressbar' aria-valuenow='" + parseInt(feedback[b].score*100) + "' aria-valuemin='0' aria-valuemax='100' style='width: " + parseInt(feedback[b].score*100) + "%'>"
		    + parseInt(feedback[b].score*100) + "% Correct"
		  + "</td></tr></div>";
  	}
  }
sps += "</table>";

  if (d.name != "flare") { $('#legend').addClass("hidden"); $('#details').removeClass("hidden").html(sps); }
  if (d.name == "flare") { $('#legend').removeClass("hidden"); $('#details').addClass("hidden"); }

  var t = d3.select("#grade"+(i-1)).selectAll("g.cell")
  		.transition()
     	.duration(750)
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


	$('#gradebutton').on('switchChange.bootstrapSwitch', function () {

    if (number >= 2) $('#divgrade0').toggleClass("hidden");
    if (number >= 3) $('#divgrade1').toggleClass("hidden");
    if (number >= 4) $('#divgrade2').toggleClass("hidden");

	});    
	</script>
  </body>
</html>

