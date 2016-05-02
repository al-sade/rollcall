<?php
require_once("../session.php");
require_once("../vendor/autoload.php");
	require_once("../classes/class.stats.php");

	use Ghunti\HighchartsPHP\Highchart;
	use Ghunti\HighchartsPHP\HighchartJsExpr;

	$chart = new Highchart();
	$chart->includeExtraScripts();

	$chart->chart->type = "arearange";
	$chart->chart->zoomType = "x";
	$chart->title->text = "Temperature variation by day";
	$chart->xAxis->type = "datetime";
	$chart->yAxis->title->text = null;
	$chart->tooltip = array(
	    'crosshairs' => true,
	    'shared' => true,
	    'valueSuffix' => 'ºC'
	);
	$chart->legend->enabled = false;
	$chart->series[] = array(
	    'name' => 'Temperatures',
	    'data' => new HighchartJsExpr('data')
	);

	if(isset($_SESSION['lecturer'])){
		require_once("init-lecturer.php");
		$auth_user = new LECTURER();
	}else{
		require_once("init-user.php");
		$auth_user = new USER();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="../vendor/twbs/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="../components/jquery/jquery.min.js"></script>
<script src="../js/stat-charts.js	"></script>
<link rel="stylesheet" href="../style.css" type="text/css"  />
<title>welcome - <?php print($userRow['email']); ?></title>
</head>

<body>

<?php require_once('header.php');?>


    <div class="clearfix"></div>


<div class="container-fluid" style="margin-top:80px;">

    <div class="container">

			<?php
				$auth_user->createAttendanceTable($userRow['user_id']);
				$chart->printScripts();
			?>

			<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

    </div>

</div>

<script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
<div id="container"></div>
	<script type="text/javascript">
			$.getJSON('http://www.highcharts.com/samples/data/jsonp.php?filename=range.json&callback=?', function(data) {
					$('#container').highcharts(<?php echo $chart->renderOptions(); ?>)});
	</script>
</body>
</html>
