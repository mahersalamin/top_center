<?php

require_once('../MyDB.php');
require_once('../src/jpgraph.php');
require_once('../src/jpgraph_line.php'); // For spline graph

// Set the content type to image/png
//header('Content-Type: image/png');

// Get month and year from the query parameters
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');

// Validate inputs
if (!preg_match('/^\d{4}$/', $year) || !preg_match('/^\d{2}$/', $month)) {
    die('Invalid year or month.');
}

// Initialize database and fetch income data
$db = new MyDB();
$incomeData = $db->getIncomeGraph($year, $month); // Update this method to filter by year and month

// Check if data is available
if (empty($incomeData)) {
    // Create a graph with a message for no data
    $graph = new Graph(800, 600);
    $graph->SetScale('textlin');
    $graph->title->Set('No Data Available');
    $graph->xaxis->title->Set('Day of Month');
    $graph->yaxis->title->Set('Amount');
    $graph->SetTheme(new UniversalTheme);

    // Add dummy data
    $dummyPlot = new LinePlot([0]);
    $dummyPlot->SetColor('red');
    $dummyPlot->SetLegend('No Data Available');
    $dummyPlot->SetCenter();
    $graph->Add($dummyPlot);

    // Output the graph
    $graph->Stroke();

    exit;
}

// Arrays to hold days and aggregated amounts
$days = [];
$amounts = [];

// Process the data to aggregate amounts by day
foreach ($incomeData as $row) {
    // Convert date to day
    $date = new DateTime($row['date']);
    $day = $date->format('j'); // Day of the month without leading zeros

    // Aggregate amounts for each day
    if (!isset($days[$day])) {
        $days[$day] = 0;
    }
    $days[$day] += (float)$row['amount'];
}

// Prepare data arrays for plotting
$dayLabels = array_keys($days); // X-axis labels (days)
$amountValues = array_values($days); // Y-axis values (amounts)

// Create the graph and set its scale
$graph = new Graph(800, 600);
$graph->SetScale('textlin');

// Set titles and theme
$graph->title->Set('Income Spline Graph for ' . DateTime::createFromFormat('!m', $month)->format('F Y'));
$graph->xaxis->title->Set('Day of Month');
$graph->yaxis->title->Set('Amount');
$graph->SetTheme(new UniversalTheme);

// Set the x-axis labels (days)
$graph->xaxis->SetTickLabels($dayLabels);

// Create the spline plot
$splinePlot = new LinePlot($amountValues);
$splinePlot->SetLegend('Daily Income');
$splinePlot->SetColor('blue');
$splinePlot->SetCenter();
$splinePlot->SetWeight(2); // Line weight
$splinePlot->SetStyle('solid'); // Optional: set line style

// Add the plot to the graph
$graph->Add($splinePlot);

// Output the graph directly to the browser
$graph->Stroke();

?>
