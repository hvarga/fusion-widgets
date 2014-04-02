<?php
include_once '/../ReportInterface.php';
include_once 'lib/PHPExcel/PHPExcel.php';

/**
 * Class which implements ReporterInterface using the PHPExcel library.
 * It uses the data to populate the PHPExcel object, but it is left for the user to make
 * a fine grained adaptation and to implement the generation of a report.
 */
abstract class DefaultPHPExcelReporter implements ReportInterface {
    /**
     * The PHPExcel itself.
     *
     * @link https://phpexcel.codeplex.com/
     * @var PHPExcel
     */
    protected $phpExcel;

    /**
     * Name of the report file.
     *
     * @var string
     */
    protected $reportFileName;

    /**
     * Holds the number of layers.
     *
     * @var integer
     */
    protected $numberOfLayers;
    /**
     * Represents current processing row.
     *
     * @var integer
     */
    protected $currentRow = 1;

    public static $COLOR_DARK_GREY = '919191';
    public static $COLOR_LIGHT_GREY = 'D6D6D6';

    function __construct() {
        // Create new PHPExcel object
        $this->phpExcel = new PHPExcel();
        $this->phpExcel->setActiveSheetIndex(0);
    }

    public function setReportFileName($reportFileName) {
        $this->reportFileName = $reportFileName;
    }

    public function setNumberOfLayers($numberOfLayers) {
        $this->numberOfLayers = $numberOfLayers;
    }

    public function reportLayerName($layerName, $propertyCount) {
        if ($this->currentRow > 1) {
            $this->currentRow++;
        }

        $this->phpExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $this->currentRow, $layerName);
        $this->phpExcel->getActiveSheet()->getStyleByColumnAndRow(0, $this->currentRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->phpExcel->getActiveSheet()->getStyleByColumnAndRow(0, $this->currentRow)->getFill()->getStartColor()->setRGB(self::$COLOR_DARK_GREY);

        $this->phpExcel->getActiveSheet()->mergeCellsByColumnAndRow(0, $this->currentRow, $propertyCount - 1, $this->currentRow);

        $this->currentRow++;
    }

    public function reportHeaderForLayer($layerName, $header) {
        for ($i = 0; $i < count($header); $i++) {
            $columnName = $header[$i];
            $this->phpExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $this->currentRow, $columnName);
            $this->phpExcel->getActiveSheet()->getStyleByColumnAndRow($i, $this->currentRow)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $this->phpExcel->getActiveSheet()->getStyleByColumnAndRow($i, $this->currentRow)->getFill()->getStartColor()->setRGB(self::$COLOR_LIGHT_GREY);
        }

        $this->currentRow++;
    }

    public function reportRecordForLayer($layerName, $record) {
        for ($i = 0; $i < count($record); $i++) {
            $propertyValue = $record[$i];
            $this->phpExcel->getActiveSheet()->setCellValueByColumnAndRow($i, $this->currentRow, $propertyValue);
        }

        $this->currentRow++;
    }
}
