<?php

class CsvHandler  {
    private $csvPath;
    private $separator = ";";
    // CONSTRUCTOR
    // ===========
    // initializes instance
    public function CsvHandler($cPath="default.csv", $sep=";") {
        $this->csvPath = $cPath;
        $this->separator = $sep;
        if (!file_exists($cPath)) $this->writeCsv("");
    }
    
    // 'PRIVATE' FUNCTIONS
    // ===================
    // writes content in $csvPath
    private function writeCsv($content) {
        $fp = @fopen($this->csvPath, "w+")
        or $this->showError("Error: could not write CSV file");
        fwrite($fp, $content);
        fclose($fp);
    }
    // handle warnings and errors
    private function showWarning($war) {
        echo $war;
    }
    private function showError($err) {
        die ($err);
    }
    // writes csv from array of records
    private function fillFromArray($arr) {
        $content = implode("\n", $arr)."\n";
        $content = ereg_replace("\n+","\n",$content);
        $this->writeCsv($content);
    }
    // 'PUBLIC' FUNCTIONS
    // ==================
    // checks if row exists
    public function rowExists($val, $colNr=0) {
        $rows = @file($this->csvPath);
        foreach ($rows as $row) {
            $cells = explode($this->separator, $row);
            if ($cells[$colNr]==$val) return true;
            }
        return false;
    }
    public function getCellByNr($rowNr=0, $collNr=0){
        $rows = @file($this->csvPath);
        $allrows = array();
        foreach ($rows as $row) {
            $allrows[] = explode($this->separator, $row);
        }
        return $allrows[$rowNr][$collNr];
    }
    public function getNrRows(){
        $rows = @file($this->csvPath);
        return count($rows);
    }
    public function getRow($rowNr=0){
       $rows = @file($this->csvPath);
       $allrows = array();
       foreach ($rows as $row) {
            $allrows[] = explode($this->separator, $row);
        }
       return $allrows[$rowNr];
    }
    // removes row at specified index
    public function removeRow($index) {
        $rows = @file($this->csvPath);
        if ($index>=count($rows)) {
            $this->showWarning("Warning: row does not exist");
        return;
        }
        array_splice($rows, $index, 1);
        $this->fillFromArray($rows);
    }

	public function editRow($rowNr, $rowToInsert){
        $row = (is_array($rowToInsert)?implode($this->separator, $rowToInsert):$rowToInsert);
	    $rows = @file($this->csvPath) or $this->showError("Error: could not read CSV file");
		@array_splice($rows, $rowNr, 1, $row) or die("not possible to change the row");
		$this->fillFromArray($rows);
	}
    // adds row at specified index
    public function addRow($val, $index=-1) {
        $row = (is_array($val)?
        implode($this->separator, $val):$val);
        $rows = @file($this->csvPath);
        if ($index==-1) $rows[] .= $row;
        if ($index!=-1) {
            $rows[] = array_slice($rows, 0, $index-1)
            + $row + array_slice($rows, $index);
        }
        $this->fillFromArray($rows);
    }
}
?>