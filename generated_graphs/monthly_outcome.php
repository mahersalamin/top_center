<?php

require_once('../MyDB.php');
require_once('../src/jpgraph.php');
require_once('../src/jpgraph_line.php'); // For spline graph

// Set the content type to image/png
header('Content-Type: image/png');

// Get month and year from the query parameters
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

// Validate inputs
if (!preg_match('/^\d{4}$/', $year) || !preg_match('/^\d{2}$/', $month)) {
    die('Invalid year or month.');
}

// Initialize database and fetch outcome data
$db = new MyDB();
$outcomeData = $db->getOutcomeGraph($year, $month); // Update this method to filter by year and month

// Arrays to hold days and aggregated amounts
$days = [];
$amounts = [];

// Process the data to aggregate amounts by day
foreach ($outcomeData as $row) {
    // Convert date to day
    $date = new DateTime($row['date']);
    $day = $date->format('j'); // Day of the month without leading zeros

    // If the day is not already in the array, initialize it
    if (!isset($days[$day])) {
        $days[$day] = 0;
    }

    // Aggregate amounts for each day
    $days[$day] += (float)$row['amount'];
}

// Prepare data arrays for plotting
$dayLabels = array_keys($days); // X-axis labels (days)
$amountValues = array_values($days); // Y-axis values (amounts)

// Create the graph and set its scale
$graph = new Graph(800, 600);
$graph->SetScale("textlin");

// Set titles and theme
$graph->title->Set('Outcome Spline Graph for ' . DateTime::createFromFormat('!m', $month)->format('F Y'));
$graph->xaxis->title->Set('Day of Month');
$graph->yaxis->title->Set('Amount');
$graph->SetTheme(new UniversalTheme);

// Set the x-axis labels (days)
$graph->xaxis->SetTickLabels($dayLabels);

// Create the spline plot
$splinePlot = new LinePlot($amountValues);
$splinePlot->SetLegend('Daily Outcome');
$splinePlot->SetColor("red");
$splinePlot->SetWeight(2); // Line weight

// Optionally set spline (smooth) style
$splinePlot->SetStyle('solid'); // Other styles: 'dashed', 'dotted'

// Add the plot to the graph
$graph->Add($splinePlot);

// Output the graph directly to the browser
$graph->Stroke();
