<?php


require_once ('../src/jpgraph.php');
require_once ('../src/jpgraph_line.php');
require_once ('../src/jpgraph_plotline.php');
require_once ('../src/jpgraph_plotmark.inc.php');

$data1 = array(20,15,23,15,30,22);
$data2 = array(12,9,12,8,19,14);

// Create the graph
$graph = new Graph(350,250);
$graph->SetScale("textlin");

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

$graph->img->SetAntiAliasing();
$graph->title->Set('Line/Area Graph');
$graph->SetBox(false);

$lineplot1 = new LinePlot($data1);
$lineplot2 = new LinePlot($data2);

$graph->Add($lineplot1);
$graph->Add($lineplot2);

$lineplot1->SetColor("#0000FF");
$lineplot2->SetColor("#00FF00");

// Output the graph
$graph->Stroke();
