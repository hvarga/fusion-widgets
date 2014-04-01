<?php

/**
 * Interface which provides the callback methods that will be called automatically by the SelectionReport logic. This will allow you to get all necessary data and use it to build up your report.
 *
 * Note that some methods will be called more than once. Specifically, each layer will trigger calling of reportLayerName(), reportHeaderForLayer() and reportRecordForLayer().
 */
interface ReportInterface {

    /**
     * Set the name of the report file.
     *
     * @param unknown $reportFileName
     *            The name of the report file.
     */
    public function setReportFileName($reportFileName);

    /**
     * The number of layers which have selections.
     *
     * @param integer $numberOfLayers
     *            Represents total number of layers which constitues a selection.
     */
    public function setNumberOfLayers($numberOfLayers);

    /**
     * Name of the layer which should be written to the report.
     *
     * @param string $layerName
     *            Represents the name of a layer.
     * @param integer $propertyCount
     *            Holds the number of layer properties.
     */
    public function reportLayerName($layerName, $propertyCount);

    /**
     * Layer header data which should be written to the report.
     *
     * @param string $layerName
     *            Represents the name of a layer.
     * @param array $header
     *            An array of strings. Each string represents one column.
     */
    public function reportHeaderForLayer($layerName, $header);

    /**
     * Method will be called for each selected object.
     *
     * @param string $layerName
     *            Represents the name of a layer.
     * @param array $record
     *            An array of strings. Each string represents one distinct value for a column.
     */
    public function reportRecordForLayer($layerName, $record);

    /**
     * This method will be called when the report is populated and needs to be generated.
     */
    public function generateReport();
}
