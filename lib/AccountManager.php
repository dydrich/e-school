<?php

class AccountManager{
	
	private $user_;
	private $datasource_;
	private $table_;
	private $field_;
	
	public function __construct(UserBean $u, DataLoader $dl){
		$this->user_ = $u;
		$this->datasource_ = $dl;
		if ($this->user_ instanceof StudentBean){
			$this->table_ = 'rb_alunni';
			$this->field_ = 'id_alunno';
		}
		else {
			$this->table_ = 'rb_utenti';
			$this->field_ = 'uid';
		}
	}
	
	public function recoveryPasswordViaEmail(){
		/*
	    * generate a random id
		*/
		$uniqid = md5(uniqid(rand(), true));
		$tm = new DateTime();
		$now = $tm->format("Y-m-d H:i:s");
		$due = $tm->add(new DateInterval('P1D'));
		$area = null;
		if ($this->user_ instanceof SchoolUserBean){
			$area = 3;
		}
		else if ($this->user_ instanceof ParentBean){
			$area = 1;
		}
		else {
			$area = 2;
		}

		$smt = $this->datasource_->prepare("INSERT INTO rb_recupero_password (utente, gruppo, token, data_richiesta, data_scadenza_token) VALUES (?, ?, ?, ?, ?)");
		$smt->bind_param("iisss", $this->user_->getUid(), $area, $uniqid, $now, $due->format("Y-m-d H:i:s"));
		$smt->execute();

		/*
		 * send email
		 */
		$to = $this->user_->getUsername();
		$subject = "Richiesta nuova password";
		$from = "admin@istitutoiglesiasserraperdosa.it";
		$headers = "From: {$from}\r\n"."Reply-To: {$from}\r\n" .'X-Mailer: PHP/' . phpversion();
		$message = "Gentile utente,\nabbiamo ricevuto la sua richiesta di una nuova password di accesso al Registro Elettronico.\n ";
		$message .= "Per modificare la password, clicchi sul link seguente entro 24 ore:\n\n";
		$message .= $_SESSION['__config__']['root_site']."/change_password.php?token=".$uniqid."&area=".$area."\n";
		$message .= "Per qualunque problema, non esiti a contattarci.\n\n";
		$message .= "Si prega di non rispondere a questa mail, in quanto inviata da un programma automatico.\n\n";
		mail($to, $subject, $message, $headers);
	}
	
	public function changePassword($newPwd){
		if ($this->user_ instanceof StudentBean){
			$table = 'rb_alunni';
			$field = 'id_alunno';
		}
		else {
			$table = 'rb_utenti';
			$field = 'uid';
		}
		$smt = $this->datasource_->prepare("UPDATE {$table} SET password = ? WHERE $field = ?");
		$smt->bind_param("si", $newPwd, $this->user_->getUid());
		$smt->execute();
	}

	public function changeUsername($newUsername) {
		if ($this->user_ instanceof StudentBean){
			$table = 'rb_alunni';
			$field = 'id_alunno';
		}
		else {
			$table = 'rb_utenti';
			$field = 'uid';
		}
		$smt = $this->datasource_->prepare("UPDATE {$table} SET username = ? WHERE $field = ?");
		$id = $this->user_->getUid();
		$smt->bind_param("si", $newUsername, $id);
		$smt->execute();
	}

	public function updateAccount($uname, $pwd) {
		$smt = $this->datasource_->prepare("UPDATE {$this->table_} SET username = ?, password = ? WHERE uid = ?");
		$smt->bind_param("ssi", $uname, $pwd, $this->user_->getUid());
		$smt->execute();
	}

	public static function generatePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bcdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BCDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}

		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		$pwd = array();
		$pwd['c'] = $password;
		$pwd['e'] = md5($password);
		return $pwd;
	}

	public static function generateLogin($names, $nome, $cognome){
		// analizzo il nome: se composto, utilizzo solo il primo
		if(preg_match("/ /", $nome)){
			$nomi = explode(" ", $nome);
		}
		else{
			$nomi[0] = $nome;
			$nomi[1] = "";
		}
		// elimino eventuali accenti (apostrofi) e spazi (solo dal cognome)
		$nm = strtolower(preg_replace("/'/", "", $nomi[0]));
		$cm = strtolower(preg_replace("/'/", "", trim($cognome)));
		$cm = strtolower(preg_replace("/ /", "", $cm));
		// creo la login e verifico
		$login = $nm.".".$cm;
		$base_login = $login;
		$length = count($login);
		$ok = false;
		// valore numerico per la creazione di login univoche
		$index = 1;
		while(!$ok){
			if(!in_array($login, $names)){
				return $login;
			}
			else{
				$login = $base_login.$index;
				$index++;
			}
		}
	}

	public function checkUsername($uname) {
		$names = $this->datasource_->executeCount("SELECT username FROM ".$this->table_." WHERE username = '".$uname."' AND uid <> ".$this->user_->getUid());
		if ($names != null) {
			return false;
		}
		return true;
	}
}
