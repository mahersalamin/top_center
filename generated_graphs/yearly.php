<?php

require_once('../MyDB.php');
require_once('../src/jpgraph.php');
require_once('../src/jpgraph_line.php'); // For spline graph

// Set the content type to image/png
header('Content-Type: image/png');

// Get year from the query parameters
$year = $_GET['year'] ?? date('Y');

// Validate input
if (!preg_match('/^\d{4}$/', $year)) {
    die('Invalid year.');
}

// Initialize database and fetch income data
$db = new MyDB();
$incomeData = $db->getYearlyIncomeGraph($year); // Update this method to filter by year

// Check if data is available
if (empty($incomeData)) {
    // Create a graph with a message for no data
    $graph = new Graph(800, 600);
    $graph->SetScale('textlin');
    $graph->title->Set('No Data Available');
    $graph->xaxis->title->Set('Month');
    $graph->yaxis->title->Set('Amount');
    $graph->SetTheme(new UniversalTheme);

    // Add dummy data
    $dummyPlot = new LinePlot([0]);
    $dummyPlot->SetColor('red');
    $dummyPlot->SetLegend('No Data Available');
    $graph->Add($dummyPlot);

    // Output the graph
    $graph->Stroke();
    exit;
}

// Arrays to hold months and aggregated amounts
$months = [];
$amounts = array_fill(1, 12, 0); // Initialize array with 12 months

// Process the data to aggregate amounts by month
foreach ($incomeData as $row) {
    $date = new DateTime($row['date']);
    $month = $date->format('n'); // Numeric month without leading zeros

    // Aggregate amounts for each month
    $amounts[$month] += (float)$row['amount'];
}

// Prepare data arrays for plotting
$monthLabels = [];
for ($m = 1; $m <= 12; $m++) {
    $monthLabels[] = DateTime::createFromFormat('!m', $m)->format('F');
}
$amountValues = array_values($amounts); // Y-axis values (amounts)

// Create the graph and set its scale
$graph = new Graph(800, 600);
$graph->SetScale('textlin');

// Set titles and theme
$graph->title->Set('Yearly Income Spline Graph for ' . $year);
$graph->xaxis->title->Set('Month');
$graph->yaxis->title->Set('Amount');
$graph->SetTheme(new UniversalTheme);

// Set the x-axis labels (months)
$graph->xaxis->SetTickLabels($monthLabels);

// Create the spline plot
$splinePlot = new LinePlot($amountValues);
$splinePlot->SetLegend('Monthly Income');
$splinePlot->SetColor('blue');
$splinePlot->SetWeight(2); // Line weight
$splinePlot->SetStyle('solid'); // Optional: set line style

// Add the plot to the graph
$graph->Add($splinePlot);

// Output the graph directly to the browser
$graph->Stroke();

?>
