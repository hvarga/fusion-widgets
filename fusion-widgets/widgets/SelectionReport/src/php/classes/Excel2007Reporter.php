<?php
include 'DefaultPHPExcelReporter.php';
include_once 'lib/PHPExcel/PHPExcel.php';

/**
 * Class responsible for generating of Excell 2007 format report.
 */
class Excel2007Reporter extends DefaultPHPExcelReporter {
    /**
     * Generate a selection report using Excel 2007 format.
     */
    public function generateReport() {
        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $this->reportFileName .'.xlsx"');

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
}
