<?php
namespace Jenga\App\Html;

use Jenga\App\Request\Facade\Sanitize;
use PHPExcel;

class Excel extends \PHPExcel{
    
    public $creator;
    public $title;
    public static $doc;
    public $dateformat;
    
    public $excelFile;
    public $file_ext;

    public $worksheet;

    public function __construct($creator = '', $title = '') {
        
        parent::__construct();
        
        if($creator != ''){
            
            $this->creator = $creator;

            if($title!='')            
                $this->title = $title;
            else            
                $this->title = $creator;

            self::$doc = $this;
            self::$doc->getProperties()->setCreator($creator)->setTitle($title);
        }
        
        $this->worksheet = new \stdClass();
        $this->dateformat = 'dd/MM/YY';
    }
    
    public function filename(){
        
        $cleanname = Sanitize::xss_clean([$this->title]);
        return ucfirst($cleanname[0]);
    }
    
    /**
     * Generates document based on documents sent. The filename is generated from the title
     * 
     * @param array $columns Array which holds column values
     * @param object $rowdata Object which holds the row information
     * @return doc The generated excel document
     */
    public function generateDoc($columns, $rowdata, $format = 'excel', $title = null){
        
        $col = 0; $row = 1;

        if(!is_null($title)) {
            self::$doc->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $title);
        }

        $col = 0; $row = 3;
        foreach($columns as $key => $fullname){

            self::$doc->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $fullname);
            self::$doc->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(TRUE);

            $col++;
        }

        //move to the next row
        $row++;

        $col = 0;
        foreach($rowdata as $data){
            
            $keys = array_keys($columns);

            foreach($keys as $value){
                
                self::$doc->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->{$value});
                $col++;
            }

            $row++;
            $col = 0;
        }

        if($format == 'excel'){
            
            $exformat = 'Excel5';
            $extension = '.xls';
        }
        elseif($format == 'csv'){
            
            $exformat = 'CSV';
            $extension = '.csv';
        }
        
        // Redirect output to a client's web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.  self::$doc->filename().$extension.'"');
        header('Cache-Control: max-age=0');

        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        $objWriter = \PHPExcel_IOFactory::createWriter(self::$doc, $exformat);
        $objWriter->save('php://output');
    }
    
    /**
     * Imports the document into the PHPExcel processing environment
     * @param type $file
     */
    public function importDoc($file){
        
        $this->file_ext = $this->getExtension($file);
        $this->excelFile = \PHPExcel_IOFactory::load($file);
        
        return $this->parseDocument();
    }
    
    /**
     * Process the imported document
     */
    public function parseDocument() {
        
        foreach ($this->excelFile->getWorksheetIterator() as $worksheet) {
            
            $worksheetTitle     = $worksheet->getTitle();
            
            $highestRow         = $worksheet->getHighestRow(); // e.g. 10
            $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
            
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            $nrColumns = ord($highestColumn) - 64;
            
            $this->worksheet->title = $worksheetTitle;
            $this->worksheet->columncount = $nrColumns;
            $this->worksheet->rowcount = $highestRow;
            
            for ($row = 1; $row <= $highestRow; ++ $row) {
                
                for ($col = 0; $col < $highestColumnIndex; ++ $col) {
                    
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    $val = $cell->getValue();
                    
                    //get the columns
                    if($row == 1){                        
                        $this->worksheet->columns[] = $val;
                    }                    
                    
                    if(\PHPExcel_Shared_Date::isDateTime($cell)){
                        $this->worksheet->rows[$col.','.$row] = \PHPExcel_Shared_Date::ExcelToPHP($val);
                    }
                    else{
                        $this->worksheet->rows[$col.','.$row] = $val;
                    }
                    
                    $this->worksheet->dataTypes[$col.','.$row] = $cell->getDataType();
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Returns the file extension
     * @param type $file
     * @return type
     */
    public function getExtension($file){
        
        return end(explode('.', $file));
    }
}