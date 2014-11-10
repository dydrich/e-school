<?php

require_once "ScheduleModule.php";

class Classe{
	private $ID;
	private $anno;
	private $sezione;
	private $sede;
	private $tempo_prolungato = 0;
	private $musicale = 0;
	private $school_order = null;
	private $modulo_orario;
	private $coordinatore;
	private $segretario;
	
	function __construct($record, $ds){
		$this->ID = $record['id_classe'];
		$this->sede = $record['sede'];
		$this->anno = $record['anno_corso'];
		$this->sezione = $record['sezione'];
		$this->tempo_prolungato = $record['tempo_prolungato'];
		$this->musicale = $record['musicale'];
		$this->school_order = $record['ordine_di_scuola'];
		$this->modulo_orario = new ScheduleModule($ds, $record['modulo_orario']);
		$this->coordinatore = $record['coordinatore'];
		$this->segretario = $record['segretario'];
	}
	
	public function get_ID(){
		return $this->ID;
	}
	
	public function get_anno(){
		return $this->anno;
	}
	
	public function get_sezione(){
		return $this->sezione;
	}
	
	public function get_sede(){
		return $this->sede;
	}
	
	public function set_sede($idsede){
		$this->sede = $idsede;
	}
	
	public function set_modulo_orario($m){
		$this->modulo_orario = $m;
	}
	
	public function get_modulo_orario(){
		return $this->modulo_orario;
	}
	
	public function getSchoolOrder(){
		return $this->school_order;
	}
	
	public function to_string($extended = false){
		$ret = "Classe ".$this->anno.", sezione ".$this->sezione;
		if($extended){
			$ret .= " - ".$this->sede;
		}
		return $ret;
	}
	
	public function isFullTime(){
		return $this->tempo_prolungato;
	}
	
	public function isMusicale(){
		return $this->musicale;
	}
	
	public function getCoordinatore(){
		return $this->coordinatore;
	}
	
	public function getSegretario(){
		return $this->segretario;
	}
	
}

class OraDiLezione{
	private $ID;
	private $ora;
	private $giorno;
	private $materia;
	private $compresenza;
	private $sostegno;
	private $classe;
	private $docente;
	private $descrizione;
	
	public function __construct($record){
		$this->ID = $record['id'];
		$this->ora = $record['ora'];
		$this->materia = $record['materia'];
		if ($this->materia == 0 || $this->materia == null) {
			$this->materia = 1;
		}
		$this->compresenza = $record['materia2'];
		$this->sostegno = $record['sostegno'];
		$this->giorno = $record['giorno'];
		$this->classe = $record['classe'];
		$this->docente = $record['docente'];
		$this->descrizione = $record['descrizione'];
	}
	
	public function getMateria(){
		return $this->materia;
	}
	
	public function getID(){
		return $this->ID;
	}
	
	public function getClasse(){
		return $this->classe;
	}
	
	public function getOra(){
		return $this->ora;
	}
	
	public function getGiorno(){
		return $this->giorno;
	}
	
	public function getDescrizione(){
		return $this->descrizione;
	}
	
	public function getDocente(){
		return $this->docente;
	}
	
}

class Orario{
	private $orario;
	
	public function __construct(){
		$this->orario = array();
	}
	
	public function addHour($ora){
		$index = $ora->getID();
		$this->orario[$index] = $ora;
	}
	
	public function getHour($id){
		return $this->orario[$id];
	}
	
	public function searchHour($giorno, $ora, $classe){
		foreach($this->orario as $a){
			//print ($a->getClasse()."=".$classe."-----".$a->getOra()."=".$ora."----".$a->getGiorno()."=".$giorno."<br />");
			if(($a->getClasse() == $classe) && ($a->getOra() == $ora) && ($a->getGiorno() == $giorno)){
				return $a;
			}
		}
		return null;
	}
	
	public function searchMateria($id){
		return $this->getHour($id)->getMateria();
	}
	
	public function getMateria($classe, $giorno, $ora){
		$h = $this->searchHour($giorno, $ora, $classe);
		if($h == null) return null;
		return $h->getMateria();
	}
	
	public function getDescrizione($classe, $giorno, $ora){
		$h = $this->searchHour($giorno, $ora, $classe);
		if($h){
			return $h->getDescrizione();
		}
		else {
			return "";
		}
	}
	
	public function _count(){
		return count($this->orario);
	}
}

abstract class CustomException extends Exception{
	protected $message = 'Unknown exception';     // Exception message
	private $string;                              // Unknown
	protected $code = 0;                          // User-defined exception code
	protected $file;                              // Source filename of exception
	protected $line;                              // Source line of exception
	private $trace;                               // Unknown

	
	public function __construct($message, $code = 0){
		parent::__construct($message, $code);
	}
	 
	public function __toString(){
		return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n" . "{$this->getTraceAsString()}";
	}
}

/**
* Handles SQL errors
* @author	 	Riccardo Bachis <cravenroad17@gmail.com>
* @created 	06/28/2011
* @last_mod 	06/28/2011
*
****************************************************************************/
class MySQLException extends CustomException{

	/**
	* the sql associated with the exception
	 */
	private $sql;

	/**
	* array of data about the error
	*/
	private $errors;
	
	public function __construct($message, $code, $query){
		parent::__construct($message, $code);
		if ($query != null){
			$this->sql = $query;
			$this->errors = array();
			$this->errors['data'] = date("d/m/Y");
			$this->errors['ora'] = date("H:i:s");
			$this->errors['ip_address'] = $_SERVER['REMOTE_ADDR'];
			$this->errors['referer'] = $_SERVER['HTTP_REFERER'];
			$this->errors['script'] = $_SERVER['SCRIPT_NAME'];
			$this->errors['query'] = $this->sql;
			$this->errors['error'] = $this->message;
			$_SESSION['__mysql_error__'] = $this->errors;
		}
	}

	/**
	* Convert the Exception to String
	*
	* @return String
	*/
	function __toString(){
		return "Si e' verificato un errore SQL nell'istruzione seguente: ".$this->sql.". Messaggio d'errore: ".$this->message;
	}
	
	/**
	* Convert the Exception to an html formatted string
	*
	* @return String
	*/
	function __toHTML(){
		return "<span>Si &egrave; verificato un errore SQL nell'istruzione seguente:</span> <span style='font-weight: bold'>$this->sql. </span><br />Messaggio d'errore: <span style='color: red'>".$this->message."</span>";
	}

	/**
	* Redirect to an html error page
	*
	* @return String
	*/
	function redirect(){
		header("Location: ".$_SESSION['__config__']['root_site']."/shared/mysql_error.php");
	}
	
	/**
	* Get an alert about the error
	*
	* @return String
	*/
	function alert(){
		$alert = '<script type="text/javascript">
				alert("Errore SQL.\nQuery: '.$this->getQuery().'\nErrore: '.$this->getMessage().'");
		window.close();
		</script>';
		print $alert;
	}
	
	/**
	* Get an alert about the error in a "fake-window"
	*
	* @return String
	*/
	function fake_alert(){
		print "kosql;".$this->getMessage().";".$this->getQuery();
		exit;
	}

	function getQuery(){
		return $this->sql;
	}
}


/**
 * A simple MailMessage class (like a Java Bean)
 * @author cravenroad17@gmail.com
 *
 */
class MailMessage{

	private $from;
	private $to;
	private $attachements = array();
	private $attachContentTypes = array();
	private $boundary;
	private $header;
	private $body;
	private $subject;

	public function __construct($from, $to = null, $subject = null){
		$this->from = $from;
		$this->to = $to;
		$this->subject = $subject;
		$this->createBoundary();
	}

	public function setFrom($from){
		$this->from = $from;
	}

	public function getFrom(){
		return $this->from;
	}

	public function setTo($to){
		$this->to = $to;
	}

	public function getTo(){
		return $this->to;
	}

	public function addAttachement($att){
		array_push($this->attachements, $att);
		array_push($this->attachContentTypes, MimeType::getMimeContentType($att, $tipo));
	}

	public function getAttachements(){
		return $this->attachements;
	}

	public function getBody(){
		return $this->body;
	}

	public function hasAttachements(){
		return count($this->attachements) > 0;
	}

	public function setHeader(){
		$this->header = "Reply-to: ".$this->getFrom()."\r\n";
		if($this->hasAttachements()){
			$this->header = "MIME-Version: 1.0\r\n";
			$this->header .= "Content-Type: multipart/mixed;boundary=\"".$this->getBoundary()."\"";
			$this->header .= "\r\n\r\nThis is a multi-part message in MIME format\r\n\r\n";
		}
	}

	public function getHeader(){
		return $this->header;
	}

	public function createBoundary(){
		$this->boundary = md5(uniqid(time));
	}

	public function getBoundary(){
		return $this->boundary;
	}

	public function createBody($txt){
		if($this->hasAttachements()){
			$this->body = "--".$this->getBoundary()."\r\n";
			$this->body .= "Content-Type: text/plain; charset=iso-8859-1\r\nContent-Transfer-Encoding: 7bit\r\n\r\n";
			$this->body .= $txt."\r\n\r\n";
				
			for($i = 0; $i < count($this->getAttachements()); $i++){
				$this->body .= "--".$this->getBoundary()."\r\n";
				$this->body .= "Content-Type: ".$this->attachContentTypes[$i]."; name=\"".basename($this->attachements[$i])."\"\r\n";
				$this->body .= "Content-Transfer-Encoding: base64\r\n";
				$this->body .= "Content-Disposition: attachment; filename=\"".basename($this->attachements[$i])."\"\r\n\r\n";
				$fp = fopen($this->attachements[$i], "r");
				$text = fread($fp, filesize($this->attachements[$i]));
				$text = chunk_split(base64_encode($text));
				fclose($fp);
				$this->body .= $text."\r\n\r\n";
			}
			$this->body .= "--".$this->getBoundary()."--";
		}
		else{
			$this->body = $txt;
		}
	}

	public function setSubject($sub){
		$this->subject = $sub;
	}

	public function getSubject(){
		return $this->subject;
	}
}

class SMTP{

	public static function send(MailMessage $mail){
		return mail($mail->getTo(), $mail->getSubject(), $mail->getBody(), $mail->getHeader());
	}
}

class Subjects{

	private $subjects = array();

	public static function subjectDesc($subId){

	}

	public static function subjectID($subDesc){

	}
}

class Subject 
{
	private $id;
	private $description;
	private $parent;
	private $children = array();
	private $report;
	private $school_type;
	
	public function __construct($record){
		$this->id = $record['id_materia'];
		$this->description = $record['materia'];
		$this->report = $record['pagella'];
		$this->school_type = $record['tipologia_scuola'];
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setDescription($desc){
		$this->description = $desc;
	}
	
	public function addChildren(Subject $sub){
		$this->children[$sub->getId()] = $sub;
	}
	
	public function getChildren(){
		return $this->children;
	}
	
	public function removeChildren($id){
		unset($this->children[$id]);
	}
	
	public function isInReport(){
		return $this->report;
	}
	
	public function setParent(Subject $par){
		$this->parent = $par;
	}
	
	public function getParent(){
		return $this->parent;
	}
	
	public function hasChildren(){
		return count($this->children) > 0;
	}
	
	public function getSchoolType(){
		return $this->school_type;
	}
	
	public function setSchoolType($t){
		$this->school_type = $t;
	}
	
}

?>
