<?php

class SelectionDataCollector {
    private $mapName;
    private $sessionId;
    private $reportFormat;
    private $fileName;
    private $selection;

    function __construct($mapName, $sessionId, $reportFormat, $fileName) {
        $this->mapName = $mapName;
        $this->sessionId = $sessionId;
        $this->reportFormat = $reportFormat;
        $this->fileName = $fileName;

        $cred = new MgUserInformation($this->sessionId);
        // connect to the site and get a feature service and a resource service instances
        $siteConnection = new MgSiteConnection();
        $siteConnection->Open($cred);

        $map = new MgMap($siteConnection);
        $resourceService = $siteConnection->CreateService(MgServiceType::ResourceService);
        $featureService = $siteConnection->CreateService(MgServiceType::FeatureService);
        $map->Open($resourceService, $this->mapName);
        $this->selection = new MgSelection($map);
        $this->selection->Open($resourceService, $this->mapName);
    }

    private function propertyValueFromFeatureReader($featureReader, $propertyType, $propertyName) {
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

    public function exportData() {
        $reportClassName = $this->reportFormat . 'Reporter';
        // Check that the reporter class exists before trying to use it.
        if (class_exists($reportClassName) == false) {
            die('Report format is unsupported. Please check the ReportFormat widget parameter for supported values.');
        }

        // Initialize the reporter based on the report format.
        $report = new $reportClassName();

        $report->setReportFileName($this->fileName);

        $numberOfLayers = $this->selection->GetLayers()->GetCount();
        $report->setNumberOfLayers($numberOfLayers);
        for ($i = 0; $i < $numberOfLayers; $i++) {
            $layer = $this->selection->GetLayers()->GetItem($i);
            $layerLegendLabel = $layer->GetLegendLabel();

            $layerClassName = $layer->GetFeatureClassName();
            $layerSelectedFeatures = $this->selection->GetSelectedFeatures($layer, $layerClassName, true);

            $isLayerWritten = false;
            $isHeaderWritten = false;
            while ($layerSelectedFeatures->ReadNext()) {
                $propertyCount = $layerSelectedFeatures->GetPropertyCount();

                if ($isLayerWritten == false) {
                    $report->reportLayerName($layerLegendLabel, $propertyCount);
                    $isLayerWritten = true;
                }

                if ($isHeaderWritten == false) {
                    $layerHeader = array();
                    for ($j = 0; $j < $propertyCount; $j++) {
                        $propertyName = $layerSelectedFeatures->GetPropertyName($j);
                        array_push($layerHeader, $propertyName);
                    }

                    $report->reportHeaderForLayer($layerLegendLabel, $layerHeader);
                    $isHeaderWritten = true;
                }

                $layerRecord = array();
                for ($j = 0; $j < $propertyCount; $j++) {
                    $propertyName = $layerSelectedFeatures->GetPropertyName($j);
                    $propertyType = $layerSelectedFeatures->GetPropertyType($propertyName);

                    $propertyValue = $this->propertyValueFromFeatureReader($layerSelectedFeatures, $propertyType, $propertyName);

                    array_push($layerRecord, $propertyValue);
                }

                $report->reportRecordForLayer($layerLegendLabel, $layerRecord);
            }
        }

        $report->generateReport();
    }
}
