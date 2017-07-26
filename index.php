
<?php

class LoadCSV{

  public $delimiter = ';';
  public $total_columns = 0;
  public $file = null;
  public $file_data = null;
  public $convert_encoding = true;
  public $input_encoding = 'ISO-8859-1';
  public $output_encoding = 'ISO-8859-1';
  public $validate_rows = [];
  public $error_rows = [];
  public $csv = [];
  public $type = null;
  public $titles = ['nombre', 'apellido', 'genero', 'acompañantes', 'email', 'telefono', 'país', 'ciudad', 'direccion', 'codigoPostal', 'Complemento', 'SubsActualizaciones', 'SubsNewsletter'];

  function __construct( $path, $type , $titles = null, $delimiter = null){
    if ( !empty($path) ) {
      $this->path = $path;
    }
    if ( !empty($titles) ) {
      $this->titles = $titles;
    }
    if ( !empty($delimiter) ) {
      $this->delimiter = $delimiter;
    }

    if ( !empty($type) ) {
      $this->type = $type;
    }

    $this->total_columns = count($this->titles);
  }

  function load_data(){
    if($this->type == true){
      $this->load_csv();
    }else{
      $this->load_text();
    }
    return $this->validate_rows;
  }

  function load_csv(){
    if (!empty($this->path)){
      if ($this->load_file()){
        foreach ($this->file as $key => $line) {
          $this->comparative_line($line[0]);
        }
      }
    }
  }

  function load_text(){
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $this->path) as $line){
      $this->comparative_line($line);
    }
  }

  function comparative_line($line){
    $array = explode($this->delimiter, utf8_encode($line));
    if (count($array) == $this->total_columns){
      array_push($this->validate_rows, $array);
    }else{
      array_push($this->error_rows, $array);
    }
  }

  function load_file(){
    if (!empty($this->path)){
      $this->file = array_map('str_getcsv', file($this->path));
      return true;
    }
    return false;
  }
}


// cuando se carga desde un archivo .csv
$csv = new LoadCSV('ejemplo.csv', true);

print_r($csv->load_data());

echo "<BR>";
echo "<BR>";

print_r($csv->error_rows);


// Cuando se carga desde un texto plano.

$text = "Nombre;Apellido;Genero;Acompañantes;Email;Telefono;País;Ciudad;Dirección;CódigoPostal;Complemento;Subs. Actualizaciones;Subs. Newsletter
Matías;Pérez;male;1;mperez@whooohq.com;56956892345;Chile;Santiago;Irarrázaval 2821;80000;Oficina 505;1;1
Matías;Pérez;male;1;mperez@whooohq.com;56956892345;Chile;Santiago;Irarrázaval 2821;80000;Oficina 505;1;1
Matías;Pérez;male;1;mperez@whooohq.com;56956892345;Chile;Santiago;Irarrázaval 2821;80000;Oficina 505;1;1
Jhoynerk;Caraballo;male;3;jhoynerk@whooohq.com;5427282533;Argentina;Buenos Aires;;80000;Oficina 505;1;1
Jhoynerk;Caraballo;male;3;jhoynerk@whooohq.com;5427282533;Argentina
";


$csv_text = new LoadCSV($text, false);
$rows_to_save = $csv_text->load_data();
print_r($rows_to_save);

echo "<BR>";
echo "<BR>";

print_r($csv_text->error_rows);
