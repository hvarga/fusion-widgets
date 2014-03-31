<?php

function getRequestParameters() {
    global $params;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $params = $_POST;
    } else {
        $params = $_GET;
    }

    //global $mapName, $sessionId, $reportFormat;
    global $mapName, $sessionId;

    $mapName = $params['mapName'];
    $sessionId = $params['sessionId'];
    //$reportFormat = $params['reportFormat'];
}

function propertyValueFromFeatureReader($featureReader, $propertyType, $propertyName) {
    switch ($propertyType) {
        case MgPropertyType::Null:
            break;
        case MgPropertyType::Boolean:
            $val = $featureReader->GetBoolean($propertyName);
            break;
        case MgPropertyType::Byte:
            $val = $featureReader->GetByte($propertyName);
            break;
        case MgPropertyType::DateTime:
            $val = $featureReader->GetDateTime($propertyName);
            break;
        case MgPropertyType::Single:
            $val = $featureReader->GetSingle($propertyName);
            break;
        case MgPropertyType::Double:
            $val = $featureReader->GetDouble($propertyName);
            break;
        case MgPropertyType::Int16:
            $val = $featureReader->GetInt16($propertyName);
            break;
        case MgPropertyType::Int32:
            $val = $featureReader->GetInt32($propertyName);
            break;
        case MgPropertyType::Int64:
            $val = $featureReader->GetInt64($propertyName);
            break;
        case MgPropertyType::String:
            $val = $featureReader->GetString($propertyName);
            break;
        case MgPropertyType::Blob:
            break;
        case MgPropertyType::Clob:
            break;
        case MgPropertyType::Feature:
            $val = $featureReader->GetFeatureObject($propertyName);
            break;
        case MgPropertyType::Geometry:
            $val = $featureReader->GetGeometry($propertyName);
            break;
        case MgPropertyType::Raster:
            $val = $featureReader->GetRaster($propertyName);
            break;
    }

    return $val;
}

function exportData($selection) {
    $report = new ExcelReporter();

    $numberOfLayers = $selection->GetLayers()->GetCount();
    $report->setNumberOfLayers($numberOfLayers);
    for ($i = 0; $i < $numberOfLayers; $i ++) {
        $layer = $selection->GetLayers()->GetItem($i);
        $layerLegendLabel = $layer->GetLegendLabel();
        $report->reportLayerName($layerLegendLabel);

        $layerClassName = $layer->GetFeatureClassName();
        $layerSelectedFeatures = $selection->GetSelectedFeatures($layer, $layerClassName, true);

        $isHeaderWritten = false;
        while ($layerSelectedFeatures->ReadNext()) {
            $propertyCount = $layerSelectedFeatures->GetPropertyCount();

            if ($isHeaderWritten == false) {
                $layerHeader = array();
                for ($j = 0; $j < $propertyCount; $j ++) {
                    $propertyName = $layerSelectedFeatures->GetPropertyName($j);
                    array_push($layerHeader, $propertyName);
                }

                $report->reportHeaderForLayer($layerLegendLabel, $layerHeader);
                $isHeaderWritten = true;
            }

            $layerRecord = array();
            for ($j = 0; $j < $propertyCount; $j ++) {
                $propertyName = $layerSelectedFeatures->GetPropertyName($j);
                $propertyType = $layerSelectedFeatures->GetPropertyType($propertyName);

                $propertyValue = propertyValueFromFeatureReader($layerSelectedFeatures, $propertyType, $propertyName);

                array_push($layerRecord, $propertyValue);
            }

            $report->reportRecordForLayer($layerLegendLabel, $layerRecord);
        }
    }

    $report->generateReport();
}

$fusionMGpath = '../../layers/MapGuide/php/';

include $fusionMGpath . 'Common.php';
include 'classes/ExcelReporter.php';

if (InitializationErrorOccurred()) {
    DisplayInitializationErrorHTML();
    exit();
}

$mapName = "";
$sessionId = "";
//$reportFormat = "";

getRequestParameters();

$cred = new MgUserInformation($sessionId);
// connect to the site and get a feature service and a resource service instances
$siteConnection = new MgSiteConnection();
$siteConnection->Open($cred);

$map = new MgMap($siteConnection);
$resourceService = $siteConnection->CreateService(MgServiceType::ResourceService);
$featureService = $siteConnection->CreateService(MgServiceType::FeatureService);
$map->Open($resourceService, $mapName);
$selection = new MgSelection($map);
$selection->Open($resourceService, $mapName);

exportData($selection);
