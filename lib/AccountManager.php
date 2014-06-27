<?php

class AccountManager{
	
	private $user_;
	private $datasource_;
	
	public function __construct(UserBean $u, DataLoader $dl){
		$this->user_ = $u;
		$this->datasource_ = $dl;
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
		$smt = $this->datasource_->prepare("UPDATE rb_utenti SET password = ? WHERE uid = ?");
		$smt->bind_param("si", $newPwd, $this->user_->getUid());
		$smt->execute();
	}
	
}