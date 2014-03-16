<?php

function getRequestParameters() {
    global $params;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $params = $_POST;
    } else {
        $params = $_GET;
    }

    global $mapName, $sessionId;

    $mapName = $params['mapName'];
    $sessionId = $params['sessionId'];
}

function propertyValueFromFeatureReader($featureReader, $propertyType, $propertyName) {
    switch ($propertyType) {
        case MgPropertyType::Null :
            break;
       case MgPropertyType::Boolean :
              $val = $featureReader->GetBoolean($propertyName);
              break;
       case MgPropertyType::Byte :
              $val = $featureReader->GetByte($propertyName);
              break;
       case MgPropertyType::DateTime :
              $val = $featureReader->GetDateTime($propertyName);
              break;
       case MgPropertyType::Single :
              $val = $featureReader->GetSingle($propertyName);
              break;
       case MgPropertyType::Double :
              $val = $featureReader->GetDouble($propertyName);
              break;
       case MgPropertyType::Int16 :
              $val = $featureReader->GetInt16($propertyName);
              break;
       case MgPropertyType::Int32 :
              $val = $featureReader->GetInt32($propertyName);
              break;
       case MgPropertyType::Int64 :
              $val = $featureReader->GetInt64($propertyName);
              break;
       case MgPropertyType::String :
              $val = $featureReader->GetString($propertyName);
              break;
       case MgPropertyType::Blob :
              break;
       case MgPropertyType::Clob :
              break;
       case MgPropertyType::Feature :
              $val = $featureReader->GetFeatureObject($propertyName);
             break;
       case MgPropertyType::Geometry :
              $val = $featureReader->GetGeometry($propertyName);
              break;
       case MgPropertyType::Raster :
              $val = $featureReader->GetRaster($propertyName);
              break;
    }

    return $val;
}

function exportData($selection) {
    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);

    $currentRow = 1;
    $numberOfLayers = $selection->GetLayers()->GetCount();
    for ($i = 0; $i < $numberOfLayers; $i++) {
        $isPropertiesHeaderWritten = false;
        $layer = $selection->GetLayers()->GetItem($i);
        $layerLegendLabel = $layer->GetLegendLabel();

        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $currentRow, $layerLegendLabel);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $currentRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow(0, $currentRow)->getFill()->getStartColor()->setARGB('FFC0C0C0');

        $layerClassName = $layer->GetFeatureClassName();
        $layerSelectedFeatures = $selection->GetSelectedFeatures($layer, $layerClassName, true);

        $currentRow++;
        while ($layerSelectedFeatures->ReadNext()) {
            $propertyCount = $layerSelectedFeatures->GetPropertyCount();
            if ($isPropertiesHeaderWritten == false) {
                for ($j = 0; $j < $propertyCount; $j++) {
                    $propertyName = $layerSelectedFeatures->GetPropertyName($j);

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, $currentRow, $propertyName);
                }

                $isPropertiesHeaderWritten = true;
                $currentRow++;
            }

            for ($j = 0; $j < $propertyCount; $j++) {
                $propertyName = $layerSelectedFeatures->GetPropertyName($j);
                $propertyType = $layerSelectedFeatures->GetPropertyType($propertyName);

                $propertyValue = propertyValueFromFeatureReader($layerSelectedFeatures, $propertyType, $propertyName);

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($j, $currentRow, $propertyValue);
            }

            $currentRow++;
        }
    }

    // Redirect output to a client's web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="report.xlsx"');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}

$fusionMGpath = '../../layers/MapGuide/php/';
$PHPExcelPath = 'lib/PHPExcel/';

include $fusionMGpath . 'Common.php';
include $PHPExcelPath . 'PHPExcel.php';

if(InitializationErrorOccurred()) {
    DisplayInitializationErrorHTML();
    exit;
}

$mapName = "";
$sessionId = "";

getRequestParameters();

$cred = new MgUserInformation($sessionId);
//connect to the site and get a feature service and a resource service instances
$siteConnection = new MgSiteConnection();
$siteConnection->Open($cred);

$map = new MgMap($siteConnection);
$resourceService = $siteConnection->CreateService(MgServiceType::ResourceService);
$featureService = $siteConnection->CreateService(MgServiceType::FeatureService);
$map->Open($resourceService, $mapName);
$selection = new MgSelection($map);
$selection->Open($resourceService, $mapName);

exportData($selection);

?>
