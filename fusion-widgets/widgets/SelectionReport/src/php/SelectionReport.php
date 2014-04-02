<?php

function getRequestParameters() {
    global $params;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $params = $_POST;
    } else {
        $params = $_GET;
    }

    global $mapName, $sessionId, $reportFormat, $fileName;

    $mapName = $params['mapName'];
    $sessionId = $params['sessionId'];
    $reportFormat = $params['reportFormat'];
    $fileName = $params['fileName'];
}

$fusionMGpath = '../../layers/MapGuide/php/';

include $fusionMGpath . 'Common.php';
include 'classes/SelectionDataCollector.php';
foreach (glob("classes/reporters/*.php") as $filename) {
    include_once $filename;
}

if (InitializationErrorOccurred()) {
    DisplayInitializationErrorHTML();
    exit();
}

$mapName = "";
$sessionId = "";
$reportFormat = "";
$fileName = "";

getRequestParameters();

$selectionDataCollector = new SelectionDataCollector($mapName, $sessionId, $reportFormat, $fileName);
$selectionDataCollector->exportData();
