<?php

require_once 'data_source.php';

class UploadManager {
	
	private $pathTo;
	private $data;
	private $file;
	private $uploadType;
	private $datasource;
	
	const FILE_EXISTS = 1;
	const UPL_ERROR = 2;
	const UPL_OK = 3;
	const WRONG_FILE_EXT = 4;
	
	public function __construct($pt, $file, $ut, $db){
		$this->pathTo = $pt;
		$this->file = $file;
		$this->uploadType = $ut;
		$this->datasource = new MySQLDataLoader($db);
	}
	
	public function setData($d){
		$this->data = $d;
	}
	
	public function moveFile(){
		/**
		 * gestione del filesystem
		 */
		$file_name = $this->file['name'];
		//$file = ereg_replace(" ", "_", basename($this->file['name']));
		//$file = ereg_replace("'", "", $file);
		//$file = preg_replace("/\\\/", "", $file);

		$file = preg_replace("/ /", "_", basename($this->file['name']));
		$file = preg_replace("/'/", "", $file);
		$file = preg_replace("/\\\/", "", $file);

		/**
		 * gestione file nel filesystem
		*/
		$dir = $_SESSION['__config__']['document_root']."/rclasse/{$this->pathTo}";
		if(!file_exists($dir)){
			if (!mkdir($dir, 0775, true)) {
				return self::UPL_ERROR;
			}
		}
		
		$target_path = $dir . $file;
		if(file_exists($target_path)){
			if ($this->uploadType == "document") {
				/*
				 * file caricato in precedenza: verifico se si tratta di un errore
				 */
				$sel_docs = "SELECT id FROM rb_documents WHERE file = '{$file}'";
				$id_f = $this->datasource->executeCount($sel_docs);
				if ($id_f != "") {
					return self::FILE_EXISTS;
				}

				return self::UPL_OK;
			}
			return self::UPL_OK;
		}
		else{
		//print("<script>$('_span').innerHTML = 'Attendere il caricamento del file...'</script>");
			if(move_uploaded_file($this->file['tmp_name'], $target_path)) {
			//echo "The file ".  basename( $_FILES['fname']['name']). " has been uploaded";
			//echo "moved ".$this->file['tmp_name']." to ".$target_path;
				chdir($dir);
				chmod($file, 0644);
			} 
			else{
				return self::UPL_ERROR;
			}
		}
		return self::UPL_OK;
	}
	
	public function upload($ext = null){
		$file_ext = pathinfo($this->file['name'], PATHINFO_EXTENSION);
		if ($ext != null && !in_array($file_ext, $ext)) {
			return self::WRONG_FILE_EXT;
		}
		switch ($this->uploadType){
			case "document":
			case "document_cdc":
				return $this->uploadDocument();
				break;
			case "teaching_doc":
				return $this->uploadDocument();
				break;
			case "teacherbook_att":
				return $this->teacherbookAttach();
				break;
		}
	}
	
	private function teacherbookAttach(){
		$ret = $this->moveFile();
		if ($ret != 3){
			return $ret;
		}
		else {
			$file_name = basename($this->file['name']);
			$last = $this->datasource->executeUpdate("INSERT INTO rb_allegati_registro_docente (registro, file) VALUES ({$this->data['id']}, '{$file_name}')");
			$ff = preg_replace("/ /", "_", $file_name);
			return $last;
		}
	}
	
	private function uploadDocument(){
		$ret = $this->moveFile();
		return $ret;
	}
}
