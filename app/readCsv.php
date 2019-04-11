<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class readCsv extends Model
{
    private $csvFile;
    private $csvData=array();
    private $filtered=array();
    private $fields=array();
    private $filteredData=array();
    private $rowAdded=0;
    private $rowCount=0;
    private $rowFailed=0;
    //The construct function gets a file directory and name then, defines the variable then calls readFile function.
    public function __construct($var) {
     $this->csvFile = $var;
     $this->readFile();
     $this->filterData();

    }
    public function test(){
      $arr = array('rowCount'=>$this->rowCount,'rowAdded'=>$this->rowAdded, 'rowFailed'=>$this->rowFailed);
      return $arr;
    }


    //This function reads CSV file and fills csvData array with data from CSV
    private function readFile(){
      $row = 0;
      //Checks file if it's readable
      if (($handle = fopen($this->csvFile, "r")) !== FALSE) {
        //Reads first 1000 rows seperated by comma(,)
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
          $num = count($data);
          for ($c=0; $c < $num; $c++) {
            //If it's the first row gets first row where titles are located and sets fields array with these titles.
            //It also encodes in UTF-8.
            if($row==0){
              $this->fields[$c]=mb_convert_encoding($data[$c], "UTF-8");
            }
            else{
              //checks whether CSV data columns are more than title columns.
              //If data column number is more than titles this statement doesn't allow to read more.
              if(count($this->fields)>$c){
                  //In csvData array, first dimension contains rows as a number.
                  //Second dimension contains columns with names previosly taken from the first row.
                  $this->csvData[$row][$this->fields[$c]]=mb_convert_encoding($data[$c], "UTF-8");
              }
            }
          }
          $row++;
        }
        //closes CSV file.
        fclose($handle);
      }
    }
    // All data gets filtered and defined into filteretData array
    private function filterData(){

      foreach($this->csvData as $data){
        //Checks whether Cost and Stock columns are set
        $this->rowCount++;
        if(isset($data['Cost in GBP']) && isset($data['Stock'])){
          if((!is_numeric($data['Stock']) || (!is_numeric($data['Cost in GBP'])))){
            //if stock and cost are not numeric array gets 2 columns.
            //Message is the output of proccess and Imported is the final result.
            $data['Message']='Product has no proper stock or cost .';
            $data['Imported']=0;
            array_push($this->filteredData,$data);
          }
          //Cost cannot be less than 5 AND Stock cannot be less than 10. If so this statement works.
          elseif($data['Cost in GBP']<5 && $data['Stock']<10){
            $data['Message']='Product is under 5 GBP and has less stock than 10.';
            $data['Imported']=0;
            array_push($this->filteredData,$data);
          }
          elseif($data['Cost in GBP']>1000){
            $data['Message']='Product cost over than 1000 GBP.';
            $data['Imported']=0;
            array_push($this->filteredData,$data);
          }
          else{
            $data['Message']='Product is inserted correctly.';
            $data['Imported']=1;
            $this->rowAdded++;
            if($data['Discontinued']=='yes')
              $data['Discontinued']=date("Y-m-d H:i:s");
            else
              $data['Discontinued']=NULL;
            array_push($this->filteredData,$data);
          }
        }
      }
      $this->rowFailed=$this->rowCount-$this->rowAdded;
    }


    public function prettyPrint(){
      return ($this->filteredData);
    }



    /**function _filterData($rules){
      $this->filtered=array();
      foreach($rules as $rule){
        $rule=explode(' ',$rule);
        $field=str_replace('"','',$rule[0]);
        $operator=$rule[1];
        $criteria=$rule[2];
        foreach($this->csvData[0] as $index=>$fieldName){
          if($fieldName==$field){
            $field=$index;
            break;
          }
        }

        switch ($operator) {
          case '>':
            foreach ($this->csvData as $key => $value) {
              //
              if($key==0) continue;
              if(count($value)>$field && $value[$field]>$criteria){
                $this->filtered[]=$value;
              }
            }
            break;
          case '<':
            foreach ($this->csvData as $key => $value) {
              //
              if($key==0) continue;
              if(count($value)>$field && $value[$field]<$criteria){
                $this->filtered[]=$value;
              }
            }
            break;
          case '>=':
            foreach ($this->csvData as $key => $value) {
              //
              if($key==0) continue;
              if(count($value)>$field && $value[$field]>=$criteria){
                $this->filtered[]=$value;
              }
            }
            break;
          case '<=':
            foreach ($this->csvData as $key => $value) {
              //
              if($key==0) continue;
              if(count($value)>$field && $value[$field]<=$criteria){
                $this->filtered[]=$value;
              }
            }
            break;
          case '=':
            foreach ($this->csvData as $key => $value) {
              //
              if($key==0) continue;
              if(count($value)>$field && $value[$field]==$criteria){
                $this->filtered[]=$value;
              }
            }
            break;
          default:
            // code...
            break;
        }
        $this->csvData=$this->filtered;
      }
    }**/




}
