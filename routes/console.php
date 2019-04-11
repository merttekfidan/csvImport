<?php


use App\readCsv;
use App\tblProductData;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/


Artisan::command('testMe {filename}', function () {
  $path= getcwd().'/'.$this->argument('filename');
  $csv= new readCsv($path);
  $prettyData=$csv->prettyPrint();
  dd($prettyData);
})->describe('To see how many product processed.');

Artisan::command('importMe  {filename}', function () {
  $path= getcwd().'/'.$this->argument('filename');
  $csv= new tblProductData($path);
  $arr=$csv->fillTable();

  echo ($arr['rowCount']." rows exist \n".$arr['rowAdded']." rows have been inserted\n".$arr['rowFailed']." rows couldn't be inserted\n");
  //dd($csv->doldur());
})->describe('To import data.');
