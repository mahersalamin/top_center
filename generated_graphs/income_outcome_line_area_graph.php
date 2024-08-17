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

// Calculate cumulative totals
$cumulativeIncome = array();
$cumulativeOutcome = array();
$incomeTotal = 0;
$outcomeTotal = 0;

foreach ($incomeAmounts as $amount) {
    $incomeTotal += $amount;
    $cumulativeIncome[] = $incomeTotal;
}

foreach ($outcomeAmounts as $amount) {
    $outcomeTotal += $amount;
    $cumulativeOutcome[] = $outcomeTotal;
}

// Create the graph and set its scale
$graph = new Graph(800, 600);
$graph->SetScale("textlin");

// Set titles and theme
$graph->title->Set('Cumulative Income and Outcome');
$graph->xaxis->title->Set('Date');
$graph->yaxis->title->Set('Cumulative Amount');
$graph->SetTheme(new UniversalTheme);

// Set the x-axis labels (dates)
$graph->xaxis->SetTickLabels($incomeDates);

// Create the line plots
$incomeLinePlot = new LinePlot($cumulativeIncome);
$incomeLinePlot->SetFillColor("blue@0.5");
$incomeLinePlot->SetLegend('Cumulative Income');
$incomeLinePlot->SetColor("blue");

$outcomeLinePlot = new LinePlot($cumulativeOutcome);
$outcomeLinePlot->SetFillColor("red@0.5");
$outcomeLinePlot->SetLegend('Cumulative Outcome');
$outcomeLinePlot->SetColor("red");

// Add the plots to the graph
$graph->Add($incomeLinePlot);
$graph->Add($outcomeLinePlot);

// Output the graph directly to the browser
$graph->Stroke();
