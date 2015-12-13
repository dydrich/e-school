<?php

require_once "../../../lib/start.php";
require_once "../../../lib/PHPExcel.php";

ini_set("display_errors", DISPLAY_ERRORS);

check_session();

$_SESSION['__path_to_root__'] = "../../../";
$_SESSION['__path_to_reg_home__'] = "../";

$author = $_SESSION['__user__']->getFullName();
$list_type = $_REQUEST['t'];

$students = [];

$sel_alunni = "SELECT CONCAT_WS(' ', cognome, nome) AS alunno, data_nascita, luogo_nascita, id_alunno, sesso FROM rb_alunni WHERE id_classe = ".$_SESSION['__classe__']->get_ID()." AND attivo = 1 ORDER BY cognome, nome ";
$res_alunni = $db->execute($sel_alunni);
while ($alunno = $res_alunni->fetch_assoc()) {
	$students[$alunno['id_alunno']]['anagrafica'] = $alunno;
	$students[$alunno['id_alunno']]['indirizzo'] = ["indirizzo" => "Non presente", "citta" => ""];
	$students[$alunno['id_alunno']]['telefoni'] = [];
}

$sel_add = "SELECT indirizzo, citta, rb_alunni.id_alunno FROM rb_indirizzi_alunni, rb_alunni WHERE rb_indirizzi_alunni.id_alunno = rb_alunni.id_alunno AND attivo = 1 AND id_classe = ".$_SESSION['__classe__']->get_ID();
$res_add = $db->execute($sel_add);
if ($res_add->num_rows > 0) {
	while ($row = $res_add->fetch_assoc()) {
		$students[$row['id_alunno']]['indirizzo']['indirizzo'] = $row['indirizzo'];
		$students[$row['id_alunno']]['indirizzo']['citta'] = $row['citta'];
	}
}

$sel_phones = "SELECT telefono, descrizione, rb_alunni.id_alunno FROM rb_telefoni_alunni, rb_alunni WHERE rb_telefoni_alunni.id_alunno = rb_alunni.id_alunno AND attivo = 1 AND id_classe = ".$_SESSION['__classe__']->get_ID()." ORDER BY principale DESC";
$res_phones = $db->execute($sel_phones);
if ($res_phones->num_rows > 0) {
	while ($row = $res_phones->fetch_assoc()) {
		$students[$row['id_alunno']]['telefoni'][] = ["telefono" => $row['telefono'], "descrizione" => $row['descrizione']];
	}
}

// create new PDF document
$objPHPExcel = new PHPExcel();

// set document information
$objPHPExcel->getProperties()->setCreator($author)
	->setLastModifiedBy($author)
	->setTitle("Elenco alunni classe  ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione())
	->setDescription("Elenco alunni classe  ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione());

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1')->setCellValue('A1', $_SESSION['__current_year__']->to_string()."  -  Elenco alunni classe  ".$_SESSION['__classe__']->get_anno().$_SESSION['__classe__']->get_sezione()." (".count($students).")");

$index = 3;
switch ($list_type) {
	case 1:
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Nome e cognome");
		foreach ($students as $student) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$index, $student['anagrafica']['alunno']);
			$index++;
		}
		break;
	case 2:
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Nome e cognome");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Data di nascita");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Luogo di nascita");
		foreach ($students as $student) {
			$vowel = "o";
			if ($student['anagrafica']['sesso'] == "F") {
				$vowel = "a";
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$index, $student['anagrafica']['alunno']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$index, format_date($student['anagrafica']['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$index, $student['anagrafica']['luogo_nascita']);
			$index++;
		}
		break;
	case 3:
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Nome e cognome");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Indirizzo");
		foreach ($students as $student) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$index, $student['anagrafica']['alunno']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$index, $student['indirizzo']['indirizzo']." - ".$student['indirizzo']['citta']);
			$index++;
		}
		break;
	case 4:
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Nome e cognome");
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:G2')->setCellValue('B2', "Telefono");
		foreach ($students as $student) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$index, $student['anagrafica']['alunno']);
			if (count($student['telefoni']) > 0) {
				$col = 'B';
				foreach ($student['telefoni'] as $telefono) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$index, $telefono['telefono']);
					$col++;
				}
			}
			$index++;
		}
		break;
	case 5:
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Nome e cognome");
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:G2')->setCellValue('B2', "Telefono");
		foreach ($students as $student) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$index, $student['anagrafica']['alunno']);
			$col = 'B';
			foreach ($student['telefoni'] as $phone) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$index, $phone['telefono']." - ".$phone['descrizione']);
				$col++;
			}
			$index++;
		}
		break;
	case 6:
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', "Nome e cognome");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', "Data di nascita");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', "Luogo di nascita");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', "Indirizzo");
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:J2')->setCellValue('E2', "Telefoni");
		foreach ($students as $student) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$index, $student['anagrafica']['alunno']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$index, format_date($student['anagrafica']['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$index, $student['anagrafica']['luogo_nascita']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$index, $student['indirizzo']['indirizzo']." - ".$student['indirizzo']['citta']);
			$col = 'E';
			foreach ($student['telefoni'] as $phone) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.$index, $phone['telefono']." - ".$phone['descrizione']);
				$col++;
			}
			$index++;
		}
		break;
}

$objPHPExcel->getActiveSheet()->setTitle('Elenco alunni');
$objPHPExcel->setActiveSheetIndex(0);
if ($_REQUEST['app'] == 'xls') {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="elenco_alunni.xls"');
	header('Cache-Control: max-age=0');
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
}
else {
	header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
	header('Content-Disposition: attachment;filename="elenco_alunni.ods"');
	header('Cache-Control: max-age=0');
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'OpenDocument');
}
$objWriter->save('php://output');
