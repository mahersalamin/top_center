<?php
require_once ('../MyDB.php');
require_once('../src/jpgraph.php');
require_once('../src/jpgraph_line.php');

// Set the content type to image/png
header('Content-Type: image/png');

$db = new MyDB();

$incomeData = $db->getIncomeGraph();
$outcomeData = $db->getOutcomeGraph();

$incomeDates = [];
$incomeAmounts = [];
foreach ($incomeData as $row) {
    $incomeDates[] = $row['date'];
    $incomeAmounts[] = $row['amount'];
}

// Extract outcome data
$outcomeDates = [];
$outcomeAmounts = [];
foreach ($outcomeData as $row) {
    $outcomeDates[] = $row['date'];
    $outcomeAmounts[] = $row['amount'];
}

// Create the graph and set its scale
$graph = new Graph(800, 600);
$graph->SetScale("textlin");

// Set titles and theme
$graph->title->Set('Income vs. Outcome');
$graph->xaxis->title->Set('Date');
$graph->yaxis->title->Set('Amount');
$graph->SetTheme(new UniversalTheme);

// Set the x-axis labels (dates)
$graph->xaxis->SetTickLabels($incomeDates);

// Create the spline plots
$incomePlot = new LinePlot($incomeAmounts);
$incomePlot->SetLegend('Income');
$incomePlot->SetColor("blue");

$outcomePlot = new LinePlot($outcomeAmounts);
$outcomePlot->SetLegend('Outcome');
$outcomePlot->SetColor("red");

// Add the plots to the graph
$graph->Add($incomePlot);
$graph->Add($outcomePlot);

// Output the graph
$graph->Stroke();
