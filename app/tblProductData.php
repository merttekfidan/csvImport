<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\readCsv;
class tblProductData extends Model
{
    protected $table = 'tblProductData';
    protected $primaryKey = 'intProductDataId';
    protected $guarded = [];
    const CREATED_AT = 'stmTimestamp';
    public $timestamps = false;
    private $csvArr=array();
    public $eloqArr=array();
    private $csv;

    public function __construct($path){
      $this->csv = new readCsv($path);
      $this->csvArr= $this->csv->prettyPrint();
    }

    public function fillTable(){
      foreach($this->csvArr as $val){
        //echo $val['Stock'];
        if($val['Imported']==1){
          array_push($this->eloqArr,array(
              'strProductName' => $val['Product Name'],
              'strProductDesc' => $val['Product Description'],
              'strProductCode' => $val['Product Code'],
              'dtmAdded' => date("Y-m-d H:i:s"),
              'dtmDiscontinued' => $val['Discontinued'],
              'intStock' => $val['Stock'],
              'dblCost' => $val['Cost in GBP']
            ));
        }
      }

        $this->insert($this->eloqArr);
        return ($this->csv->test());

    }
}
