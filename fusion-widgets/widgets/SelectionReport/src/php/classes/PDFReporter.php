<?php
include_once 'DefaultPHPExcelReporter.php';
include_once 'lib/PHPExcel/PHPExcel.php';

/**
 * Class responsible for generating of PDF format report.
 */
class PDFReporter extends DefaultPHPExcelReporter {
    function __construct() {
        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
        $rendererLibrary = 'mPDF';
        $rendererLibraryPath = dirname(__FILE__) . '/../lib/' . $rendererLibrary;

        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
            die('Cannot set the PHPExcel PDF renderer.');
        }

        parent::__construct();
    }

    /**
     * Generate a selection report using PDF format.
     */
    public function generateReport() {
        // Redirect output to a client's web browser (PDF)
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment;filename="' . $this->reportFileName . '.pdf"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'PDF');
        $objWriter->save('php://output');
    }
}
