<?php

class Goal{
	
	private $id;
	private $text;
	private $subject;
	private $teacher;
	private $year;
	private $classes;
	private $father;
	private $school_order;
	
	private $datasource;
	
	public function __construct($data, DataLoader $ds){
		$this->datasource = $ds;
		$this->id = $data['oid'];
		$this->text = $data['obj'];
		$this->subject = $data['subj'];
		$this->teacher = $data['teacher'];
		$this->year = $data['year'];
		$this->classes = $data['classi'];
		$this->father = $data['idp'];
		if ($this->father == 0){
			$this->father = "";
		}
		$this->school_order = $data['ordine_scuola'];
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function insert(){
		$q = "INSERT INTO rb_obiettivi (nome, docente, materia, ordine_scuola, anno, id_padre) VALUES ('{$this->text}', {$this->teacher}, {$this->subject}, {$this->school_order}, {$this->year}, ".field_null($this->father, true).")";
		$id = $this->datasource->executeUpdate($q);
		$this->id = $id;
		foreach ($this->classes as $cls){
			$q = "INSERT INTO rb_obiettivi_classe (id_obiettivo, anno, classe) VALUES ({$id}, {$this->year}, {$cls})";
			$this->datasource->executeUpdate($q);
		}
	}
	
	public function update(){
		$q = "UPDATE rb_obiettivi SET nome = '{$this->text}', docente = {$this->teacher}, materia = {$this->subject}, ordine_scuola = {$this->school_order}, anno = {$this->year}, id_padre = ".field_null($this->father, true)." WHERE id = {$this->id}";
		$this->datasource->executeUpdate($q);
		$this->datasource->executeUpdate("DELETE FROM rb_obiettivi_classe WHERE id_obiettivo = {$this->id}");
		foreach ($this->classes as $cls){
			$q = "INSERT INTO rb_obiettivi_classe (id_obiettivo, anno, classe) VALUES ({$this->id}, {$this->year}, {$cls})";
			$this->datasource->executeUpdate($q);
		}
	}
	
	public function delete(){
		$this->datasource->executeUpdate("DELETE FROM rb_obiettivi_classe WHERE id_obiettivo = {$this->id}");
		$this->datasource->executeUpdate("DELETE FROM rb_voti_obiettivo WHERE obiettivo = {$this->id}");
		$this->datasource->executeUpdate("DELETE FROM rb_obiettivi id = {$this->id}");
	}
}