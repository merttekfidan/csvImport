# csvImport
CSV import application task for the job offer

After setting up the database with given sql query file, migrations must be executed.
Although Sql query file is given, no need to execute it. Because that query has been converted into Eloquent ORM form.

<b>php artisan migrate</b>

To test the data;</br>
<b>php artisan testMe <i><CSVDIRECTORY/CSVNAME.CSV></i></b>
</br>example:</br>
<i>php artisan testMe ../stock.csv</i>
</br>


To import the data;</br>
<b>php artisan importMe <i><CSVDIRECTORY/CSVNAME.CSV></i></b>
</br>example:</br>
<i>php artisan import ../stock.csv</i>
</br>

Unfortunatelly, this project doesn't include Unit tests
