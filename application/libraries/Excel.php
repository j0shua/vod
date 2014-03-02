<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Excel {

    private $excel;
    private $sheet_index = 0;

    public function __construct() {
        // initialise the reference to the codeigniter instance
        require_once APPPATH . 'third_party/PHPExcel.php'; //require PHPExcel_1.7.9
        $this->excel = new PHPExcel();
    }

    public function load($path) {
        $filename_info = pathinfo($path);
        if ($filename_info['extension'] == 'xlsx') {
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        } else {
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        }
        $this->excel = $objReader->load($path);
    }

    public function save($path) {
        $this->excel->setActiveSheetIndex();
// Write out as the new file
        $filename_info = pathinfo($path);
        if ($filename_info['extension'] == 'xlsx') {
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        } else {
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        }
        $objWriter->save($path);
    }

    public function stream($filename, $data = null, $show_field_title = TRUE, $sheet_title = '') {
        if ($sheet_title) {
            $this->excel->getActiveSheet()->setTitle($sheet_title);
        }

        $filename_info = pathinfo($filename);
        $rowNumber = 1;
        if ($data != null) {
            $col = 'A';
            if ($show_field_title) {
                foreach ($data[0] as $key => $val) { // สร้าง column
                    $objRichText = new PHPExcel_RichText();
                    $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
                    $objPayable->getFont()->setBold(true);
                    $objPayable->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_DARKRED));
                    $this->excel->getActiveSheet()->getCell($col . $rowNumber)->setValue($objRichText);
                    //$objPHPExcel->getActiveSheet()->setCellValue($col.'1' , str_replace("_"," ",$key));
                    $col++;
                }

                $rowNumber++;
            }

            foreach ($data as $row) {
                $col = 'A'; // start at column A
                foreach ($row as $cell) {
                    $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }
        }
        header('Content-type: application/ms-excel');
        header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        header("Cache-control: private");
        if ($filename_info['extension'] == 'xlsx') {
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        } else {
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        }
        $objWriter->save("temp/$filename");
        header("location: " . base_url() . "temp/$filename");
        unlink(base_url() . "temp/$filename");
    }

    public function add_sheet($data = null, $show_field_title = TRUE, $sheet_title = '', $options = array()) {
        if ($this->sheet_index > 0) {
            $this->excel->createSheet(NULL, $this->sheet_index);
            $this->excel->setActiveSheetIndex($this->sheet_index);
        }

        if ($sheet_title) {
            $this->excel->getActiveSheet()->setTitle($sheet_title);
        }
        $rowNumber = 1;
        if ($data != null) {
            $col = 'A';
            if ($show_field_title) {
                foreach ($data[0] as $key => $val) { // สร้าง column
                    if ($options) {
                        if (isset($options['setWidth'])) {
                            $this->excel->getActiveSheet()->getColumnDimension($col)->setWidth($options['setWidth']);
                        }
                    }
                    $objRichText = new PHPExcel_RichText();
                    $objPayable = $objRichText->createTextRun(str_replace("_", " ", $key));
                    $objPayable->getFont()->setBold(true);
                    $objPayable->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_DARKRED));
                    $this->excel->getActiveSheet()->getCell($col . $rowNumber)->setValue($objRichText);
                    //$objPHPExcel->getActiveSheet()->setCellValue($col.'1' , str_replace("_"," ",$key));
                    $col++;
                }

                $rowNumber++;
            }

            foreach ($data as $row) {
                $col = 'A'; // start at column A
                foreach ($row as $cell) {
                    if ($options) {
                        if (isset($options['setWidth'])) {
                            $this->excel->getActiveSheet()->getColumnDimension($col)->setWidth($options['setWidth']);
                        }
                    }
                    $this->excel->getActiveSheet()->setCellValue($col . $rowNumber, $cell);
                    $col++;
                }
                $rowNumber++;
            }
        }
        $this->sheet_index++;
    }

    public function __call($name, $arguments) {
// make sure our child object has this method 
        if (method_exists($this->excel, $name)) {
// forward the call to our child object 
            return call_user_func_array(array($this->excel, $name), $arguments);
        }
        return null;
    }

}