<?

require __DIR__ . '/Box/Spout/Autoloader/autoload.php'; // Core Box Spout
require __DIR__ . '/Box/function.php'; // Funções Auxiliares

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;

$reader = ReaderFactory::create(Type::XLSX); // Type::CSV, Type::ODS
$reader->setShouldFormatDates(true);
$reader->open("Pasta1.xlsx");

$posRow = 0;

foreach ($reader->getSheetIterator() as $sheet) {
    foreach ($sheet->getRowIterator() as $row) {
		// $posRow = 0 { Cabeçalho }
		if ($posRow == 0) {	
			foreach($row as $idx => $value) {
				// remove os caracteres (acentos, ', ", -, /, ' ')
				$header[] =  str_replace("'", " ", str_replace("´", " ", str_replace("/", "_", str_replace(" ", "_", strtolower(trim(tirarAcentos($value)))))));
			}			
		} else {
			$coll = 0;
			foreach($row as $idx => $value) {
				// array(coluna => celula)
				$data[$posRow-1][$header[$coll]] = $value;
				$coll++;
			}
		}
		
		$posRow++;
    }
}
$reader->close();

// echo "<pre>";
// print_r($data);
// echo "</pre>";

?>
<!-- ================ -->
<!-- DISPLAY IN TABLE -->
<style>
	 th, td {border: 1px solid lightgray; white-space: nowrap; text-align: left; font-weight: normal; }
</style>
<table>
	<thead>
		<tr>
			<?php  for($i=0; $i < count ($header); $i++) {  ?>
			
				<th> <?php echo $header[$i]?> </th>
			
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php  for($i=0; $i < count ($data); $i++) {  ?>

				<tr>
					<?php  for($j=0; $j < count ($header); $j++) {  ?>
					
						<td> <?php echo $data[$i][$header[$j]] ?> </td>
					
					<? } ?>
				</tr>
			
		<?php } ?>
	</tbody>
</table>