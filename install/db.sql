
CREATE TABLE IF NOT EXISTS `rb_allegati_registro_docente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registro` int(11) NOT NULL,
  `file` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `rb_alunni` (
  `id_alunno` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `cognome` varchar(200) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `data_nascita` date DEFAULT NULL,
  `luogo_nascita` varchar(250) DEFAULT NULL,
  `codice_fiscale` char(16) CHARACTER SET latin1 DEFAULT '',
  `sesso` char(1) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `id_classe` int(11) DEFAULT NULL,
  `nickname` varchar(25) DEFAULT NULL,
  `attivo` char(1) CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `accessi` int(11) NOT NULL DEFAULT '0',
  `stile` smallint(6) DEFAULT '0',
  `ripetente` tinyint(1) NOT NULL DEFAULT '0',
  `legge104` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_alunno`),
  UNIQUE KEY `uniq_cf` (`codice_fiscale`),
  UNIQUE KEY `unq_uname` (`username`),
  KEY `idx_classe` (`id_classe`),
  KEY `idx_cognome` (`cognome`),
  KEY `idx_nome` (`nome`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000 ;


CREATE TABLE IF NOT EXISTS `rb_anni` (
  `id_anno` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` char(9) NOT NULL,
  `data_inizio` date NOT NULL,
  `data_fine` date NOT NULL,
  `fine_quadrimestre` date DEFAULT NULL,
  `data_inizio_lezioni` date NOT NULL,
  `data_termine_lezioni` date NOT NULL,
  `vacanze` text,
  PRIMARY KEY (`id_anno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_aree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `attivo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `rb_aree` (`id`, `nome`, `attivo`) VALUES
(1, 'docenti', 1),
(2, 'genitori', 0),
(3, 'alunni', 0),
(4, 'segreteria', 0),
(5, 'dirigente scolastico', 0),
(6, 'admin', 1);

CREATE TABLE IF NOT EXISTS `rb_assegnazione_sostegno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anno` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `docente` int(11) NOT NULL,
  `ore` int(11) NOT NULL,
  `alunno` int(11) DEFAULT NULL,
  `progressivo_classe` int(11) NOT NULL DEFAULT '1' COMMENT 'indice del docente di sostegno, se nella classe ne sono presenti diversi',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_doc` (`anno`,`classe`,`docente`,`alunno`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_attivita_sostegno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alunno` int(11) NOT NULL,
  `data` date NOT NULL,
  `attivita` text NOT NULL,
  `anno` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_categorie_docs` (
  `id_categoria` smallint(6) NOT NULL AUTO_INCREMENT,
  `tipo_documento` smallint(6) NOT NULL,
  `codice` varchar(3) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descrizione` text NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

INSERT INTO `rb_categorie_docs` (`id_categoria`, `tipo_documento`, `codice`, `nome`, `descrizione`) VALUES
(1, 4, 'VA', 'Valutazione', 'Documenti sulla valutazione'),
(2, 4, 'MS', 'Materiali scolastici', 'Tutto ci&ograve; che riguarda le lezioni: approfondimenti, testi, verifiche, criteri, ecc.'),
(3, 6, 'EX', 'Normativa', 'Documenti ufficiali non prodotti dalla scuola, riguardanti varia normativa in materia'),
(4, 7, 'PER', 'Personale', 'Tutto quanto riguarda il personale della scuola'),
(5, 7, 'ALU', 'Alunni', 'Tutto quanto riguarda gli alunni della scuola'),
(6, 7, 'CTB', 'ContabilitÃƒÂ ', 'Documenti di contabilitÃƒÂ '),
(7, 7, 'SIN', 'Comunicazioni sindacali', 'Comunicazioni di sindacato e RSU'),
(8, 7, 'SIC', 'Sicurezza nei luoghi di lavoro', 'Atti relativi alla sicurezza nei luoghi di lavoro'),
(9, 7, 'OCG', 'Organi collegiali', 'Delibere e tutto quanto riguarda gli organi collegiali della scuola'),
(10, 4, 'SCH', 'Schemi e modelli', 'Schemi e modelli utili per il docente'),
(11, 7, 'DCI', 'Delibere CI', 'Contiene tutte le delibere del Consiglio d''Istituto'),
(12, 7, 'BAN', 'bandi e gare ', 'bandi e gare '),
(13, 4, 'FOR', 'Formazione docenti', 'Materiali utili per la formazione dei docenti');

CREATE TABLE IF NOT EXISTS `rb_cdc` (
  `id_classe` int(11) NOT NULL,
  `id_anno` int(11) NOT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `id_materia` int(11) NOT NULL,
  `coordinatore` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_classe`,`id_anno`,`id_materia`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rb_classi` (
  `id_classe` int(11) NOT NULL AUTO_INCREMENT,
  `anno_corso` int(11) NOT NULL,
  `sezione` char(2) CHARACTER SET latin1 NOT NULL,
  `anno_scolastico` int(11) NOT NULL,
  `tempo_prolungato` tinyint(1) NOT NULL DEFAULT '0',
  `sede` tinyint(4) NOT NULL DEFAULT '1',
  `musicale` tinyint(4) NOT NULL DEFAULT '0',
  `modulo_orario` tinyint(4) NOT NULL DEFAULT '1',
  `ordine_di_scuola` int(11) NOT NULL,
  `coordinatore` int(11) DEFAULT NULL,
  `segretario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_classe`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_allegati_circolari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_circolare` int(11) NOT NULL,
  `file` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_avvisi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `testo` text NOT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_scadenza` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_circolari` (
  `id_circolare` int(11) NOT NULL AUTO_INCREMENT,
  `anno` int(11) NOT NULL,
  `progressivo` int(11) NOT NULL,
  `protocollo` varchar(10) NOT NULL,
  `data_circolare` date NOT NULL,
  `data_inserimento` datetime NOT NULL,
  `owner` int(11) NOT NULL,
  `destinatari` text NOT NULL,
  `oggetto` text NOT NULL,
  `testo` text NOT NULL,
  PRIMARY KEY (`id_circolare`),
  UNIQUE KEY `unq_numero_circolare` (`anno`,`progressivo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_eventi` (
  `id_evento` int(11) NOT NULL AUTO_INCREMENT,
  `abstract` varchar(200) NOT NULL,
  `testo` text NOT NULL,
  `owner` int(11) NOT NULL,
  `data_evento` timestamp NULL DEFAULT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_modifica` timestamp NULL DEFAULT NULL,
  `pubblico` tinyint(1) NOT NULL,
  `modificabile` tinyint(1) NOT NULL,
  `utente_mod` int(11) DEFAULT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `classe` int(11) DEFAULT NULL,
  `has_sons` tinyint(1) NOT NULL DEFAULT '0',
  `ordine_di_scuola` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id_evento`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mittente` int(11) NOT NULL,
  `destinatario` int(11) NOT NULL,
  `file` varchar(100) NOT NULL,
  `data_invio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data_download` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_lettura_circolari` (
  `id_circolare` int(11) NOT NULL,
  `docente` int(11) NOT NULL,
  `letta` tinyint(1) NOT NULL DEFAULT '0',
  `data_lettura` datetime DEFAULT NULL,
  PRIMARY KEY (`id_circolare`,`docente`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rb_com_messages` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `send_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read_timestamp` timestamp NULL DEFAULT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_news` (
  `id_news` int(11) NOT NULL AUTO_INCREMENT,
  `data` date NOT NULL,
  `abstract` varchar(250) NOT NULL,
  `testo` text NOT NULL,
  `utente` int(11) NOT NULL,
  `ora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_news`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_com_threads` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `user1_group` varchar(11) NOT NULL,
  `user2_group` varchar(11) NOT NULL,
  `last_message` int(11) DEFAULT NULL,
  PRIMARY KEY (`tid`),
  KEY `user1_idx` (`user1`,`user1_group`),
  KEY `user2_idx` (`user2`,`user2_group`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_config` (
  `variabile` varchar(50) NOT NULL,
  `valore` varchar(100) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `readonly` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `rb_config` (`variabile`, `valore`, `id`, `readonly`) VALUES
('stato_avanzamento_nuove_classi', '5', 2, 1),
('num_news', '4', 3, 0),
('debug', '1', 4, 1),
('limite_sufficienza', '5.5', 5, 0);

CREATE TABLE IF NOT EXISTS `rb_dati_lezione` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vacanze` text,
  `id_anno` int(11) NOT NULL,
  `id_ordine_scuola` int(11) NOT NULL,
  `data_inizio_lezioni` date DEFAULT NULL,
  `data_termine_lezioni` date DEFAULT NULL,
  `sessioni` int(11) DEFAULT NULL,
  `data_fine_1_sessione` date DEFAULT NULL,
  `data_fine_2_sessione` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_anno` (`id_anno`,`id_ordine_scuola`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_dati_sostegno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alunno` int(11) NOT NULL,
  `scuola_provenienza` varchar(150) DEFAULT NULL,
  `classe_provenienza` varchar(5) DEFAULT NULL,
  `padre` varchar(50) DEFAULT NULL,
  `madre` varchar(50) DEFAULT NULL,
  `fratelli_sorelle` text,
  `altro` text,
  `diagnosi` text,
  `terapia` tinyint(1) DEFAULT NULL,
  `tipo_terapia` varchar(100) DEFAULT NULL,
  `difficolta_prevalenti` text,
  `tipologia_classe` text,
  `profilo` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_docenti` (
  `id_docente` int(11) NOT NULL AUTO_INCREMENT,
  `materia` int(11) DEFAULT NULL,
  `ruolo` char(1) CHARACTER SET latin1 NOT NULL DEFAULT 'S',
  `attivo` tinyint(1) NOT NULL DEFAULT '1',
  `tipologia_scuola` int(11) NOT NULL DEFAULT '6',
  PRIMARY KEY (`id_docente`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_upload` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file` varchar(100) NOT NULL,
  `dw_counter` int(11) NOT NULL DEFAULT '0',
  `doc_type` int(11) NOT NULL,
  `abstract` varchar(250) NOT NULL,
  `anno_scolastico` smallint(6) NOT NULL,
  `owner` smallint(6) DEFAULT NULL,
  `ultima_modifica` timestamp NULL DEFAULT NULL,
  `categoria` smallint(6) DEFAULT NULL,
  `titolo` varchar(200) DEFAULT NULL,
  `classe_rif` char(1) DEFAULT NULL,
  `materia` smallint(6) DEFAULT NULL,
  `link` varchar(200) NOT NULL,
  `progetto` int(11) DEFAULT NULL,
  `privato` tinyint(1) NOT NULL DEFAULT '0',
  `gruppi` varchar(50) DEFAULT NULL,
  `evidenziato` timestamp NULL DEFAULT NULL,
  `numero_atto` int(11) DEFAULT NULL,
  `protocollo` varchar(32) DEFAULT NULL,
  `scadenza` date DEFAULT NULL,
  `permessi` int(11) DEFAULT NULL,
  `progressivo_atto` varchar(11) DEFAULT NULL,
  `ordine_scuola` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_sub` (`doc_type`,`materia`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_documents_shared` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anno` int(11) NOT NULL,
  `id_documento` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_documents_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_documento` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_document_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codice` char(2) NOT NULL,
  `commento` varchar(100) NOT NULL,
  `permessi` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

INSERT INTO `rb_document_types` (`id`, `codice`, `commento`, `permessi`) VALUES
(1, 'SW', 'Lavori dei ragazzi', 2),
(2, 'OD', 'Documenti ufficiali della scuola', 225),
(3, 'PG', 'Progetti', 2),
(4, 'DD', 'Materiale didattico', 2),
(5, 'AM', 'Altri documenti dalla scuola', 225),
(6, 'EX', 'Documenti esterni', 225),
(7, 'AP', 'Albo pretorio', 225),
(8, 'SV', 'Schede di valutazione', 225);

CREATE TABLE IF NOT EXISTS `rb_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_id` int(11) NOT NULL,
  `doc_type` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `data_dw` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_esiti` (
  `id_esito` int(11) NOT NULL AUTO_INCREMENT,
  `esito` varchar(250) NOT NULL,
  `ordine_scuola` int(11) DEFAULT NULL,
  `positivo` tinyint(1) NOT NULL,
  `classe` int(11) NOT NULL,
  `sesso` char(1) NOT NULL,
  `desc_pagella` text NOT NULL,
  PRIMARY KEY (`id_esito`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_genitori_figli` (
  `id_genitore` int(11) NOT NULL,
  `id_alunno` int(11) NOT NULL,
  PRIMARY KEY (`id_genitore`,`id_alunno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rb_gestione_scrutini` (
  `id_scrutinio` int(11) NOT NULL AUTO_INCREMENT,
  `anno` int(11) NOT NULL,
  `sessione` int(11) NOT NULL,
  `data_apertura` datetime NOT NULL,
  `data_chiusura` datetime NOT NULL,
  `stato` int(11) NOT NULL COMMENT '1: aperto; 0: chiuso',
  PRIMARY KEY (`id_scrutinio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_giorni_modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modulo` int(11) NOT NULL,
  `giorno` int(11) NOT NULL COMMENT 'Numeric representation of the day of the week, as in php date function',
  `ingresso` time NOT NULL,
  `uscita` time NOT NULL,
  `inizio_pausa` time DEFAULT NULL,
  `durata_pausa` int(11) DEFAULT NULL COMMENT 'in minuti',
  `durata_ora` int(11) NOT NULL COMMENT 'in minuti',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

INSERT INTO `rb_giorni_modulo` (`id`, `id_modulo`, `giorno`, `ingresso`, `uscita`, `inizio_pausa`, `durata_pausa`, `durata_ora`) VALUES
(1, 1, 1, '08:25:00', '13:25:00', NULL, NULL, 60),
(2, 1, 2, '08:25:00', '13:25:00', NULL, NULL, 60),
(3, 1, 3, '08:25:00', '13:25:00', NULL, NULL, 60),
(4, 1, 4, '08:25:00', '13:25:00', NULL, NULL, 60),
(5, 1, 5, '08:25:00', '13:25:00', NULL, NULL, 60),
(6, 1, 6, '08:25:00', '13:25:00', NULL, NULL, 60),
(7, 2, 1, '08:30:00', '16:30:00', '13:30:00', 60, 60),
(8, 2, 2, '08:30:00', '16:30:00', '13:30:00', 60, 60),
(9, 2, 3, '08:30:00', '16:30:00', '13:30:00', 60, 60),
(10, 2, 4, '08:30:00', '16:30:00', '13:30:00', 60, 60),
(11, 2, 5, '08:30:00', '16:30:00', '13:30:00', 60, 60);

CREATE TABLE IF NOT EXISTS `rb_giudizi_parametri_pagella` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_parametro` int(11) NOT NULL,
  `giudizio` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

INSERT INTO `rb_giudizi_parametri_pagella` (`id`, `id_parametro`, `giudizio`) VALUES
(1, 1, 'Ottime'),
(2, 1, 'Molto buone'),
(3, 2, 'Elevata'),
(4, 2, 'Notevole'),
(5, 3, 'Tenace'),
(6, 3, 'Notevole'),
(7, 4, 'Immediata'),
(8, 4, 'Costante'),
(9, 5, 'Attiva'),
(10, 5, 'Vivace');

CREATE TABLE IF NOT EXISTS `rb_gruppi` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `codice` char(3) NOT NULL,
  `permessi` int(11) NOT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

INSERT INTO `rb_gruppi` (`gid`, `nome`, `codice`, `permessi`) VALUES
(1, 'admin', '1', 1),
(2, 'docenti', '2', 2),
(3, 'ata', '4', 4),
(4, 'genitori', '8', 8),
(5, 'segreteria', '32', 32),
(6, 'dirigenza', '64', 64),
(7, 'dsga', '128', 128),
(8, 'studenti', '256', 256),
(9, 'sp_admin', '512', 512),
(10, 'sm_admin', '102', 1024),
(11, 'si_admin', '204', 2048),
(12, 'ss_admin', '409', 4096);

CREATE TABLE IF NOT EXISTS `rb_gruppi_utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_doc_gr` (`gid`,`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_impegni` (
  `id_impegno` int(11) NOT NULL AUTO_INCREMENT,
  `data_assegnazione` timestamp NULL DEFAULT NULL,
  `data_inizio` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data_fine` timestamp NULL DEFAULT NULL,
  `docente` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `materia` int(11) NOT NULL,
  `descrizione` text NOT NULL,
  `note` text,
  `tipo` int(11) NOT NULL,
  PRIMARY KEY (`id_impegno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_indirizzi_alunni` (
  `id_indirizzo` int(11) NOT NULL AUTO_INCREMENT,
  `id_alunno` int(11) NOT NULL,
  `indirizzo` varchar(200) DEFAULT NULL,
  `citta` varchar(50) DEFAULT NULL,
  `telefono1` varchar(25) DEFAULT NULL,
  `telefono2` varchar(25) DEFAULT NULL,
  `telefono3` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_indirizzo`),
  KEY `id_alunno` (`id_alunno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_lettura_pagelle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pubblicazione` int(11) NOT NULL,
  `alunno` int(11) NOT NULL,
  `data_lettura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `genitore` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_ora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utente` int(11) NOT NULL,
  `tipo_evento` int(11) NOT NULL,
  `numeric1` int(11) DEFAULT NULL,
  `numeric2` int(11) DEFAULT NULL,
  `text1` text,
  `text2` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_materie` (
  `id_materia` int(11) NOT NULL AUTO_INCREMENT,
  `materia` varchar(200) NOT NULL,
  `idpadre` tinyint(4) DEFAULT NULL,
  `has_sons` tinyint(1) NOT NULL DEFAULT '0',
  `pagella` tinyint(4) NOT NULL DEFAULT '0',
  `tipologia_scuola` int(11) NOT NULL,
  `posizione_pagella` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_materia`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

INSERT INTO `rb_materie` (`id_materia`, `materia`, `idpadre`, `has_sons`, `pagella`, `tipologia_scuola`, `posizione_pagella`) VALUES
(1, 'Scegli', NULL, 0, 0, 0, NULL),
(2, 'Comportamento', NULL, 0, 1, 1, 12),
(3, 'Italiano', 12, 0, 1, 1, 1),
(4, 'Storia e Geografia', 12, 1, 0, 1, NULL),
(5, 'Arte e immagine', NULL, 0, 1, 1, 8),
(6, 'Educazione fisica', NULL, 0, 1, 1, 9),
(7, 'Tecnologia', NULL, 0, 1, 1, 10),
(8, 'Musica', NULL, 0, 1, 1, 11),
(9, 'Scienze Matematiche', NULL, 1, 0, 1, NULL),
(10, 'Inglese', NULL, 0, 1, 1, 2),
(11, 'Francese', NULL, 0, 1, 1, 3),
(12, 'Lettere', NULL, 3, 0, 1, NULL),
(13, 'Strumento', NULL, 1, 1, 1, NULL),
(14, 'Storia', 12, 0, 1, 1, 4),
(15, 'Geografia', 12, 0, 1, 1, 5),
(16, 'Matematica', 9, 0, 1, 1, 6),
(17, 'Scienze', 9, 0, 1, 1, 7),
(21, 'Chitarra', 13, 0, 0, 1, NULL),
(22, 'Violino', 13, 0, 0, 1, NULL),
(23, 'Flauto', 13, 0, 0, 1, NULL),
(24, 'Pianoforte', 13, 0, 0, 1, NULL),
(25, 'Approfondimento materie letterarie', 12, 0, 0, 1, NULL),
(26, 'Religione', NULL, 0, 1, 1, NULL),
(27, 'Sostegno', NULL, 0, 0, 1, NULL),
(28, 'Italiano', 39, 0, 1, 2, 1),
(29, 'Matematica', 39, 0, 1, 2, 2),
(30, 'Religione', 39, 0, 1, 2, NULL),
(31, 'Immagine', 39, 0, 1, 2, 3),
(32, 'Inglese', 39, 0, 1, 2, 4),
(33, 'Sostituzione', NULL, 0, 0, 0, NULL),
(34, 'Storia', 39, 0, 1, 2, 5),
(35, 'Geografia', 39, 0, 1, 2, 6),
(36, 'Motoria', 39, 0, 1, 2, 7),
(37, 'Scienze', 39, 0, 1, 2, 8),
(38, 'Musica', 39, 0, 1, 2, 9),
(39, 'Posto comune', NULL, 11, 0, 2, NULL),
(40, 'Comportamento', NULL, 0, 1, 2, 20),
(41, 'Sostegno', NULL, 0, 0, 2, NULL),
(42, 'Tecnologia', 39, 0, 1, 2, NULL),
(43, 'Posto comune', NULL, 0, 0, 3, NULL),
(44, 'Sostegno', NULL, 0, 0, 3, NULL);

CREATE TABLE IF NOT EXISTS `rb_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `depends_to` varchar(50) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `code_name` varchar(20) NOT NULL,
  `code_value` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `home` varchar(250) NOT NULL,
  `lib_home` varchar(250) NOT NULL,
  `front_page` varchar(250) NOT NULL,
  `path_to_root` varchar(250) NOT NULL,
  `lib_to_root` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

INSERT INTO `rb_modules` (`id`, `name`, `depends_to`, `active`, `code_name`, `code_value`, `type`, `home`, `lib_home`, `front_page`, `path_to_root`, `lib_to_root`) VALUES
(1, 'registro di classe', NULL, 1, 'reg_cls', 1, 1, 'intranet/teachers/registro_classe', 'lib', 'registro_classe.php', '', ''),
(2, 'registro personale', '1', 1, 'reg_pers', 2, 1, 'intranet/teachers/registro_personale', 'lib', 'index.php', '', ''),
(3, 'gestione della classe', '1,2', 1, 'gest_cls', 4, 1, 'intranet/teachers/gestione_classe', 'lib', 'index.php', '', ''),
(4, 'documenti', '1', 1, 'docs', 8, 1, 'modules/documents', 'modules/documents/lib', 'index.php', '../../', '../../../'),
(5, 'workflow', '1', 0, 'wflow', 16, 1, '', '', '', '', ''),
(6, 'comunicazioni', '1', 1, 'com', 32, 1, 'modules/communication', 'modules/communication/lib', 'index.php', '../../', '../../../'),
(7, 'area docenti', NULL, 1, 'teachers', 64, 2, 'intranet/teachers', 'lib', 'index.php', '', ''),
(8, 'alunni', NULL, 1, 'students', 128, 2, 'intranet/alunni', 'lib', 'index.php', '', ''),
(9, 'progetti', NULL, 0, 'projects', 256, 1, '', '', '', '', ''),
(10, 'genitori', NULL, 1, 'parents', 512, 2, 'intranet/genitori', 'lib', 'index.php', '', ''),
(11, 'albo', '4', 1, 'albo', 1024, 1, '', '', '', '', '');

CREATE TABLE IF NOT EXISTS `rb_moduli_orario` (
  `id_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `giorni` int(11) NOT NULL,
  `ore_settimanali` int(11) NOT NULL,
  PRIMARY KEY (`id_modulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `rb_moduli_orario` (`id_modulo`, `giorni`, `ore_settimanali`) VALUES
(1, 6, 30),
(2, 5, 40);

CREATE TABLE IF NOT EXISTS `rb_note_didattiche` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT,
  `docente` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `alunno` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `materia` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `data` date NOT NULL,
  `note` text,
  PRIMARY KEY (`id_nota`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_note_disciplinari` (
  `id_nota` int(11) NOT NULL AUTO_INCREMENT,
  `docente` int(11) NOT NULL,
  `data` date NOT NULL,
  `classe` int(11) NOT NULL,
  `alunno` int(11) DEFAULT NULL,
  `tipo` smallint(6) NOT NULL DEFAULT '1',
  `descrizione` text NOT NULL,
  `sanzione` varchar(200) DEFAULT NULL,
  `anno` int(11) NOT NULL,
  PRIMARY KEY (`id_nota`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_obiettivi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text NOT NULL,
  `descrizione` text,
  `docente` int(11) DEFAULT NULL,
  `materia` int(11) NOT NULL,
  `ordine_scuola` int(11) NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `anno` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_obiettivi_classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obiettivo` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_obiettivi_verifica` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_obiettivo` int(11) NOT NULL,
  `id_verifica` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_orario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giorno` varchar(20) NOT NULL,
  `ora` tinyint(4) NOT NULL,
  `inizio_ora` time NOT NULL,
  `classe` smallint(6) NOT NULL,
  `materia` smallint(4) NOT NULL DEFAULT '1',
  `materia2` tinyint(4) DEFAULT NULL,
  `sostegno` tinyint(1) NOT NULL DEFAULT '0',
  `sostegno2` int(11) DEFAULT NULL,
  `anno` int(11) NOT NULL,
  `docente` int(11) DEFAULT NULL,
  `descrizione` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniq_ora` (`giorno`,`ora`,`classe`),
  KEY `docente` (`docente`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_pagelle` (
  `id_pagella` int(11) NOT NULL AUTO_INCREMENT,
  `id_pubblicazione` int(11) NOT NULL,
  `id_alunno` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `desc_classe` char(2) NOT NULL,
  `esito` varchar(250) DEFAULT NULL,
  `id_file` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_pagella`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_parametri_configurabili` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `codice` varchar(100) NOT NULL,
  `descrizione` text NOT NULL,
  `tabella_dati` varchar(100) DEFAULT NULL,
  `permessi` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `rb_parametri_configurabili` (`id`, `nome`, `codice`, `descrizione`, `tabella_dati`, `permessi`) VALUES
(1, 'Tipologia prove', 'tipologia_prove', 'Tipologie di prove tra le quali il docente puo` scegliere, per visualizzarle nel proprio registro personale', 'rb_tipologia_prove', 2),
(2, 'Attivazione registro per obiettivi', 'registro_obiettivi', 'Flag che indica se il registro personale del docente per obiettivi e` attivo o no', NULL, 2);

CREATE TABLE IF NOT EXISTS `rb_parametri_pagella` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `ordine_scuola` tinyint(4) NOT NULL,
  `quadrimestre` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_parametri_utente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utente` int(11) NOT NULL,
  `id_parametro` int(11) NOT NULL,
  `valore` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_profili` (
  `id` int(11) NOT NULL,
  `data_nascita` varchar(20) DEFAULT NULL,
  `indirizzo` varchar(150) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `cellulare` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `messenger` varchar(50) DEFAULT NULL,
  `web` varchar(120) DEFAULT NULL,
  `blog` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rb_profili_alunni` (
  `id_alunno` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `messenger` varchar(100) DEFAULT NULL,
  `blog` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_alunno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_progetti` (
  `id_progetto` smallint(6) NOT NULL AUTO_INCREMENT,
  `nome` varchar(250) NOT NULL,
  `descrizione` text NOT NULL,
  `anno_inizio` tinyint(4) NOT NULL,
  `classi` varchar(50) NOT NULL,
  `sede` tinyint(4) NOT NULL,
  `referenti` varchar(35) NOT NULL,
  `attivo` tinyint(1) NOT NULL DEFAULT '1',
  `sito` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_progetto`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_progressivi_albo` (
  `progressivo_atto` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_pubblicazione_pagelle` (
  `id_pagella` int(11) NOT NULL AUTO_INCREMENT,
  `anno` int(11) NOT NULL,
  `quadrimestre` int(11) NOT NULL,
  `data_pubblicazione` date DEFAULT NULL,
  `ora_pubblicazione` time DEFAULT NULL,
  `stato_scrutinio` int(11) DEFAULT '2',
  `disponibili_docenti` date DEFAULT NULL,
  `data_pubblicazione_sp` date DEFAULT NULL,
  `ora_pubblicazione_sp` time DEFAULT NULL,
  `stato_scrutinio_sp` tinyint(4) DEFAULT NULL,
  `disponibili_docenti_sp` date DEFAULT NULL,
  PRIMARY KEY (`id_pagella`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_registri_personali` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anno` int(11) NOT NULL,
  `docente` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `materia` int(11) NOT NULL,
  `file` varchar(40) DEFAULT NULL,
  `data_creazione` date DEFAULT NULL,
  `alunno` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_reg_alunni` (
  `id_registro` int(11) NOT NULL,
  `id_alunno` int(11) NOT NULL,
  `ingresso` time DEFAULT NULL,
  `uscita` time DEFAULT NULL,
  `note` text,
  `giustificata` date DEFAULT NULL,
  `id_classe` int(11) DEFAULT NULL COMMENT 'contiene l''id classe attuale dell''alunno (potrebbe essere variata in corso d''anno)',
  PRIMARY KEY (`id_registro`,`id_alunno`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rb_reg_classi` (
  `id_reg` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` smallint(6) NOT NULL,
  `id_anno` smallint(6) NOT NULL,
  `data` date NOT NULL,
  `ingresso` time NOT NULL DEFAULT '08:30:00',
  `uscita` time NOT NULL DEFAULT '13:30:00',
  PRIMARY KEY (`id_reg`),
  UNIQUE KEY `get_idreg` (`id_classe`,`id_anno`,`data`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_reg_firme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_registro` int(11) NOT NULL,
  `ora` tinyint(4) NOT NULL,
  `firma` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
  `docente` int(11) DEFAULT NULL,
  `materia` int(11) DEFAULT NULL,
  `docente_compresenza` int(11) DEFAULT NULL,
  `materia_compresenza` int(11) DEFAULT NULL,
  `argomento` text,
  `anno` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_regora` (`id_registro`,`ora`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_reg_firme_sostegno` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_registro` int(11) NOT NULL,
  `id_ora` int(11) NOT NULL,
  `ora` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `classe` int(11) NOT NULL,
  `docente` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_reg_personale` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `id_reg` int(11) NOT NULL DEFAULT '0',
  `docente` int(11) NOT NULL,
  `ora` smallint(6) NOT NULL,
  `materia` smallint(6) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `idx_ora` (`id_reg`,`docente`,`ora`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_scrutini` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alunno` int(11) DEFAULT NULL,
  `classe` smallint(6) DEFAULT NULL,
  `anno` smallint(6) DEFAULT NULL,
  `quadrimestre` smallint(6) DEFAULT NULL,
  `materia` smallint(6) DEFAULT NULL,
  `voto` smallint(6) DEFAULT NULL,
  `assenze` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_sedi` (
  `id_sede` smallint(6) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `indirizzo` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_sede`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_stati_scrutinio` (
  `id_stato` int(11) NOT NULL AUTO_INCREMENT,
  `stato` varchar(50) NOT NULL,
  PRIMARY KEY (`id_stato`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `rb_stati_scrutinio` (`id_stato`, `stato`) VALUES
(1, 'Chiuso'),
(2, 'Aperto'),
(3, 'Riaperto');

CREATE TABLE IF NOT EXISTS `rb_stud_works` (
  `id_work` int(11) NOT NULL AUTO_INCREMENT,
  `anno` char(9) NOT NULL,
  `data_inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `autore` varchar(200) NOT NULL,
  `classe` int(11) DEFAULT NULL,
  `owner` int(11) NOT NULL,
  `abstract` text NOT NULL,
  `titolo` varchar(200) NOT NULL,
  `file` varchar(100) NOT NULL,
  `dw_counter` int(11) NOT NULL DEFAULT '0',
  `_classe` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id_work`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_tags` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(75) NOT NULL,
  PRIMARY KEY (`tid`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_tipievento_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(250) NOT NULL,
  `descrizione` text NOT NULL,
  `numeric1` text,
  `numeric2` text NOT NULL,
  `text1` text NOT NULL,
  `text2` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_tipi_note_didattiche` (
  `id_tiponota` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(200) NOT NULL,
  PRIMARY KEY (`id_tiponota`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

INSERT INTO `rb_tipi_note_didattiche` (`id_tiponota`, `descrizione`) VALUES
(1, 'Impreparato'),
(2, 'Non ha l''attrezzatura'),
(3, 'Risposta corretta'),
(4, 'Risposta errata o assente'),
(5, 'Non segue la lezione'),
(6, 'Non ha fatto i compiti'),
(7, 'Non lavora');

CREATE TABLE IF NOT EXISTS `rb_tipi_note_disciplinari` (
  `id_tiponota` int(11) NOT NULL AUTO_INCREMENT,
  `descrizione` text,
  PRIMARY KEY (`id_tiponota`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `rb_tipi_note_disciplinari` (`id_tiponota`, `descrizione`) VALUES
(1, 'Nota del docente'),
(2, 'Nota del Consiglio di classe'),
(3, 'Ammonizione del Consiglio di classe'),
(4, 'Ammonizione del Dirigente'),
(5, 'Sospensione');

CREATE TABLE IF NOT EXISTS `rb_tipologia_prove` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipologia` varchar(75) NOT NULL,
  `label` varchar(75) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `rb_tipologia_prove` (`id`, `tipologia`, `label`, `default`) VALUES
(1, 'Verifica scritta', 'Scritti', 1),
(2, 'Verifica orale', 'Orali', 1),
(3, 'Prova pratica', 'Prove pratiche', 0);

CREATE TABLE IF NOT EXISTS `rb_tipologia_scuola` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(200) NOT NULL,
  `codice` char(2) NOT NULL,
  `has_admin` tinyint(1) NOT NULL,
  `attivo` int(11) NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `rb_tipologia_scuola` (`id_tipo`, `tipo`, `codice`, `has_admin`, `attivo`) VALUES
(1, 'Scuola secondaria di primo grado', 'SM', 1, 1),
(2, 'Scuola primaria', 'SP', 1, 1),
(3, 'Scuola dell''infanzia', 'SI', 1, 1),
(4, 'Istituto comprensivo', 'IC', 0, 1),
(5, 'Scuola secondaria di II grado', 'SS', 0, 0),
(6, 'Scegli', '', 0, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `rb_utenti`
--

CREATE TABLE IF NOT EXISTS `rb_utenti` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(125) NOT NULL,
  `password` varchar(32) CHARACTER SET latin1 NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cognome` varchar(100) NOT NULL,
  `accessi` int(11) NOT NULL DEFAULT '0',
  `permessi` smallint(6) NOT NULL,
  `last_access` datetime DEFAULT NULL,
  `previous_access` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=304 ;

--
-- Dump dei dati per la tabella `rb_utenti`
--

INSERT INTO `rb_utenti` (`uid`, `username`, `password`, `nome`, `cognome`, `accessi`, `permessi`, `last_access`, `previous_access`) VALUES
(1, 'admin', 'ed5e9dd0aba4cf0d013201e6521330bf', 'Admin', 'System', 361, 225, '2013-11-06 19:17:14', '2013-11-06 19:06:32'),
(2, 'rbachis', 'ed5e9dd0aba4cf0d013201e6521330bf', 'Riccardo', 'Bachis', 664, 2, '2013-11-06 19:10:51', '2013-11-06 19:05:45'),
(3, 'carla.masala', '835542cc403e457a3ef164a250bc74d3', 'Carla', 'Masala', 165, 2, '2013-11-06 14:06:13', '2013-11-05 14:28:40'),
(4, 'angela.pinna', '7820881b545bbffe89f4133313eee2fb', 'Angela', 'Pinna', 142, 2, '2013-11-06 17:34:37', '2013-11-06 12:34:55'),
(5, 'franco.casu', '1e93fa37a46c5bd1afd047de33658aa6', 'Franco', 'Casu', 243, 2, '2013-11-06 10:41:33', '2013-11-06 09:39:23'),
(6, 'romano.vittinio', '27e4b8ca60c217113467f527bce3fb8c', 'Romano', 'Vittinio', 68, 2, '2013-11-05 15:28:28', '2013-11-04 20:18:01'),
(7, 'mariano.garau', '36d2891be2fdab5e38a02344af612aaa', 'Mariano', 'Garau', 60, 2, '2013-10-30 08:57:15', '2013-10-26 22:13:59'),
(8, 'giovanna.cui', '80ff139f978e581fb1cf5fc976c68832', 'Giovanna', 'Cui', 147, 2, '2013-11-05 10:40:50', '2013-11-05 10:37:36'),
(9, 'lucia.guaita', '5039778f7fdeb5649e504533f489328c', 'Lucia', 'Guaita', 147, 2, '2013-11-06 13:20:05', '2013-11-05 16:02:21'),
(10, 'carmen.caddeo', 'eba52c04b6c1dce8a140fa17a7bb7746', 'Carminetta', 'Caddeo', 193, 2, '2013-11-06 17:30:47', '2013-11-06 12:06:33'),
(11, 'daniela.pilleri', '843fdfe22540572f5699df72a74552b1', 'Daniela', 'Pilleri', 194, 2, '2013-11-06 15:15:35', '2013-11-05 14:35:43'),
(12, 'cristina.frongia', '1fd7388ce134fb67d9477d7712e31f89', 'Maria Cristina', 'Frongia', 46, 2, '2013-10-26 10:00:21', '2013-10-25 11:19:44'),
(13, 'marina.frau', '8bd5b67121d84eecff7a0f2130509521', 'Marina', 'Frau', 54, 2, '2013-10-30 11:16:57', '2013-10-30 11:16:25'),
(14, 'giovanna.caboni', 'f106b7f99d2cb30c3db1c3cc0fde9ccb', 'Maria Giovanna', 'Caboni', 210, 10, '2013-11-05 22:01:37', '2013-11-05 08:49:46'),
(15, 'cristina.paulis', '0c74ac34d6652b2da30488d4f38496d8', 'Maria Cristina', 'Paulis', 48, 2, '2013-11-05 12:28:46', '2013-10-31 11:27:55'),
(16, 'rafaele.serafini', '194a3b94e591578dfce43ac284a20297', 'Rafaele', 'Serafini', 54, 2, '2013-11-05 13:07:14', '2013-11-04 18:24:52'),
(17, 'manuela.cuccu', '96a96d7148d5a4685e0473c70ecbb763', 'Anna Manuela', 'Cuccu', 393, 2, '2013-11-06 19:19:16', '2013-11-06 19:19:15'),
(18, 'isabella.coppola', 'f8bedca80ca62b1b306eeae33411858a', 'Isabella', 'Coppola', 317, 2, '2013-11-06 08:26:49', '2013-11-05 20:09:14'),
(19, 'lucia.madau', '3ba430337eb30f5fd7569451b5dfdf32', 'Lucia', 'Madau', 28, 2, '2013-11-02 10:55:51', '2013-11-02 09:52:33'),
(20, 'patrizia.melis', 'cfbbb8ce992d24f8d26617e22c07d6fe', 'Patrizia', 'Melis', 12, 2, '2013-11-04 18:54:39', '2013-10-28 19:47:25'),
(22, 'rita.mallica', '79284cdbab993930084de98016c43578', 'Rita', 'Mallica', 4, 32, '2013-09-17 10:57:57', '2013-03-01 13:06:59'),
(23, 'annamaria.boi', 'f27d3de8e6573cac31e42a8e627ad7d2', 'Anna Maria', 'Boi', 0, 32, NULL, NULL),
(24, 'rosella.meletti', '293c1d55b6bb625e632ab95900942828', 'Rosella', 'Meletti', 108, 32, '2013-11-05 12:13:45', '2013-11-04 12:17:03'),
(26, 'daniela.lancellotti', '09fa8c48443ca37a4f808d447a4e03ae', 'Daniela', 'Lancellotti', 38, 128, '2013-10-30 13:00:16', '2013-10-28 14:04:37'),
(33, 'stefania.demontis', 'cd242b1da4b7c06c81ce7dfacbeb68d7', 'Stefania', 'Demontis', 8, 8, NULL, NULL),
(35, 'maria.moi', '54ed0a114e377b80705c3016b1a75158', 'Maria Antonietta', 'Moi', 13, 8, NULL, NULL),
(36, 'alessandra.aramu', '7e6ef420862986ab57224dec4b0feb4f', 'Alessandra', 'Aramu', 9, 8, NULL, NULL),
(37, 'simonetta.meloni', '0b53d3a1cc324e93bb513d29c29d24a0', 'Simonetta', 'Meloni', 4, 8, NULL, NULL),
(39, 'cristina.vargiu', 'ef4b27210542ee5a0397c5b79a3176a6', 'Cristina', 'Vargiu', 1, 8, NULL, NULL),
(40, 'annamaria.congiu', '1a99cfe626450e0e3066bf88b42923c4', 'Anna Maria', 'Congiu', 0, 8, NULL, NULL),
(41, 'diego.demontis', '8f5a622eb1c275c4549e91356e8d6f64', 'Diego', 'Demontis', 18, 8, NULL, NULL),
(42, 'rosella.marceddu', '84a64c25bfff68f268b37fae0191254d', 'Rosella', 'Marceddu', 69, 8, NULL, NULL),
(43, 'giorgio.carta', 'd792cb43190599b8661222e3ab8c0f45', 'Giorgio', 'Carta', 20, 8, NULL, NULL),
(44, 'francaangela.fais', 'ab2890a685d4401412e709a60c3db00d', 'Franca Angela', 'Fais', 28, 8, NULL, NULL),
(45, 'rita.mongitto', 'ec76436fda5ecf792701f5352c4b2edd', 'Rita', 'Mongitto', 23, 8, NULL, NULL),
(46, 'tiziana.pinna', 'bceda3c4b79fe6adede693e092cc6946', 'Tiziana', 'Pinna', 37, 8, NULL, NULL),
(47, 'efisio.melis', 'a4010945e4bd924bc2a890a2effea0e6', 'Efisio', 'Melis', 19, 8, NULL, NULL),
(48, 'daniele.cherchi', '26f92a8e1380e1c63cb16feacc2e0e61', 'Daniele', 'Cherchi', 48, 8, '2013-01-31 14:05:28', '2013-01-31 14:05:28'),
(49, 'lydiemartine.licheri', '2c98cbb56c7205f6c12ddbb04161add4', 'Lydie Martine', 'Licheri', 27, 8, '2013-01-21 19:14:39', NULL),
(50, 'carla.pinna', 'c4cf5b592a70bf8a40f2749e50e42c12', 'Carla', 'Pinna', 6, 8, NULL, NULL),
(51, 'lucia.ladu', '43b400f7bd62adaebb80a6cfc3804c13', 'Lucia', 'Ladu', 43, 8, NULL, NULL),
(52, 'barbara.caria', '58801d0114d586471f818e2a814a6126', 'Barbara', 'Caria', 14, 8, NULL, NULL),
(53, 'antonella.marcia', '1b8becae8ef62f78675393920f4fabc9', 'Antonella', 'Marcia', 64, 8, '2013-01-26 20:20:02', '2013-01-25 18:29:56'),
(54, 'graziella.vacca', '17722ed44e17a3dc4018f782449028b2', 'Graziella', 'Vacca', 8, 8, NULL, NULL),
(55, 'walter.figus', '3231818d4f45511a2da82d14bd370959', 'Walter', 'Figus', 35, 8, NULL, NULL),
(56, 'fabrizio.montisci', '4fb3e21e8bd1a5f5dc01f6027fa8d748', 'Fabrizio', 'Montisci', 9, 8, NULL, NULL),
(57, 'angela.cui', '45109db7452a9f1858c046e4c7524b4a', 'Angela', 'Cui', 29, 8, NULL, NULL),
(58, 'annalisa.uccella', 'e9feaaaa05430085fd47fcd056156bf8', 'Annalisa', 'Uccella', 110, 8, NULL, NULL),
(59, 'andrea.peddis', 'b844d044ce33f0d579cea92d45b4f419', 'Andrea', 'Peddis', 517, 8, NULL, NULL),
(60, 'claudiamichela.plaisant', 'd7a9b653571cb2e2c342738f4790bc03', 'Claudiamichela', 'Plaisant', 26, 8, NULL, NULL),
(62, 'pierangela.filippi', 'dc64bc42d65832dcb9900456fe68edec', 'Pierangela', 'Filippi', 7, 8, NULL, NULL),
(63, 'claudia.ennas', '6aaf672cb0abd1d8485726c9660ef0bb', 'Claudia', 'Ennas', 7, 8, NULL, NULL),
(64, 'sandro.riola', '54cb4a6572f32210f2e1d8ffea566768', 'Sandro', 'Riola', 63, 8, NULL, NULL),
(66, 'patrizia.sanna', 'c14b9ad96e8c39962a8b6279c43445b7', 'Patrizia', 'Sanna', 61, 8, NULL, NULL),
(67, 'ornella.porcedda', '40441eabf2be24fc32e9081f29b0b6a1', 'Ornella', 'Porcedda', 72, 8, '2013-02-03 15:33:52', '2013-02-01 20:14:20'),
(69, 'roberto.oddo', '9b2db2be11bbac547fc212e87b00308b', 'Roberto', 'Oddo', 9, 8, NULL, NULL),
(70, 'sebastiana.manca', '901613cb8bac5c6f284a337b23c04835', 'Sebastiana', 'Manca', 31, 8, '2013-02-12 12:00:46', '2013-02-02 14:27:17'),
(71, 'lucia.porcheddu', '7aaf8d8bee0f821c77da784f3283daf1', 'Lucia', 'Porcheddu', 45, 8, '2013-02-04 20:35:35', NULL),
(74, 'katia.melis', '3ee519340b9aa9f36d75f7d4806657d1', 'Katia', 'Melis', 21, 8, NULL, NULL),
(75, 'giovanni.contrino', 'b008d8189b747b03c8c6507eae2b134b', 'Giovanni', 'Contrino', 70, 8, '2013-02-02 19:54:50', NULL),
(76, 'mariagabriella.caboni', '6917610344694d22ca18af7d44956d56', 'Maria Gabriella', 'Caboni', 10, 8, NULL, NULL),
(77, 'maurizio.secchi', '0bf0d64101191a0c7bfd2b1cddd29f89', 'Maurizio', 'Secchi', 15, 8, '2013-02-07 21:16:55', NULL),
(79, 'rosaliarita.porceddu', '487c3ece4c3353b16007876ac42f4eb4', 'Rosalia Rita', 'Porceddu', 21, 8, NULL, NULL),
(80, 'sandra.ballocco', '222ddb781711135fed9cf59c4d8ad9bb', 'Sandra', 'Ballocco', 109, 8, NULL, NULL),
(81, 'antonello.mereu', '6d657cf3c9b6c77fddf28be359b6902e', 'Antonello', 'Mereu', 7, 8, NULL, NULL),
(82, 'silvano.aru', 'dba770f151ef1a4bf89ffc9b2afbfb3c', 'Silvano', 'Aru', 0, 8, NULL, NULL),
(83, 'gianluca.collu', '82d12864807de22ab728a2f66fdce9cd', 'Gianluca', 'Collu', 24, 8, NULL, NULL),
(85, 'sabrina.puddu', '6cda13294c8c93f649483d4387cd4374', 'Sabrina', 'Puddu', 23, 8, NULL, NULL),
(86, 'giovanni.mancuso', '4ebd062d80a926065798c24b2bdc42e1', 'Giovanni', 'Mancuso', 18, 8, NULL, NULL),
(87, 'graziella.caddeo', '34094120a283a207df92618c3e81f3cf', 'Graziella', 'Caddeo', 47, 8, NULL, NULL),
(88, 'marinella.mameli@gmail.com', '3d267beb1de710357b9d9b0c77404dff', 'Marinella', 'Mameli', 58, 10, '2013-11-05 21:30:13', '2013-11-05 19:28:39'),
(89, 'maurizio.usai', '45a773dd131ced0332964eb71fbba5d7', 'Maurizio', 'Usai', 16, 8, NULL, NULL),
(91, 'podda.mario', 'f94c46cefb41191902ec6950108ab868', 'Podda', 'Mario', 13, 8, NULL, NULL),
(92, 'carla.pilleri', 'b52a1782839ab9ee75c0a82287b80632', 'Carla', 'Pilleri', 0, 8, NULL, NULL),
(93, 'carlo.boi', 'c2fa277a8fc996e43ab19ffab55b4c39', 'Carlo', 'Boi', 10, 8, NULL, NULL),
(95, 'silvia.pischedda', '6375dae61e2bbc169ff9f9fb6f679ab9', 'Silvia', 'Pischedda', 5, 8, NULL, NULL),
(96, 'gina.pirastru', 'bfc1ee446d97c85d1571b3fce7f1a5f3', 'Gina', 'Pirastru', 24, 8, NULL, NULL),
(97, 'mery.floris', '5909a9f22c093d73edfc5c71743985af', 'Mery', 'Floris', 11, 8, NULL, NULL),
(98, 'caterina.carta', '04d1cc7487c7b8e8cc48774fcc5c6911', 'Caterina', 'Carta', 3, 8, NULL, NULL),
(99, 'mauro.gessa', 'e808e96cf1f7538d98017dd3650321e7', 'Mauro', 'Gessa', 29, 8, '2013-02-14 20:36:19', NULL),
(100, 'liliana.floris', 'c14b4d1fc5763469bd9a1e73ce786c3e', 'Liliana', 'Floris', 20, 8, NULL, NULL),
(101, 'gianluigi.rosas', 'f941dc5ae920c2d808a9e95457916a12', 'Gianluigi', 'Rosas', 25, 8, NULL, NULL),
(102, 'yleina.atzeni', 'cd7d903f5ae2305151d950b406e9d3fd', 'Yleina', 'Atzeni', 112, 8, NULL, NULL),
(105, 'mariagrazia.salvatore', '11c98499624e96bc4e1b8be9ce86da8a', 'Maria Grazia', 'Salvatore', 6, 8, NULL, NULL),
(107, 'pietro.ferraro', '9b7ead814d67263b5dd3418d00f8222b', 'Pietro', 'Ferraro', 26, 8, NULL, NULL),
(109, 'sergio.salidu', '3bcfbb02ce50087db9d6a7c8e363752f', 'Sergio', 'Salidu', 13, 8, NULL, NULL),
(110, 'ivana.mameli', 'd2b320cf598c055a64198f588c4a2a27', 'Ivana', 'Mameli', 15, 8, NULL, NULL),
(111, 'daniela.pinna', 'b860dbccc81dbc59c0864d3bb9d31da0', 'Daniela', 'Pinna', 17, 8, NULL, NULL),
(113, 'isidoro.parodo', '82e825b8ab546f12b83dee2067f65622', 'Isidoro', 'Parodo', 65, 8, NULL, NULL),
(114, 'roberto.leo', '2db501cadf3b23a54e09bd3e5b8e83f2', 'Roberto', 'Leo', 12, 8, NULL, NULL),
(115, 'mariacristina.petraroia', '11264998c03c5aadbdddc2526f36ae47', 'Maria Cristina', 'Petraroia', 0, 8, NULL, NULL),
(116, 'renata.manca', 'b8b248f5a3ea460351b45637806cb025', 'Renata', 'Manca', 21, 8, NULL, NULL),
(117, 'mariaroxana.ion', '133e9d3bd9f1212d103f57bb251a07c7', 'Maria Roxana', 'Ion', 1, 8, NULL, NULL),
(119, 'manuela.farci', 'edc9e54935bba8ee1b3dfa118e4f062e', 'Manuela', 'Farci', 2, 8, NULL, NULL),
(120, 'caterina.puligheddu', '03a276626352999e80be93473c875dcd', 'Caterina', 'Puligheddu', 1, 8, NULL, NULL),
(121, 'antonello.fadda', '6af9761f75bad6a2423f8a07e89aa915', 'Antonello', 'Fadda', 13, 8, NULL, NULL),
(122, 'angela.cau', 'a453897d65ac22cc4026a6d3fbec18dc', 'Angela', 'Cau', 28, 8, NULL, NULL),
(123, 'angela.origa', '4e4e66644d7cb0f1f0026652cad4bb0e', 'Angela', 'Origa', 3, 8, NULL, NULL),
(124, 'daniela.brighi', '934432619d42e0dbd4a3d18aeb86489a', 'Daniela', 'Brighi', 44, 8, NULL, NULL),
(125, 'rita.nannizzi', '2b620fe8ba7e8ce03cb442ca34f41fd7', 'Rita', 'Nannizzi', 0, 8, NULL, NULL),
(127, 'ritaclara.contini', '1ca33de5cdf4866fd370fba3acaf7f8a', 'Rita Clara', 'Contini', 49, 8, NULL, NULL),
(128, 'maria.pinna', 'e8fb1ead40def58040db8e2c419bbc93', 'Maria', 'Pinna', 6, 8, NULL, NULL),
(129, 'annacarla.serra', '66d83718cd13ad5fc06321edb1ef224f', 'Anna Carla', 'Serra', 11, 8, NULL, NULL),
(130, 'simonetta.granella', 'bee7b33d57a2b20a12f1735950c7fcd3', 'Simonetta', 'Granella', 11, 8, NULL, NULL),
(131, 'bruno.deidda', '039bb2219ececb208c30ed5254cc981c', 'Bruno', 'Deidda', 32, 8, NULL, NULL),
(132, 'patrizia.piras', 'fe3cdb6c078a412cbcad5956c26000ab', 'Patrizia', 'Piras', 0, 8, NULL, NULL),
(134, 'ileana.sira', '42be0ce6f8a22814ac4799a9814ff838', 'Ileana', 'Sira', 7, 8, NULL, NULL),
(136, 'giovanna.usai', '9925d4256a2b83b5bc67e30d464382a0', 'Giovanna', 'Usai', 0, 8, NULL, NULL),
(138, 'susanna.peddio', 'c3ab5437d2455d8ea20d10350d7dc112', 'Susanna', 'Peddio', 4, 8, '2013-02-10 12:00:12', NULL),
(139, 'antonella.senis', '0573f9f1002101665ae955acb28c488f', 'Antonella', 'Senis', 33, 8, NULL, NULL),
(140, 'luigi.pusceddu', '4d4e8ad67f7a3206c6cab4301fe953fe', 'Luigi', 'Pusceddu', 7, 8, NULL, NULL),
(142, 'roberto.marongiu', '609ffa5a836f049ccc6b2eb751ccc019', 'Roberto', 'Marongiu', 21, 8, NULL, NULL),
(143, 'giorgio.brundu', 'd13f2724c1fcb9e9f1972a7f83728bfc', 'Giorgio', 'Brundu', 38, 8, NULL, NULL),
(144, 'manuela.spiga', '0d0d67180c47f28900868f20ecc6bb64', 'Manuela', 'Spiga', 5, 8, NULL, NULL),
(146, 'stefania.sirigu', '4ad3a10053ac3de27280c1680aa79f82', 'Stefania', 'Sirigu', 0, 8, NULL, NULL),
(147, 'tiziana.dipaola', '3ee3f9ce5546a2853ccd5e2af0e49605', 'Tiziana', 'Di Paola', 9, 8, NULL, NULL),
(148, 'daniela.anedda', '8e4815fdf3d55ce586f67cee00196999', 'Daniela', 'Anedda', 10, 8, NULL, NULL),
(149, 'silvia.serra', '34c77eeefd582c22839118732354177c', 'Silvia', 'Serra', 1, 8, NULL, NULL),
(151, 'stefania.ballocco', 'd3cad467c39e4a53ad2eb0b612ff906d', 'Stefania', 'Ballocco', 16, 8, NULL, NULL),
(152, 'emilianomulas.foto@yahoo.it', '637a6398f7d38e4faca80c316be5fba2', 'Emiliano', 'Mulas', 3, 8, NULL, NULL),
(153, 'tiziana.meloni', 'ca4ab24a48cf616e0ebb568ceb34e7fa', 'Tiziana', 'Meloni', 15, 8, NULL, NULL),
(154, 'silvano.medda', 'b5e338c17a2bdcb6df6bdac0ec3f4aee', 'Silvano', 'Medda', 5, 8, NULL, NULL),
(155, 'alfredo.villonio', '7902b7c0be5cedb6fbada8d4c7fc42a0', 'Alfredo', 'Villonio', 3, 8, NULL, NULL),
(156, 'angela.mocci', '2c019f9992b0dbce240f4db4ceb73109', 'Angela', 'Mocci', 19, 8, NULL, NULL),
(157, 'anna.ecca', '75e0ce617fc0bd2f5ceb946dc1fa530c', 'Anna', 'Ecca', 2, 8, NULL, NULL),
(158, 'mariagrazia.maccio', 'f61078f51cefea301ee70a0ff5e0b8a9', 'Maria Grazia', 'Maccio', 40, 8, NULL, NULL),
(160, 'alessandra.giannoni', 'dcb0b7d3b9de631a1f08d71db75a016a', 'Alessandra', 'Giannoni', 0, 8, NULL, NULL),
(161, 'valentina.urru', '3ef8a9d5b05edad255125198d819b2ff', 'Valentina', 'Urru', 4, 8, NULL, NULL),
(162, 'patrizia.fontana', 'af439f03b5136d0f266c404b6f9a736e', 'Patrizia', 'Fontana', 8, 8, NULL, NULL),
(163, 'manuela.melis', '9cc1ebed4ee8828f2fb8c581c7002a4e', 'Manuela', 'Melis', 20, 8, NULL, NULL),
(164, 'mariamaddalena.pitzeri', '217223b4000b305dc4cc22389291fa1e', 'Maria Maddalena', 'Pitzeri', 1, 8, NULL, NULL),
(165, 'francesca.murru', 'ddf2b12bdc75d5b324ce4829f19d1e8f', 'Francesca', 'Murru', 0, 8, NULL, NULL),
(166, 'annachiara.cau', '235d63e1033c2e73b0716fe828662361', 'Anna Chiara', 'Cau', 8, 8, NULL, NULL),
(167, 'mario.marruncheddu', '68d11dd5ba967e5ad5e2a6e1ee5eec15', 'Mario', 'Marruncheddu', 18, 8, NULL, NULL),
(168, 'dario.quesada', 'c22934011bee99ec857f274b9546d028', 'Dario', 'Quesada', 10, 8, NULL, NULL),
(169, 'roberta.saba', 'c919a641aba85a194729ef4c1787bee4', 'Roberta', 'Saba', 27, 8, NULL, NULL),
(171, 'sergio.filippi', 'a8e63ddc6670576f547ce08dd95bf5a7', 'Sergio', 'Filippi', 13, 8, NULL, NULL),
(172, 'sabrina.serra', 'af39996b2f44437110a97f8a5b20ec7a', 'Sabrina', 'Serra', 11, 8, NULL, NULL),
(173, 'sara.congia', '63e0b5e8186062d1b329e2fc8c1374a5', 'Sara', 'Congia', 1, 8, NULL, NULL),
(175, 'gianluca.zucca', '399259396e0fb976ca2db29197010c7c', 'Gianluca', 'Zucca', 16, 8, NULL, NULL),
(176, 'simonetta.serreli', '3f5dc6447c04b4538bfac2dba7f2aacf', 'Simonetta', 'Serreli', 0, 8, NULL, NULL),
(177, 'monica.tanda', '0696a71bd6ec3e805515fe376fc306e8', 'Monica', 'Tanda', 5, 8, NULL, NULL),
(178, 'rita.murgia', '45b71551db6dc1c8f1666b46e273785d', 'Rita', 'Murgia', 1, 8, NULL, NULL),
(179, 'francesco.carta', '120917e44dd5395ddd19b8029920044e', 'Francesco', 'Carta', 8, 8, NULL, NULL),
(180, 'mariapaola.cardia', '84166a4d2f20ff370f405ee385299e50', 'Maria Paola', 'Cardia', 0, 8, NULL, NULL),
(181, 'marinella.fronteddu', '8f991b1781f88346692eb1bc42ef669e', 'Marinella', 'Fronteddu', 0, 8, NULL, NULL),
(182, 'tiziana.melis', 'b1ff5612c7a311baafe187f6c8bad097', 'Tiziana', 'Melis', 0, 8, NULL, NULL),
(184, 'giuliana.orru', 'c238536626f8f3e90af0ffb08cb8a50f', 'Giuliana', 'Orru', 0, 8, NULL, NULL),
(185, 'manuela.perini', '3747f416d97e8bd15fe192f90aa40098', 'Manuela', 'Perini', 2, 8, NULL, NULL),
(186, 'giovanni.raia', '961cc46172612f047656592b49de8d64', 'Giovanni', 'Raia', 1, 8, NULL, NULL),
(188, 'teresa.medda', '9ea3f490e54433d109ef23f6092a0d81', 'Teresa', 'Medda', 6, 8, NULL, NULL),
(192, 'prova.prova', '189bbbb00c5f1fb7fba9ad9285f193d1', 'Prova', 'Prova', 2, 8, NULL, NULL),
(193, 'giorgia_floris@yahoo.it', '70317bb050c14bca9512eacf7b4036ae', 'Giorgia', 'Floris', 7, 64, '2013-10-16 17:07:50', '2013-09-07 15:53:39'),
(194, 'mariam.pedrazzoli', '4632f18ced673094b66331ac709e630a', 'Maria Margherita', 'Pedrazzoli', 49, 32, '2013-11-06 13:25:14', '2013-11-05 12:13:39'),
(195, 'marco.pistis', 'f5888d0bb58d611107e11f7cbc41c97a', 'Marco', 'Pistis', 54, 2, '2013-11-06 07:21:22', '2013-11-05 12:19:44'),
(196, 'valentino.murgia', 'c538b202af1f7c64b11bc4793ef7ac02', 'Valentino', 'Murgia', 36, 2, '2013-11-06 15:29:34', '2013-11-05 11:48:25'),
(197, 'annabiesse@tiscali.it', 'a70f9e38ff015afaa9ab0aacabee2e13', 'Anna', 'Basso', 0, 2, NULL, NULL),
(198, 'angelamaria.boi@tiscali.it', '2f7f599781db39c031e245b720e59b8c', 'Angela Maria', 'Boi', 27, 2, '2013-11-02 11:02:11', '2013-11-01 18:45:26'),
(199, 'boianna55@gmail.com', '9367c97268c91ccf35e6dae3e737b05f', 'Anna', 'Boi', 6, 2, '2013-10-18 17:34:00', '2013-10-18 17:12:20'),
(200, 'boi.marialaura@tiscali.it', '98b3e28ae556c0b0f41ed114e47bb056', 'Maria Laura', 'Boi', 11, 2, '2013-11-04 18:23:54', '2013-10-25 19:16:07'),
(201, 'annamaria.cabitza@istruzione.it', 'a70f9e38ff015afaa9ab0aacabee2e13', 'Anna Maria', 'Cabitza', 4, 2, '2013-10-17 10:52:40', '2013-10-09 18:08:55'),
(202, 'giufrance@tiscali.it', '190ecc0c4509ef2efba9378cc2147d5a', 'Simonetta', 'Casteggio', 15, 2, '2013-10-29 21:54:41', '2013-10-29 21:27:45'),
(203, 'cristina.chighine@alice.it', '0c74ac34d6652b2da30488d4f38496d8', 'Maria Cristina', 'Chighine', 30, 2, '2013-10-31 19:05:13', '2013-10-26 09:40:40'),
(204, 'francotocco@tiscali.it', '4f0113f6b71eb5cee02e52a509281417', 'Alessandra', 'Colombelli', 9, 2, '2013-10-29 15:45:22', '2013-10-17 21:39:59'),
(205, 'annapaola64@tiscali.it', 'a70f9e38ff015afaa9ab0aacabee2e13', 'Anna Paola', 'Curreli', 5, 2, '2013-10-25 17:52:31', '2013-10-18 18:13:49'),
(206, 'simo.desogus@tiscali.it', '190ecc0c4509ef2efba9378cc2147d5a', 'Simonetta', 'Desogus', 3, 2, '2013-10-31 12:10:30', '2013-10-10 10:27:50'),
(207, 'ritascolastica.facchin@istruzione.it', '2794d223f90059c9f705c73a99384085', 'Rita Scolastica', 'Facchin', 4, 2, '2013-10-15 16:41:39', '2013-10-15 16:41:01'),
(208, 'silvana.fenu@hotmail.it', 'f850bce37f95a998a2362e26062e7a22', 'Silvana', 'Fenu', 4, 2, '2013-10-22 21:26:49', '2013-10-15 23:37:24'),
(209, 'marialuisa.garau@istruzione.it', '263bce650e68ab4e23f28263760b9fa5', 'Maria Luisa', 'Garau', 0, 2, NULL, NULL),
(210, 'silgar51@tiscali.it', 'b24f165102cc0605598edd8f381fda98', 'Silvana', 'Garau', 10, 2, '2013-10-28 15:00:21', '2013-10-28 14:56:47'),
(211, 'manuela.guaita@istruzione.it', 'c48794a0a424965596fc7bbbd3245345', 'Manuela', 'Guaita', 11, 2, '2013-11-05 05:51:48', '2013-11-05 05:17:47'),
(212, 'ornella.lecis@istruzione.it', '891bda2603ac73b25d3ab6dbabc5a98c', 'Ornella', 'Lecis', 19, 2, '2013-11-06 15:41:23', '2013-11-05 15:42:37'),
(213, 'ristedo@tiscali.it', '860ee327b3da353d45c5b159339282d6', 'Stefania', 'Locci', 4, 2, '2013-10-22 17:28:53', '2013-10-14 11:00:50'),
(214, 'robimade@tiscali.it', '229e5b1363be0591e674cd57b3bb8645', 'Roberta', 'Madeddu', 1, 2, '2013-10-08 21:56:02', NULL),
(215, 'raffaela60mameli@gmail.com', '165e23898c02284b4b1194a1ceda5e31', 'Raffaela', 'Mameli', 0, 2, NULL, NULL),
(216, 'annapaola.mascia@istruzione.it', 'a70f9e38ff015afaa9ab0aacabee2e13', 'Anna Paola', 'Mascia', 24, 2, '2013-11-06 15:35:47', '2013-11-06 10:11:25'),
(217, 'ombrettamurgia2010@libero.it', 'ea780457068782e20406372f14ae9fc7', 'Ombretta', 'Murgia', 6, 2, '2013-10-27 21:33:05', '2013-10-17 12:50:19'),
(218, 'mmarina@tiscali.it', 'ce5225d01c39d2567bc229501d9e610d', 'Marina', 'Muscas', 8, 2, '2013-11-05 18:52:41', '2013-11-05 07:49:35'),
(219, 'sinie@tiscali.it', 'f850bce37f95a998a2362e26062e7a22', 'Silvana', 'Nieddu', 4, 2, '2013-10-21 10:53:27', '2013-10-20 20:37:48'),
(220, 'annamaria.olianas@gmail.com', '1113d7a76ffceca1bb350bfe145467c6', 'Anna Maria', 'Olianas', 5, 2, '2013-10-29 18:01:08', '2013-10-29 17:49:20'),
(221, 'giuseppina.pili@istruzione.it', '092146572196dc6b249c5863554dbe1e', 'Giuseppina', 'Pili', 18, 2, '2013-11-06 13:42:46', '2013-11-04 18:38:31'),
(222, 'patriziapili@tiscali.it', 'cfbbb8ce992d24f8d26617e22c07d6fe', 'Patrizia', 'Pili', 0, 2, NULL, NULL),
(223, 'elisabetta.pilisio@istruzione.it', '205bccc3939d16e91f1f465455888765', 'Elisabetta', 'Pilisio', 4, 2, '2013-10-19 00:01:42', '2013-10-19 00:00:54'),
(224, 'annalisa.pilurzu@alice.it', '6dbc3ebbd5bd51e5970ab5173999d82d', 'Annalisa', 'Pilurzu', 5, 2, '2013-11-05 14:22:42', '2013-10-21 20:08:05'),
(225, 'p.annamaria1@alice.it', 'a70f9e38ff015afaa9ab0aacabee2e13', 'Anna Maria', 'Pinna', 3, 2, '2013-10-26 18:04:36', '2013-10-26 18:03:22'),
(226, 'puddurita@alice.it', '2794d223f90059c9f705c73a99384085', 'Rita', 'Puddu', 23, 2, '2013-11-05 18:02:30', '2013-11-05 18:02:01'),
(227, 'monicapuxeddu@gmail.com', 'ff0d813dd5d2f64dd372c6c4b6aed086', 'Monica', 'Puxeddu', 5, 2, '2013-10-27 07:47:33', '2013-10-27 07:46:29'),
(228, 'giglio.salis@gmail.com', '693543918e4215cc40b01b1440a8eb13', 'Gigliola', 'Salis', 3, 2, '2013-10-14 16:33:54', '2013-10-14 16:07:52'),
(229, 'carla.sanna@istruzione.it', '92a0cdf4ed459f089969b496b32cf002', 'Carla', 'Sanna', 3, 2, '2013-10-20 19:13:09', '2013-10-07 22:15:29'),
(230, 'gabriella.soru@istruzione.it', '45650b6f60fafe3b2544852ecc5848d0', 'Gabriella', 'Soru', 4, 2, '2013-10-22 17:18:37', '2013-10-09 18:39:35'),
(231, 'angela.tanda', '36388794be2cf5f298978498ff3c64a2', 'Angela Natalina', 'Tanda', 0, 2, NULL, NULL),
(232, 'michelina.zucca@tiscali.it', 'a73353eae57e41a5b90cffb888c2cc02', 'Michelina', 'Zucca', 7, 2, '2013-10-24 15:21:26', '2013-10-24 15:21:26'),
(233, 'verosbra77@libero.it', 'faa82036538b8f367fcf7bfd4c63b789', 'Veronica', 'Sbrandolino', 15, 2, '2013-11-05 17:16:04', '2013-11-05 17:15:49'),
(234, 'mariagiulia.rosina', '263bce650e68ab4e23f28263760b9fa5', 'Maria Giulia', 'Rosina', 0, 2, NULL, NULL),
(235, 'annabruna.pinna', 'a70f9e38ff015afaa9ab0aacabee2e13', 'Anna Bruna', 'Pinna', 0, 2, NULL, NULL),
(236, 'francesca.boi@tiscali.it', '3477402667742da39c8e93bf4f30b271', 'Francesca', 'Boi', 0, 2, NULL, NULL),
(237, 'mariafrancesca.broi@virgilio.it', '263bce650e68ab4e23f28263760b9fa5', 'Maria Francesca', 'Broi', 11, 2, '2013-10-24 17:47:06', '2013-10-24 17:47:06'),
(238, 'eppilia@alice.it', 'c2d5001a02e0e6cab22c4273c8bfaca5', 'Annarella', 'Pili', 0, 2, NULL, NULL),
(239, 'noracaboni@gmail.com', '263bce650e68ab4e23f28263760b9fa5', 'Maria Eleonora', 'Caboni', 0, 2, NULL, NULL),
(240, 'francesca.chessa64@tiscali.it', '3477402667742da39c8e93bf4f30b271', 'Francesca', 'Chessa', 0, 2, NULL, NULL),
(241, 'gloriabiggio@gmail.com', '8d281a60d6d637903d4eccd26ddb0104', 'Gloria', 'Biggio', 1, 2, '2013-11-05 10:18:06', NULL),
(242, 'danaru@tiscali.it', '07a88e756847244f3496f63f473d6085', 'Daniela', 'Aru', 0, 2, NULL, NULL),
(243, 'ulloc@hotmail.it', '674cba521a0445ef3168b298509bf88e', 'Renzo', 'Collu', 14, 2, '2013-11-04 19:07:46', '2013-11-02 11:02:03'),
(244, 'barbara.pani', '4d6c4d6b5b6c7fd2c43727ce32a56f4e', 'Barbara', 'Pani', 0, 2, NULL, NULL),
(245, 'cristinagaria@libero.it', '0c74ac34d6652b2da30488d4f38496d8', 'Cristina', 'Garia', 4, 2, '2013-11-02 11:25:32', '2013-10-20 19:29:55'),
(246, 'anna.rombi', 'a70f9e38ff015afaa9ab0aacabee2e13', 'Anna', 'Rombi', 0, 2, NULL, NULL),
(247, 'sebastiana.zanda', 'c0a46d3c9266a90c5265d4b2bde39a78', 'Sebastiana', 'Zanda', 1, 2, '2013-11-05 17:19:33', NULL),
(248, 'morena.pinna', '0aa12ef0727babfb22c48d1561d7efb9', 'Morena', 'Pinna', 6, 8, NULL, NULL),
(249, 'gabriella.piddiu', '0b51834b1f8e5847f4383caaac889068', 'Gabriella', 'Piddiu', 3, 8, NULL, NULL),
(250, 'stefano.peddis', '8ddfc84ccfdcbbcf2ec4071e0808c7dd', 'Stefano', 'Peddis', 6, 8, NULL, NULL),
(251, 'panigiovanna@virgilio.it', '0e2c1b2e409178a1cf1128087006b668', 'Giovanna', 'Pani', 0, 8, NULL, NULL),
(252, 'moigraziella@hotmail.it', '341b2ef72c3a7a71c438a8305fcfd244', 'Graziella', 'Moi', 0, 8, NULL, NULL),
(253, 'marco.atzeri@carabinieri.it', '138da5be9a42d9c60861c5f3b7bda3ad', 'Marco', 'Atzeri', 0, 8, NULL, NULL),
(254, 'martasumerti@hotmail.it', '6a0cf1352ab39ead56fcc97bfd48e58e', 'Angela', 'Salvatore', 0, 8, NULL, NULL),
(255, 'matzuzziandrea@yahoo.it', '500716cc4c22a85cfa579bbcac43b049', 'Daniela', 'Quesada', 2, 8, NULL, NULL),
(256, 'salidunadia67@tiscali.it', '0bed90b6f66bda5208cf18907d5a263b', 'Liliana', 'Salidu', 0, 8, NULL, NULL),
(257, 'eloisafanni@alice.it', '99c93f0cb386b32d94cfd2885da1abe4', 'Eloisa', 'Fanni', 42, 8, NULL, NULL),
(258, 'angelosireus@hotmail.it', '8a0d324de6b9851b8cf4ed864dc53b59', 'Angelo', 'Sireus', 0, 8, NULL, NULL),
(259, 'rosymarras69@gmail.com', 'baf97cb015e0b27f7ab8feafc2c69355', 'Rosangela', 'Marras', 0, 8, NULL, NULL),
(260, 'ilariap.1974@live.it', 'd549ae83c830a3708d79df1586b57a34', 'Ilaria', 'Pili', 13, 8, NULL, NULL),
(261, 'sa.peddis@tiscali.it', 'bfd8b48019b8eb159b55805ce499148c', 'Salvatore', 'Peddis', 1, 8, NULL, NULL),
(262, 'novyabis@hotmail.it', '65c296d014ba823d469600fe9ddceb4e', 'Maria Novella', 'Abis', 0, 8, NULL, NULL),
(263, 'sabrinafanutza@hotmail.it', '2dfbb69e7d4d1d91eb7cfc29f9e67d0e', 'Sabrina', 'Fanutza', 1, 8, NULL, NULL),
(264, 'fileccia.mario@alice.it', 'b7bcb82c51a3c87f34918986a41c04da', 'Federica', 'Lugas', 0, 8, NULL, NULL),
(265, 'dadi69@tiscali.it', '4edf7f32f412c99b15f3a782c6ed02e7', 'Daniela', 'Cherchi', 0, 8, NULL, NULL),
(266, 'cardanca@gmail.com', '6e35851b97e4118843edecbd303f1d1f', 'Alessandra Federica', 'Meloni', 12, 8, NULL, NULL),
(267, 'giovanna_mannu@libero.it', '0b342172281e41d16be71cb3a3e5b844', 'Giovanna', 'Mannu', 0, 8, NULL, NULL),
(268, 'lupe.n@tiscali.it', '6843853fca7254611e8232fa18323420', 'Anna Rita', 'Puddu', 10, 8, NULL, NULL),
(269, 'mattypao@hotmail.it', '22218ae1b450edaec513ab6ba481a839', 'Maria Paola', 'Castangia', 0, 8, NULL, NULL),
(270, 'roberta.milia', '85744b583a5c5dd56631dabbd95e2021', 'Roberta', 'Milia', 2, 8, NULL, NULL),
(271, 'granell.mariacristina@tiscali.it', 'aaf5547fe9668e9af7a2f8c89ae62731', 'Maria Cristina', 'Granella', 0, 8, NULL, NULL),
(272, 'annagraziamarras@tiscali.it', 'e82100730c3ab78268976344b2cd7e77', 'Anna Grazia', 'Marras', 0, 8, NULL, NULL),
(273, 'carlomeloni68@gmail.com', '72b302bf297a228a75730123efef7c41', 'Carlo', 'Meloni', 9, 8, NULL, NULL),
(275, 'boyz11@hotmail.it', '02c943e2d7ce97be1c0c762d60555719', 'Graziano', 'Boi', 0, 8, NULL, NULL),
(276, 'ecca.anna@gmail.com', '47ab474195a2f9a3d07ba7562d89f988', 'Anna', 'Ecca', 1, 8, NULL, NULL),
(277, 'antonellapusceddu737@tiscali.it', '0d6ac6491362ce404e85610d366194c2', 'Antonella', 'Pusceddu', 1, 8, NULL, NULL),
(278, 'robifara@gmail.com', '7813a406efbbe0fbe8a83d28042bd6de', 'Roberto', 'Fara', 0, 8, NULL, NULL),
(279, 'ralibg@yahoo.it', 'c812ae088911affc82aec603c4bddd95', 'Goceva', 'Ralica', 0, 8, NULL, NULL),
(280, 'zarasergio59@tiscali.it', '815956bc2da035daf02608443ed7d615', 'Rita', 'Nannizzi', 0, 8, NULL, NULL),
(281, 'lallo.70@hotmail.it', 'ab3f385f50a9bef22beca9a47643af16', 'Antonio', 'Fenu', 0, 8, NULL, NULL),
(282, 'ary.puddu@gmail.com', '3da41ec023be9db9fe26e4c92d1d6261', 'Arianna', 'Puddu', 0, 8, NULL, NULL),
(283, 'gabriellaf@alice.it', '1a1be32dc9338c7d5a97f119a819b17e', 'Enrico', 'Cortese', 4, 8, NULL, NULL),
(284, 'vivianavinci@hotmail.it', '318aa0ce8a749074d64d0f2f09b84a44', 'Viviana', 'Vinci', 33, 8, NULL, NULL),
(285, 'pemp@tiscali.it', '2be3692c1f9ce88893c9fe89676a7e07', 'Maurizio', 'Congia', 17, 8, NULL, NULL),
(286, 'gianfranco.lugas', '001b428c7ccacaae44a064c095e4ad9f', 'Gianfranco', 'Lugas', 0, 8, NULL, NULL),
(287, 'sabrirma@gmail.com', '46e31409cae2f77942b2d9e924945047', 'Sabrina', 'Piras', 0, 8, NULL, NULL),
(288, 'portanuovaipergross@tiscali.it', 'f23f61d624a13420d1cb1585eb428b52', 'Francesco', 'Di Paola', 0, 8, NULL, NULL),
(289, 'angelo.cocco.nrwm@alice.it', '03f31152da16ec3426b0d3aef37ce530', 'Angelo', 'Cocco', 7, 8, NULL, NULL),
(290, 'serafino.gulleri@libero.it', '9f8b62c4ce9f5595e14b5a4161d9beaf', 'Serafino', 'Gulleri', 0, 8, NULL, NULL),
(291, 'pierangelo.frold7qtc@alice.it', 'aba15877beffe6337d1dfd032b74cc44', 'Anna Maria', 'Camboni', 0, 8, NULL, NULL),
(292, 'luchino0213@gmail.com', 'c0a6b6c1f3951f6416244d7ffc533196', 'Sabrina', 'Pilia', 11, 8, NULL, NULL),
(293, 'stefymany@tiscali.it', 'c9ac8d56841b32c670ef7ab552132fdb', 'Stefania', 'Lochi', 2, 8, NULL, NULL),
(294, 'barbara.barranca', '4d6c4d6b5b6c7fd2c43727ce32a56f4e', 'Barbara', 'Barranca', 4, 2, '2013-11-06 12:47:08', '2013-11-05 21:46:10'),
(295, 'barbara.mura', '263bce650e68ab4e23f28263760b9fa5', 'Barbara', 'Mura', 6, 2, '2013-11-05 22:21:21', '2013-11-05 22:19:10'),
(296, 'francesca.diana', '3477402667742da39c8e93bf4f30b271', 'Francesca', 'Diana', 0, 2, NULL, NULL),
(297, 'daniela.biggio', '07a88e756847244f3496f63f473d6085', 'Daniela', 'Biggio', 1, 2, '2013-11-05 17:18:41', NULL),
(298, 'rossana.mazzella', 'd70fc2235d4c24e1ff36823e7fa7a916', 'Rossana', 'Mazzella', 1, 2, '2013-11-06 19:18:24', NULL),
(299, 'sandra.vacca', 'f40a37048732da05928c3d374549c832', 'Sandra', 'Vacca', 0, 2, NULL, NULL),
(300, 'danyela_ala@yahoo.it', '20bf60069c2f45512472f45c43d03860', 'Daniela', 'Ala', 0, 8, NULL, NULL),
(301, 'rfa1999@gmail.it', 'e39769917f0d3343108cdbe0ebd5cfe6', 'Roberto', 'Ala', 0, 8, NULL, NULL),
(302, 'andreamurru2245@gmail.com', 'ea7c3b587227bc408b2215877220c8d1', 'Andrea', 'Murru', 0, 8, NULL, NULL),
(303, 'desymurgia@tiscali.it', '15b856149f22b51b329928fc46633e68', 'Desolina', 'Murgia', 0, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `rb_valutazione_parametri_pagella`
--

CREATE TABLE IF NOT EXISTS `rb_valutazione_parametri_pagella` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studente` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `quadrimestre` int(11) NOT NULL,
  `parametro` int(11) NOT NULL,
  `giudizio` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `rb_vclassi_s1`
--
CREATE TABLE IF NOT EXISTS `rb_vclassi_s1` (
`id_classe` int(11)
,`anno_corso` int(11)
,`sezione` char(2)
,`anno_scolastico` int(11)
,`tempo_prolungato` tinyint(1)
,`sede` tinyint(4)
,`musicale` tinyint(4)
,`modulo_orario` tinyint(4)
,`ordine_di_scuola` int(11)
,`coordinatore` int(11)
,`segretario` int(11)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `rb_vclassi_s2`
--
CREATE TABLE IF NOT EXISTS `rb_vclassi_s2` (
`id_classe` int(11)
,`anno_corso` int(11)
,`sezione` char(2)
,`anno_scolastico` int(11)
,`tempo_prolungato` tinyint(1)
,`sede` tinyint(4)
,`musicale` tinyint(4)
,`modulo_orario` tinyint(4)
,`ordine_di_scuola` int(11)
,`coordinatore` int(11)
,`segretario` int(11)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `rb_vclassi_s3`
--
CREATE TABLE IF NOT EXISTS `rb_vclassi_s3` (
`id_classe` int(11)
,`anno_corso` int(11)
,`sezione` char(2)
,`anno_scolastico` int(11)
,`tempo_prolungato` tinyint(1)
,`sede` tinyint(4)
,`musicale` tinyint(4)
,`modulo_orario` tinyint(4)
,`ordine_di_scuola` int(11)
,`coordinatore` int(11)
,`segretario` int(11)
);
-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `rb_vclassi_s5`
--
CREATE TABLE IF NOT EXISTS `rb_vclassi_s5` (
`id_classe` int(11)
,`anno_corso` int(11)
,`sezione` char(2)
,`anno_scolastico` int(11)
,`tempo_prolungato` tinyint(1)
,`sede` tinyint(4)
,`musicale` tinyint(4)
,`modulo_orario` tinyint(4)
,`ordine_di_scuola` int(11)
,`coordinatore` int(11)
,`segretario` int(11)
);
-- --------------------------------------------------------

--
-- Struttura della tabella `rb_verifiche`
--

CREATE TABLE IF NOT EXISTS `rb_verifiche` (
  `id_verifica` int(11) NOT NULL AUTO_INCREMENT,
  `id_docente` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_anno` int(11) NOT NULL,
  `data_verifica` timestamp NULL DEFAULT NULL,
  `data_assegnazione` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_materia` smallint(6) NOT NULL,
  `valutata` tinyint(1) NOT NULL DEFAULT '0',
  `id_attivita` int(11) DEFAULT NULL,
  `prova` text NOT NULL,
  `argomento` text NOT NULL,
  `note` text,
  `tipologia` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_verifica`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=345 ;

--
-- Dump dei dati per la tabella `rb_verifiche`
--

INSERT INTO `rb_verifiche` (`id_verifica`, `id_docente`, `id_classe`, `id_anno`, `data_verifica`, `data_assegnazione`, `id_materia`, `valutata`, `id_attivita`, `prova`, `argomento`, `note`, `tipologia`) VALUES
(1, 2, 5, 1, '2012-10-10 10:07:00', '2012-12-03 11:07:16', 3, 0, 1, 'Verifica di grammatica', 'Avverbi e preposizioni', '', 1),
(2, 2, 5, 1, '2012-11-06 18:35:00', '2012-12-03 18:35:58', 3, 0, NULL, 'Verifica di grammatica', 'La frase', '', 1),
(3, 2, 5, 1, '2012-11-30 18:39:00', '2012-12-03 18:39:41', 3, 0, NULL, 'Verifica di grammatica', 'Soggetto e predicato', '', 1),
(4, 2, 5, 1, '2012-09-21 17:48:00', '2012-12-03 18:49:03', 14, 0, NULL, 'Verifica di storia', 'La fine del Medioevo', '', 1),
(5, 2, 5, 1, '2012-10-17 18:11:00', '2012-12-03 19:11:43', 14, 0, NULL, 'Verifica di storia', 'L''epoca delle grandi scoperte', '', 1),
(6, 2, 5, 1, '2012-11-05 19:14:00', '2012-12-03 19:14:40', 14, 0, NULL, 'Verifica di storia', 'Il Rinascimento', '', 1),
(7, 2, 5, 1, '2012-12-01 16:50:00', '2012-12-05 16:49:48', 15, 0, NULL, 'Verifica di geografia', 'La Spagna', '', 1),
(8, 2, 8, 1, '2012-10-06 11:16:00', '2012-12-13 12:17:30', 3, 0, NULL, 'Verifica di grammatica ', 'Preposizioni e avverbi ', '', 1),
(9, 2, 8, 1, '2012-10-31 12:27:00', '2012-12-13 12:29:01', 3, 0, NULL, 'Verifica di grammatica', 'Frase semplice e frase complessa', '', 1),
(10, 2, 8, 1, '2012-11-28 12:32:00', '2012-12-13 12:33:07', 3, 0, NULL, 'Verifica di grammatica', 'Soggetto e predicato ', '', 1),
(11, 2, 8, 1, '2012-10-16 11:36:00', '2012-12-13 12:37:17', 14, 0, NULL, 'Verifica di storia ', 'L''epoca delle grandi scoperte ', '', 1),
(12, 2, 8, 1, '2012-11-07 12:42:00', '2012-12-13 12:43:11', 14, 0, NULL, 'Verifica scritta', 'Rinascimento', '', 1),
(13, 2, 5, 1, '2012-11-23 15:36:00', '2012-12-17 15:36:48', 3, 0, NULL, 'Tema', 'Racconto giallo o d''avventura', '', 1),
(14, 2, 5, 1, '2012-12-15 15:36:00', '2012-12-17 15:37:20', 3, 0, NULL, 'Verifica di grammatica', 'Complemento oggetto e di termine. Attributo e apposizione', '', 1),
(15, 2, 8, 1, '2012-12-14 10:08:00', '2012-12-18 10:13:07', 3, 0, NULL, 'Verifica di grammatica', 'Complemento oggetto e di termine. Attributo e apposizione ', '', 1),
(16, 2, 8, 1, '2012-11-24 10:10:00', '2012-12-18 10:15:25', 3, 0, NULL, 'Tema', 'Racconto giallo e d''avventura ', '', 1),
(17, 2, 8, 1, '2012-12-21 11:53:00', '2012-12-22 11:53:23', 14, 0, NULL, 'Verifica mista', 'Riforma protestante e controriforma', '', 1),
(18, 2, 5, 1, '2012-12-21 11:54:00', '2012-12-22 11:54:05', 14, 0, NULL, 'Verifica mista', 'Riforma protestante e controriforma', '', 1),
(19, 9, 6, 1, '2012-12-01 11:30:00', '2013-01-08 10:11:10', 16, 0, NULL, 'Verifica scritta di geometria', 'Circonferenza e cerchio', '', 1),
(20, 9, 6, 1, '2012-10-04 09:21:00', '2013-01-08 10:23:14', 16, 0, NULL, 'Verifica scritta aritmetica', 'Espressioni n. decimali; percentuali; proporzionalitÃ ', '', 1),
(21, 9, 6, 1, '2012-10-20 09:27:00', '2013-01-08 10:27:54', 16, 0, NULL, 'Verifica scritta geometria', 'Circonferenza e cerchio', '', 1),
(22, 2, 5, 1, '2013-01-09 08:47:00', '2013-01-09 08:47:26', 15, 0, 11, 'Verifica scritta', 'La Francia', '', 1),
(176, 18, 5, 1, '2013-04-04 14:01:00', '2013-04-04 14:02:37', 5, 0, 145, 'Verifica scritta a tipologia mista', 'L''arte del Trecento: contesto, caratteristiche e analisi opere ( le croci dipinte,Giotto e i cicli di affreschi a Padova e Assisi).', '', 1),
(25, 9, 4, 1, '2012-11-17 10:18:00', '2013-01-11 10:19:24', 16, 0, NULL, 'Verifica scritta aritmetica', 'Numerazione decimale, romana, le 4 operazioni', '', 1),
(26, 9, 4, 1, '2012-10-19 08:55:00', '2013-01-15 09:56:11', 16, 0, NULL, 'Verifica scritta aitmetica', 'Il sistema di numerazione decimale', '', 1),
(27, 9, 5, 1, '2012-10-18 09:00:00', '2013-01-15 10:01:08', 16, 0, NULL, 'Verifica scritta geometria', 'i triangoli', '', 1),
(28, 18, 9, 1, '2012-11-17 18:47:00', '2013-01-22 18:48:03', 5, 0, NULL, 'verifica a tipologia mista', 'Rinascimento: contesto caratteristiche e analisi opere di L. Da Vinci e M. Buonarroti', '', 1),
(29, 18, 9, 1, '2012-12-22 18:49:00', '2013-01-22 18:51:19', 5, 0, NULL, 'verifica a tipologia mista', 'Arte del Seicento: contesto ,caratteristiche e analisi opere di G.L.Bernini e Caravaggio.', '', 1),
(30, 11, 7, 1, '2012-11-08 00:40:00', '2013-01-24 00:42:48', 3, 0, 20, 'italiano', 'conoscenze sul mito', '', 1),
(31, 11, 7, 1, '2012-11-08 00:51:00', '2013-01-24 00:52:57', 3, 0, NULL, 'italiano', 'comprensione scritta del mito', '', 1),
(32, 11, 7, 1, '2012-11-12 01:00:00', '2013-01-24 01:01:24', 3, 0, NULL, 'italiano', 'conoscenze sulla favola', 'Gulleri,Fenu,Cherchi e Montisci assenti nella data 12-11-12 hanno eseguito la verifica in data 3-12-12', 1),
(33, 11, 7, 1, '2012-11-12 01:12:00', '2013-01-24 01:14:44', 3, 0, NULL, 'italiano', 'comprensione scritta della favola', 'Cherchi,Gulleri, Fenu,Montisci hanno eseguito la verifica in data\n 3-12-12 in quanto assenti il \n12-11-12. Il voto di Medda Ã¨ relativo all''orale e non allo scritto.', 1),
(34, 18, 6, 1, '2012-11-19 04:13:00', '2013-01-25 04:15:21', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte del Seicento: contesto,caratteristiche e analisi opere di Bernini e Caravaggio', '', 1),
(35, 18, 6, 1, '2012-12-17 04:15:00', '2013-01-25 04:17:05', 5, 0, NULL, 'verifica scritta a tipologia mista', 'Il Neoclassicismo: contesto, caratteristiche e analisi opere di A.Canova e J.L.David.', '', 1),
(36, 18, 3, 1, '2012-11-08 04:54:00', '2013-01-25 04:56:09', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte del Seicento :contesto, caratteristiche e analisi opere di Bernini e Caravaggio.', '', 1),
(37, 18, 3, 1, '2012-12-06 04:56:00', '2013-01-25 04:57:30', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'Il Neoclassicismo: contesto, caratteristiche e analisi opera di A.Canova e J.L.David', '', 1),
(76, 11, 7, 1, '2013-01-18 05:30:00', '2013-01-30 05:31:31', 14, 0, NULL, 'storia', 'Carlo Magno e la rinascita dell''Europa.', 'Gulleri poichÃ¨ esce anticipatamente e esegue la verifica in altra data', 1),
(39, 11, 7, 1, '2013-01-19 05:28:00', '2013-01-25 05:29:38', 3, 0, NULL, 'Grammatica', 'Il nome.', '', 1),
(40, 11, 7, 1, '2013-01-19 05:35:00', '2013-01-25 05:35:51', 3, 0, NULL, 'grammatica', 'Il nome', '', 1),
(80, 18, 3, 1, '2013-01-31 17:33:00', '2013-01-31 17:34:58', 5, 0, 42, 'Verifica scritta a tipologia mista', 'L''arte dell''Ottocento: il Romanticismo e il Realismo( contesto , caratteristiche e analisi opere di T.Gericault e di E.Delacroix).', '', 1),
(83, 11, 7, 1, '2012-10-06 18:04:00', '2013-01-31 19:05:03', 3, 0, NULL, 'grammatica.', 'L''articolo', 'Bertolini Luigi 7', 1),
(84, 11, 3, 1, '2012-11-29 19:39:00', '2013-01-31 19:41:32', 14, 0, NULL, 'storia', 'la situazione storico politica economica tra fine ''800 e primi del ''900.', 'verifica di recupero', 1),
(77, 11, 7, 1, '2012-10-12 04:43:00', '2013-01-30 05:45:58', 14, 0, NULL, 'storia', 'Le fonti della storia. Cronologia.Asse temporale, utilizzo  della numerazione romana nell''indicazione dei secoli.', 'Collu Andrea esce anticipatamente a causa di malessere e non conclude la verifica.\nBertolini 3', 1),
(44, 11, 7, 1, '2012-11-10 06:38:00', '2013-01-25 06:39:01', 3, 0, NULL, 'italiano', 'Il nome.', '', 1),
(45, 11, 7, 1, '2012-10-08 05:44:00', '2013-01-25 06:44:53', 3, 0, NULL, 'italiano', 'Elaborazione di testo.', '', 1),
(46, 18, 9, 1, '2012-12-01 11:34:00', '2013-01-25 11:37:18', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'Rinascimento: contesto,caratteristiche e analisi opere di L.Da Vinci e M.Buonarroti.', 'Recupero verifica :causa assenza.', 1),
(47, 18, 8, 1, '2012-11-12 16:33:00', '2013-01-25 16:35:21', 5, 0, NULL, 'Verifca scritta a tipologia mista', 'L''arte etrusca: contesto, caratteristiche e analisi opere (il tempio, le tombe, i sarcofagi).', '', 1),
(48, 18, 8, 1, '2012-11-19 16:37:00', '2013-01-25 16:40:23', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte etrusca:contesto, caratteristiche e analisi opere(il tempio, le tombe, i sarcofagi ).', 'Recupero (causa: impreparato)', 1),
(49, 18, 8, 1, '2012-12-17 16:40:00', '2013-01-25 16:42:45', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romana: contesto, caratteristiche e analisi opere( l''architettura pubblica e le tecniche costruttive).', '', 1),
(51, 18, 8, 1, '2013-01-07 16:44:00', '2013-01-25 16:45:05', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romana: contesto, caratteristiche e analisi opere( l''architettura pubblica e le tecniche costruttive).', 'Recupero ( causa : assenza )', 1),
(52, 18, 5, 1, '2012-11-15 18:18:00', '2013-01-25 18:19:43', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'l''arte romana: contesto , caratteristiche e analisi opere( l''architettura pubblica).', '', 1),
(53, 18, 5, 1, '2012-11-22 18:20:00', '2013-01-25 18:22:20', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romana: contesto, caratteristiche e analisi opere (l''architettura pubblica).', 'Recupero impreparati e assenti.', 1),
(54, 18, 5, 1, '2012-12-20 18:22:00', '2013-01-25 18:24:38', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte paleocristiana e bizantina: contesto, caratteristiche e analisi opere ( architettura e decorazione musiva ).', '', 1),
(55, 18, 2, 1, '2012-11-17 18:32:00', '2013-01-25 18:33:57', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romana: contesto, caratteristiche e analisi opere( l''architettura pubblica )', '', 1),
(56, 18, 2, 1, '2012-12-22 18:34:00', '2013-01-25 18:35:59', 5, 0, NULL, 'Verifica scrita a tipologia mista', 'L''arte paleocristiana e bizantina : contesto, caratteristiche e analisi opere ( architettura e decorazione musiva).', '', 1),
(57, 18, 2, 1, '2013-01-12 18:36:00', '2013-01-25 18:37:50', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte paleocristiana e bizantina: contesto, caratteristiche e analisi opere( architettura e decorazione musuva ).', 'Recupero impreparati e assenti.', 1),
(58, 18, 7, 1, '2012-11-13 18:45:00', '2013-01-25 18:47:03', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte nella Preistoria : contesto, caratteristiche e analisi opere (pitture rupestri, veneri, megaliti).', '', 1),
(59, 18, 7, 1, '2012-12-18 18:47:00', '2013-01-25 18:49:03', 5, 0, NULL, 'Verifica scritta a tipologia mista ', 'L''arte egizia : contesto, caratteristiche e analisi opere (necropoli di El-Giza, tempio di Karnak,pitture tombali).', '', 1),
(60, 18, 7, 1, '2012-11-20 18:51:00', '2013-01-25 18:53:54', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte nella Preistoria : contesto, caratteristiche e analisi opere( pitture rupestri, veneri, megaliti ).', 'Recupero per assenti e impreparati.', 1),
(61, 18, 7, 1, '2013-01-08 18:58:00', '2013-01-25 19:01:03', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte egizia : contesto, caratteristiche e analisi opere( necropoli diEl-Giza, tempio di Karnak,pitture tombali).', 'Recupero assenti e impreparati .', 1),
(62, 18, 1, 1, '2012-11-30 16:34:00', '2013-01-29 16:36:21', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte della Preistoria: contesto, caratteristiche e analisi opere (le pitture rupestri, le veneri, i megaliti ).', '', 1),
(63, 18, 1, 1, '2013-01-18 16:36:00', '2013-01-29 16:38:49', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte egizia: contesto , caratteristiche e analisi opere( la necropoli di El-Giza, il tempio di Karnak, il codice di raffigurazione)', '', 1),
(112, 18, 1, 1, '2013-01-25 18:26:00', '2013-02-03 18:27:23', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte egizia: contesto, caratteristiche e analisi opere.', 'Verifica di recupero insufficienze.', 1),
(113, 9, 6, 1, '2012-11-22 18:46:00', '2013-02-03 18:47:10', 17, 0, NULL, 'Interrogazione', 'L''occhio, l''orecchio', '', 1),
(114, 2, 5, 1, '2013-02-01 11:16:00', '2013-02-04 11:16:27', 14, 0, NULL, 'Verifica scritta', 'Il ''600', '', 1),
(115, 18, 5, 1, '2013-02-07 17:57:00', '2013-02-09 17:58:56', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romanica: contesto, caratteristiche e analisi opere ( architettura e scultura).', '', 1),
(66, 18, 4, 1, '2012-12-18 16:51:00', '2013-01-29 16:52:40', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte egizia: contesto , caratteristiche e analisi opere( la necropoli di El-Giza, il tempio di Karnak, il codice di raffigurazione)', '', 1),
(67, 18, 4, 1, '2013-01-15 16:52:00', '2013-01-29 16:54:07', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte egizia: contesto , caratteristiche e analisi opere( la necropoli di El-Giza, il tempio di Karnak, il codice di raffigurazione)', 'Recupero insufficienze verifiche precedenti', 1),
(68, 18, 4, 1, '2012-11-20 16:54:00', '2013-01-29 16:55:36', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte della Preistoria: contesto ,c aratteristiche e analisi opere( pitture rupestri, veneri, megaliti).', '', 1),
(69, 11, 3, 1, '2013-01-19 04:25:00', '2013-01-30 04:27:23', 3, 0, NULL, 'Italiano', 'Sintassi', 'Verifica di recupero', 1),
(70, 11, 3, 1, '2013-01-24 04:33:00', '2013-01-30 04:34:54', 3, 0, NULL, 'italiano', 'Sintassi: prop. sub. ogg., sogg., dichiarative,interrogative indirette.', '', 1),
(71, 11, 3, 1, '2012-12-15 04:39:00', '2013-01-30 04:40:18', 3, 0, NULL, 'italiano', 'sintassi: ', '', 1),
(72, 11, 7, 1, '2012-12-19 04:47:00', '2013-01-30 04:48:25', 3, 0, NULL, 'italiano', 'elaborazione testo.', '', 1),
(75, 11, 7, 1, '2012-11-21 05:16:00', '2013-01-30 05:18:39', 15, 0, NULL, 'geografia', 'linguaggio, leggere e classificare immagini,agenti esogeni ed endogeni, puti cardinali e coordinate geografiche', 'bertolini :4', 1),
(78, 11, 7, 1, '2012-11-05 05:51:00', '2013-01-30 05:53:03', 14, 0, NULL, 'storia', 'Le invasioni barbariche e il crollo dell''Impero romano.', 'Bertolini 3', 1),
(79, 11, 7, 1, '2012-11-26 05:57:00', '2013-01-30 05:58:23', 14, 0, NULL, 'storia', 'I regni romano barbarici e il ruolo della Chiesa.', '', 1),
(81, 9, 4, 1, '2013-01-26 08:30:00', '2013-01-31 18:04:12', 16, 0, NULL, 'Verifica aritmetica', 'Potenze, espressioni, problemi,divisori, multipli, scomposizioni', '', 1),
(82, 9, 5, 1, '2012-12-18 18:55:00', '2013-01-31 18:55:25', 16, 0, NULL, 'Verifica scritta di aritmetica', 'Frazioni e numeri decimali', '', 1),
(85, 11, 3, 1, '2012-11-19 19:46:00', '2013-01-31 19:47:51', 14, 0, NULL, 'storia', 'l''Italia all''inizio del ''900', '', 1),
(86, 11, 3, 1, '2012-10-31 20:20:00', '2013-01-31 20:21:20', 3, 0, NULL, 'elaborazione testo ', 'redigere la cronaca di fatto di cronaca.', '', 1),
(87, 11, 3, 1, '2012-10-01 19:25:00', '2013-01-31 20:26:48', 3, 0, NULL, 'elaborazione testi', 'stesura di una lettera', '', 1),
(88, 11, 3, 1, '2012-11-17 20:30:00', '2013-01-31 20:31:50', 3, 0, NULL, 'elaborazione testo', 'saper redigere un testo espositivo su un argomento trattato in classe: la mafia.', '', 1),
(128, 18, 1, 1, '2013-02-22 16:06:00', '2013-02-21 16:07:12', 5, 0, 68, 'Verifica scritta a tipologia mista', 'L''arte cretese e micenea: contesto, caratteristiche e opere.', '', 1),
(130, 18, 5, 1, '2013-02-21 16:43:00', '2013-02-25 16:43:48', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romanica ', 'recupero assenti e impreparati.', 1),
(131, 18, 9, 1, '2013-02-23 18:21:00', '2013-02-26 18:22:14', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte neoclassica.', 'Recupero verifiche per gli assenti.', 1),
(93, 11, 3, 1, '2012-10-05 05:08:00', '2013-02-01 06:10:13', 3, 0, NULL, 'sintassi', 'proposizione principale, coordinate e subordinate\n', '', 1),
(94, 11, 3, 1, '2012-12-05 06:13:00', '2013-02-01 06:14:10', 3, 0, NULL, 'sintassi', 'riconoscere principali e subordinate e saperle schematizzare', '', 1),
(100, 9, 5, 1, '2013-01-29 07:30:00', '2013-02-01 15:11:10', 16, 0, NULL, 'Verifica scritta aritmetica', 'Radici', '', 1),
(99, 9, 5, 1, '2013-01-31 11:30:00', '2013-02-01 15:04:57', 16, 0, NULL, 'Verifica scritta geometria', 'Calcolo aree rettangolo, parallelogrammo, quadrato.', '', 1),
(97, 11, 3, 1, '2012-11-07 06:19:00', '2013-02-01 06:21:05', 3, 0, NULL, 'sintassi', 'principali e subordinate: saperle riconoscere e schematizzare.', '', 1),
(101, 9, 6, 1, '2013-01-28 09:30:00', '2013-02-01 15:28:52', 16, 0, NULL, 'Verifica scritta geometria', 'Problemi sui prismi:aree,volume e peso.', '', 1),
(102, 9, 6, 1, '2013-01-31 07:30:00', '2013-02-01 15:34:49', 16, 0, NULL, 'Verifica scritta algebra', 'Espressioni complete numeri relativi', '', 1),
(103, 9, 5, 1, '2012-11-27 07:30:00', '2013-02-01 15:41:46', 16, 0, NULL, 'Verifica scritta geometria', 'I quadrilateri', '', 1),
(104, 9, 5, 1, '2012-11-29 10:30:00', '2013-02-01 15:47:11', 16, 0, NULL, 'Verifica scritta aritmetica', 'Frazioni e numeri decimali', '', 1),
(105, 9, 5, 1, '2012-12-11 07:30:00', '2013-02-01 15:53:28', 16, 0, NULL, 'Verifica scritta geometria', 'I poligoni', '', 1),
(106, 9, 6, 1, '2012-11-09 07:30:00', '2013-02-01 16:07:02', 16, 0, NULL, 'Verifica scritta ', 'Statistica e probabilitÃ ', '', 1),
(107, 9, 4, 1, '2012-10-02 07:13:00', '2013-02-01 16:13:57', 16, 0, NULL, 'Verifica scritta', 'Gli insiemi', '', 1),
(108, 11, 7, 1, '2013-01-17 19:41:00', '2013-02-01 19:43:47', 15, 0, NULL, 'geografia', 'comprendere il linguaggio della geografia', '', 1),
(109, 11, 3, 1, '2013-01-26 03:39:00', '2013-02-02 03:41:09', 14, 0, NULL, 'Conoscere i fatti storici', 'La prima guerra mondiale:cause, sviluppi, esiti.', '', 1),
(110, 2, 8, 1, '2013-01-30 18:24:00', '2013-02-02 18:24:18', 3, 0, NULL, 'Verifica di grammatica', 'Complementi di tempo e di luogo', '', 1),
(111, 11, 7, 1, '2013-01-14 04:41:00', '2013-02-03 04:42:14', 3, 0, NULL, 'la fiaba', 'verifica delle conoscenze', '', 1),
(116, 2, 8, 1, '2013-02-08 17:39:00', '2013-02-11 17:39:56', 14, 0, NULL, 'Verifica scritta', 'L''Europa del ''600', '', 1),
(129, 18, 2, 1, '2013-02-23 15:08:00', '2013-02-25 15:09:39', 5, 0, NULL, 'Verifica scritta a tipologia mista.', 'L''arte romanica: contesto, caratteristiche e analisi delle opere.', '', 1),
(118, 10, 1, 1, '2013-02-14 11:25:00', '2013-02-17 10:03:21', 11, 0, NULL, 'compito in classe', 'parlare di se stessi, descrivere la propria giornata tipo; gli alimenti', '', 1),
(119, 10, 2, 1, '2013-02-08 09:30:00', '2013-02-17 10:55:45', 11, 0, NULL, 'compito in classe', 'chiedere e indicare la direzione, i luoghi pubblici, gli avverbi di luogo e i verbi di movimento', '', 1),
(120, 10, 5, 1, '2013-02-08 09:30:00', '2013-02-17 11:17:08', 11, 0, NULL, 'interrogazione orale', 'l''uso dell''articolo partitivo e della forma negativa', '', 1),
(121, 10, 5, 1, '2013-02-15 09:35:00', '2013-02-17 11:24:10', 11, 0, NULL, 'compito in classe', 'l''uso del partitivo e della preposizione semplice "de"', '', 1),
(122, 10, 8, 1, '2013-02-04 07:30:00', '2013-02-17 12:02:49', 11, 0, NULL, 'verifica scritta ', 'Conoscenza del lessico deo luoghi pubblici', '', 1),
(123, 10, 8, 1, '2013-02-14 07:30:00', '2013-02-17 12:15:43', 11, 0, NULL, 'scritta', 'chiedere e indicare la direzione; i luoghi pubblici, gli avverbi di luogo e i verbi di movimento', '', 1),
(124, 10, 9, 1, '2013-02-16 10:31:00', '2013-02-17 12:32:43', 11, 0, NULL, 'scritta', 'questionario su Parigi e i suoi monumenti', '', 1),
(125, 18, 6, 1, '2013-02-18 14:41:00', '2013-02-18 14:42:43', 5, 0, 60, 'Verifica scritta a tipologia mista', 'L''arte dell''Ottocento: contesto, caratteristiche e analisi opere romantiche e realistiche.', '', 1),
(126, 18, 9, 1, '2013-02-16 14:43:00', '2013-02-18 14:44:14', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte neoclassica: contesto, caratteristiche e analisi opere di J.L.David e A.Canova.', '', 1),
(127, 18, 7, 1, '2013-02-19 16:16:00', '2013-02-19 16:16:52', 5, 0, 64, 'Verifica scritta a tipologia mista.', 'L''arte cretese e micenea a confronto : contesto, caratteristiche e analisi opere.', '', 1),
(132, 18, 7, 1, '2013-02-26 18:30:00', '2013-02-26 18:31:10', 5, 0, 84, 'Verifica scritta a tipolgia mista', 'L''arte cretese e micenea.', 'Recupero verifica per gli assenti.', 1),
(133, 18, 8, 1, '2013-02-25 19:13:00', '2013-03-02 19:15:03', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte paleocristiana e bizantina: contesto, caratteristiche e analisi opere.', '', 1),
(134, 18, 2, 1, '2013-03-02 19:19:00', '2013-03-02 19:20:15', 5, 0, 98, 'Verifica scritta a tipologia mista', 'L''arte romanica', 'recupero verifiche per gli assenti', 1),
(135, 2, 5, 1, '2013-02-27 09:03:00', '2013-03-04 09:03:16', 14, 0, NULL, 'Verifica scritta', 'La rivoluzione scientifica', '', 1),
(136, 2, 5, 1, '2013-02-25 09:25:00', '2013-03-04 09:25:43', 3, 0, NULL, 'Verifica di grammatica', 'Analisi logica', '', 1),
(137, 16, 9, 1, '2013-02-25 11:07:00', '2013-03-04 11:08:27', 10, 0, NULL, 'reading comprehension', 'Hawaii', '', 1),
(138, 16, 8, 1, '2013-02-22 11:40:00', '2013-03-04 11:41:07', 10, 0, NULL, 'verifica scritta', 'Unit Test 13', '', 1),
(139, 2, 8, 1, '2013-02-27 09:26:00', '2013-03-05 09:26:33', 3, 0, NULL, 'Verifica di grammatica', 'Analisi logica', '', 1),
(140, 2, 8, 1, '2013-03-01 09:31:00', '2013-03-05 09:32:00', 14, 0, NULL, 'Verifica scritta', 'La rivoluzione scientifica', '', 1),
(141, 18, 1, 1, '2013-03-01 18:52:00', '2013-03-10 18:53:13', 5, 0, NULL, 'Verifica scritta a tipologia mista:', 'L''arte cretese e micenea.', 'Recupero assenti e impreparati.', 1),
(142, 18, 8, 1, '2013-03-04 18:55:00', '2013-03-10 18:56:29', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte paleocristiana e bizantina', 'Recupero verifica per impreparati.', 1),
(143, 18, 3, 1, '2013-03-07 07:41:00', '2013-03-14 07:44:38', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte di fine Ottocento e la diffusione della fotografia:contesto, caratteristiche e analisi opere (Impressionismo e Postimpressionismo)', '', 1),
(144, 18, 3, 1, '2013-03-14 07:44:00', '2013-03-14 07:45:45', 5, 0, 106, 'Verifica scritta a tipologia mista', 'L''arte di fine Ottocento', 'Recupero verifiche per impreparati e assenti', 1),
(145, 18, 4, 1, '2013-03-05 07:47:00', '2013-03-14 07:48:52', 5, 0, NULL, 'Verifica a tipologia mista', 'L''arte cretese e micenea: contesto e analisi opere', '', 1),
(146, 16, 7, 1, '2013-03-01 12:05:00', '2013-03-19 12:08:08', 10, 0, NULL, 'verifica scritta', 'Unit Test 3', '', 1),
(147, 16, 8, 1, '2013-03-19 12:16:00', '2013-03-19 12:16:40', 10, 0, 118, 'verifica scritta', 'Unit Test 14', '', 1),
(148, 9, 6, 1, '2013-03-25 09:34:00', '2013-03-30 15:32:55', 17, 0, NULL, 'Verifica scritta', 'Biologia molecolare', '', 1),
(149, 9, 6, 1, '2013-02-28 07:36:00', '2013-03-30 15:37:34', 17, 0, NULL, 'Verifica scritta', 'La riproduzione', '', 1),
(150, 9, 6, 1, '2013-03-01 07:32:00', '2013-03-30 15:41:25', 16, 0, NULL, 'Verifica scritta', 'Somma di monomi, moltiplicazione di monomi.', '', 1),
(151, 9, 6, 1, '2013-03-04 09:31:00', '2013-03-30 15:45:31', 16, 0, NULL, 'Verifica scritta', 'Piramide; solidi sovrapposti', '', 1),
(152, 10, 1, 1, '2013-03-21 10:31:00', '2013-03-31 12:22:41', 11, 0, NULL, 'scritto', 'dialogo: fare acquisti per una festa di compleanno; acquistare il regalo di compleanno in un negozio di abbigliamento', '', 1),
(153, 10, 2, 1, '2013-02-25 09:30:00', '2013-03-31 12:56:13', 11, 0, NULL, 'scritto', 'descrivere la propria giornata al passato; il passÃ© composÃ©', '', 1),
(154, 10, 2, 1, '2013-03-15 08:30:00', '2013-03-31 14:54:10', 11, 0, NULL, 'scritta', 'dialogo: prenotare una camera d''albergo', '', 1),
(155, 10, 3, 1, '2013-02-22 16:19:00', '2013-03-31 15:21:29', 11, 0, NULL, 'scritta', 'questionario su "Laila", tratto da Poisson d''or', '', 1),
(156, 10, 3, 1, '2013-02-25 16:32:00', '2013-03-31 15:34:15', 11, 0, NULL, 'scritta', 'verifica di recupero per alcuni alunni', '', 1),
(157, 10, 3, 1, '2013-03-25 16:39:00', '2013-03-31 15:40:32', 11, 0, NULL, 'scritta', 'Prova d''esame: comprensione di un testo', '', 1),
(158, 10, 4, 1, '2013-02-21 17:11:00', '2013-03-31 16:14:54', 11, 0, NULL, 'scritta', 'descriversi e parlare della propria giornata', '', 1),
(159, 10, 4, 1, '2013-02-28 17:17:00', '2013-03-31 16:18:35', 11, 0, NULL, 'scritta', 'verifica di recupero per alcuni alunni', '', 1),
(160, 10, 4, 1, '2013-03-21 17:21:00', '2013-03-31 16:23:42', 11, 0, NULL, 'scritta', 'dialogo: fare la spesa; lessico', '', 1),
(161, 10, 5, 1, '2013-03-08 18:00:00', '2013-03-31 17:01:34', 11, 0, NULL, 'scritta', 'descrivere la propria giornata al passato', '', 1),
(162, 10, 5, 1, '2013-03-08 18:08:00', '2013-03-31 17:08:55', 11, 0, NULL, 'scritto', 'conoscere l''uso del passÃ© composÃ©', '', 1),
(163, 10, 5, 1, '2013-03-25 18:16:00', '2013-03-31 17:18:06', 11, 0, NULL, 'scritto', 'dialogo: prenotare una camera d''albergo; conoscere il lessico specifico', '', 1),
(164, 10, 6, 1, '2013-03-05 19:18:00', '2013-03-31 18:20:47', 11, 0, NULL, 'scritto', 'Questionario su Notre-Dame de Paris de victor Hugo', '', 1),
(165, 10, 6, 1, '2013-03-23 19:23:00', '2013-03-31 18:29:52', 11, 0, NULL, 'scritto', 'Questionario su Souad, BrulÃ©e vivante', '', 1),
(166, 10, 7, 1, '2013-03-07 19:44:00', '2013-03-31 18:44:55', 11, 0, NULL, 'scritto', 'Parlare di se stessi e descrivere la propria giornata; lessico: gli alimenti', '', 1),
(167, 10, 7, 1, '2013-03-21 19:49:00', '2013-03-31 18:50:37', 11, 0, NULL, 'scritto', 'Dialogo: fare acquisti per preparare una festa di compleanno e per comprare il regalo (in un negozio di vestiti)', '', 1),
(168, 10, 8, 1, '2013-02-28 20:13:00', '2013-03-31 19:14:38', 11, 0, NULL, 'scritto', 'parlare della propria giornata al passato', '', 1),
(169, 10, 8, 1, '2013-02-28 20:19:00', '2013-03-31 19:19:35', 11, 0, NULL, 'scritto', 'conoscere e saper usare il passÃ© composÃ©', '', 1),
(170, 10, 8, 1, '2013-03-21 20:22:00', '2013-03-31 19:23:02', 11, 0, NULL, 'scritto', 'dialogo: prenotare una camera d''albergo; conoscere il lessico specifico', '', 1),
(172, 9, 4, 1, '2013-03-25 07:31:00', '2013-04-02 16:59:43', 16, 0, NULL, 'Verifica scritta', 'Le frazioni e gli angoli', '', 1),
(173, 9, 4, 1, '2013-02-22 10:30:00', '2013-04-02 17:06:10', 16, 0, NULL, 'Verifica scritta', 'MCD  mcm segmenti', '', 1),
(177, 18, 1, 1, '2013-04-05 12:08:00', '2013-04-05 12:09:53', 5, 0, 146, 'Verifica scritta a tipologia mista.', 'L''architetture greca: contesto, caratteristiche e analisi opere (la polis, l''acropoli e il tempio).', '', 1),
(178, 10, 9, 1, '2013-04-06 10:44:00', '2013-04-07 10:45:31', 11, 0, NULL, 'scritta', 'Parlare di se stessi e raccontare una giornata al passato', '', 1),
(179, 18, 7, 1, '2013-04-09 11:42:00', '2013-04-09 11:43:15', 5, 0, 153, 'Verifica scritta a tipologia mista', 'L''architettura greca: la polis, l''acropoli, il tempio e gli ardini architettonici.', '', 1),
(180, 9, 5, 1, '2013-02-28 10:30:00', '2013-04-09 17:08:07', 16, 0, NULL, 'Verifica scritta geometria', 'Calcolo aree triangolo e rombo', '', 1),
(181, 2, 5, 1, '2013-04-08 17:25:00', '2013-04-11 17:25:59', 14, 0, NULL, 'Verifica scritta', 'La prima rivoluzione industriale', '', 1),
(182, 2, 5, 1, '2013-03-19 10:19:00', '2013-04-12 09:19:26', 3, 0, NULL, 'Tema', 'Il fantasy', '', 1),
(183, 18, 8, 1, '2013-04-08 09:30:00', '2013-04-14 09:30:59', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romanica: contesto,caratteristiche e analisi delle opere.', '', 1),
(184, 2, 5, 1, '2013-04-12 16:04:00', '2013-04-14 16:04:49', 3, 0, NULL, 'Verifica di grammatica', 'Analisi logica', '', 1),
(185, 10, 9, 1, '2013-04-09 18:33:00', '2013-04-14 18:34:08', 11, 0, NULL, 'scritta', 'Prova d''esame', '', 1),
(186, 2, 8, 1, '2013-04-06 07:01:00', '2013-04-16 07:01:58', 3, 0, NULL, 'Tema', 'Il racconto fantasy', '', 1),
(187, 2, 8, 1, '2013-03-06 08:02:00', '2013-04-16 07:02:42', 3, 0, NULL, 'Verifica di italiano ', 'Comprensione del testo', '', 1),
(188, 2, 8, 1, '2013-04-16 07:02:00', '2013-04-16 07:03:13', 3, 0, 156, 'Verifica di grammatica', 'Analisi logica', '', 1),
(189, 2, 8, 1, '2013-04-13 21:05:00', '2013-04-16 21:05:39', 14, 0, NULL, 'Verifica di storia', 'La prima rivoluzione industriale', '', 1),
(190, 18, 8, 1, '2013-04-15 16:17:00', '2013-04-20 16:18:41', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte romanica', 'Verifica recupero assenti e impreparati', 1),
(191, 18, 2, 1, '2013-04-20 16:45:00', '2013-04-20 16:47:15', 5, 0, 166, 'Verifica scritta a tipologia mista', 'L''arte del Trecento: le cattedrali, gli affreschi di Giotto e le tecniche decorative della vetrata e il procedimento dell''affresco. ', '', 1),
(192, 18, 4, 1, '2013-04-23 12:19:00', '2013-04-23 12:19:54', 5, 0, 167, 'Verifica scritta a tipologia mista.', 'L''architettura greca: contesto caratteristiche e analisi opere.', '', 1),
(193, 10, 8, 1, '2013-04-22 12:19:00', '2013-04-25 12:19:43', 11, 0, NULL, 'scritta', 'Verbi: i tempi dell''indicativo', '', 1),
(194, 10, 2, 1, '2013-04-19 12:23:00', '2013-04-25 12:23:58', 11, 0, NULL, 'scritta', 'Dialogo: fare shopping', '', 1),
(195, 10, 2, 1, '2013-04-19 12:27:00', '2013-04-25 12:28:30', 11, 0, NULL, 'scritta', 'Verbi: i tempi dell''indicativo (seconda parte)', '', 1),
(196, 10, 5, 1, '2013-04-19 12:32:00', '2013-04-25 12:34:20', 11, 0, NULL, 'scritta', 'dialogo: fare shopping', '', 1),
(197, 10, 5, 1, '2013-04-19 12:40:00', '2013-04-25 12:40:38', 11, 0, NULL, 'scritta', 'Verbi: i tempi dell''indicativo (prima parte)', '', 1),
(198, 10, 5, 1, '2013-04-22 12:46:00', '2013-04-25 12:46:53', 11, 0, NULL, 'scritta', 'Verbi: i tempi dell''indicativo (seconda parte)', '', 1),
(200, 9, 5, 1, '2013-04-23 06:30:00', '2013-04-26 14:44:38', 16, 0, NULL, 'Verifica scritta aritmetica', 'Proporzioni', '', 1),
(201, 9, 5, 1, '2013-04-23 07:30:00', '2013-04-26 14:48:49', 16, 0, NULL, 'Verifica scritta geometria', 'Problemi di applicazione al teorema di Pitagora', '', 1),
(202, 2, 5, 1, '2013-04-24 07:06:00', '2013-04-27 07:06:06', 14, 0, NULL, 'Verifica di storia', 'La rivoluzione americana', '', 1),
(203, 18, 2, 1, '2013-04-27 09:19:00', '2013-04-27 09:20:29', 5, 0, 168, 'Verifica scritta a tipologia mista', 'L''arte del Trecento', 'Verifica di recupero per assenti e impreparati.', 1),
(204, 10, 6, 1, '2013-04-27 16:31:00', '2013-05-01 16:32:20', 11, 0, NULL, 'scritta', 'Prova d''esame', '', 1),
(205, 10, 9, 1, '2013-04-27 16:50:00', '2013-05-01 16:50:21', 11, 0, NULL, 'scritta', 'Prova d''esame', '', 1),
(206, 10, 2, 1, '2013-04-15 17:14:00', '2013-05-01 17:14:36', 11, 0, NULL, 'scritta', 'Verbi: i tempi dell''indicativo (prima parte)', '', 1),
(207, 10, 8, 1, '2013-04-18 17:20:00', '2013-05-01 17:21:08', 11, 0, NULL, 'scritta', 'Verbi: i tempi dell''indicativo (prima parte)', '', 1),
(208, 9, 4, 1, '2013-04-27 07:32:00', '2013-05-10 19:03:57', 16, 0, NULL, 'Verifica scritta', 'Addiz. e sottraz. tra ferazioni; rette perp e parallele', '', 1),
(209, 9, 6, 1, '2013-05-03 06:30:00', '2013-05-10 19:11:13', 16, 0, NULL, 'Verifica scritta', 'Espressioni polinomi; cono', '', 1),
(210, 18, 8, 1, '2013-05-27 15:10:00', '2013-05-14 15:11:05', 5, 0, 191, 'Verifica scritta a risposta multipla.', 'L''arte del Trecento.', '', 1),
(230, 18, 4, 1, '2013-05-28 17:31:00', '2013-05-31 17:32:43', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'La ceramica greca: materiali, forme e funzioni.', '', 1),
(212, 18, 6, 1, '2013-05-13 15:12:00', '2013-05-14 15:13:11', 5, 0, NULL, 'Verifica scritta a risposta mista', 'L''arte di fine Ottocento', '', 1),
(213, 18, 9, 1, '2013-05-11 15:13:00', '2013-05-14 15:14:08', 5, 0, NULL, 'Verifica scritta a risposta  mista', 'L''arte dell''Ottocento', '', 1),
(214, 10, 1, 1, '2013-05-04 16:32:00', '2013-05-15 16:35:05', 11, 0, NULL, 'scritta', 'Chiedere e indicare la direzione; i luoghi pubblici, gli avverbi di luogo e le regole di "de" e "Ã " ', '', 1),
(215, 10, 4, 1, '2013-05-02 16:39:00', '2013-05-15 16:39:43', 11, 0, NULL, 'scritta', 'I luoghi pubblici, gli avverbi di luogo e la regola di "de"', '', 1),
(216, 10, 7, 1, '2013-05-02 16:43:00', '2013-05-15 16:44:43', 11, 0, NULL, 'scritta', 'Chiedere e indicare la direzione, conoscere i luoghi pubblici, gli avverbi di luogo e le regole di "de" e "Ã "', '', 1),
(217, 18, 5, 1, '2013-05-16 10:29:00', '2013-05-19 10:30:07', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'L''arte del Primo Rinascimento', '', 1),
(218, 10, 9, 1, '2013-05-18 11:47:00', '2013-05-19 11:47:50', 11, 0, NULL, 'orale', 'Souad, BrulÃ©e vivante', '', 1),
(219, 18, 7, 1, '2013-05-21 06:17:00', '2013-05-21 06:20:08', 5, 0, 202, 'Verifica scritta a tipologia mista', 'La scultura e la ceramica greca.', '', 1),
(220, 10, 1, 1, '2013-05-17 14:41:00', '2013-05-23 14:41:58', 11, 0, NULL, 'scritta', 'Verifica finale del corso di recupero (forma negativa e interrogativa, indicare l''ora, argomenti a piacere)', '', 1),
(221, 10, 4, 1, '2013-05-17 14:44:00', '2013-05-23 14:44:43', 11, 0, NULL, 'scritta', 'Verifica finale del corso di recupero: forma negativa e interrogativa, indicare l''ora, argomenti a piacere', '', 1),
(222, 10, 7, 1, '2013-05-17 14:45:00', '2013-05-23 14:46:58', 11, 0, NULL, 'scritta', 'Verifica finale del corso di recupero: forma negativa e interrogativa, indicare l''ora, argomenti a piacere', '', 1),
(223, 10, 1, 1, '2013-05-23 16:44:00', '2013-05-23 16:45:22', 11, 0, 205, 'lavoro di gruppo', 'Cartellone sul viaggio d''istruzione ad Alghero ', '', 1),
(224, 2, 8, 1, '2013-05-15 11:01:00', '2013-05-24 11:00:13', 14, 0, NULL, 'Verifica di storia', 'La rivoluzione francese', '', 1),
(225, 10, 3, 1, '2013-04-08 14:47:00', '2013-05-24 14:47:53', 11, 0, NULL, 'scritta', 'RÃ©vision des dialogues', '', 1),
(226, 18, 3, 1, '2013-05-23 14:30:00', '2013-05-25 14:32:22', 5, 0, NULL, 'Verifica scritta a tipologia mista', 'Il Futurismo: contesto, carateristiche e opere.', '', 1),
(227, 2, 5, 1, '2013-05-18 09:33:00', '2013-05-26 09:33:22', 15, 0, NULL, 'Brochure turistiche', 'Europa', '', 1),
(228, 18, 3, 1, '2013-05-30 14:36:00', '2013-05-30 14:37:07', 5, 0, 207, 'Verifica scritta a tipologia aperta', 'Il Futurismo', 'Recupero per assenti', 1),
(229, 18, 1, 1, '2013-05-24 16:34:00', '2013-05-30 16:36:10', 5, 0, NULL, 'Verifica scritta atipologia mista', 'La scultura e la ceramica greca.', '', 1),
(231, 10, 4, 1, '2013-05-30 10:07:00', '2013-06-02 10:08:19', 11, 0, NULL, 'scritta', 'Dialogo: chiedere e indicare la direzione', '', 1),
(232, 10, 4, 1, '2013-04-09 10:16:00', '2013-06-02 10:17:46', 11, 0, NULL, 'orale', 'Dialogo: fare shopping', '', 1),
(233, 10, 4, 1, '2013-04-11 10:20:00', '2013-06-02 10:21:05', 11, 0, NULL, 'orale', 'Fare shopping', '', 1),
(234, 10, 4, 1, '2013-04-18 10:22:00', '2013-06-02 10:23:08', 11, 0, NULL, 'orale', 'fare shopping', '', 1),
(235, 10, 4, 1, '2013-06-28 10:24:00', '2013-06-02 10:24:50', 11, 0, 208, 'orale', 'Chiedere e indicare la direzione, i luoghi pubblici e gli avverbi di luogo', '', 1),
(236, 10, 4, 1, '2013-05-28 10:25:00', '2013-06-02 10:26:07', 11, 0, NULL, 'orale', 'Chiedere e indicare la direzione, i luoghi pubblici e gli avverbi di luogo', '', 1),
(237, 10, 5, 1, '2013-05-03 12:26:00', '2013-06-02 12:27:33', 11, 0, NULL, 'scritta', 'recupero sui tempi dell''indicativo', '', 1),
(238, 10, 5, 1, '2013-05-31 12:36:00', '2013-06-02 12:37:08', 11, 0, NULL, 'scritta', 'i gallicismi', '', 1),
(263, 10, 9, 1, '2013-05-25 15:42:00', '2013-06-09 15:42:38', 11, 0, NULL, 'orale', 'Souad', '', 1),
(264, 10, 9, 1, '2013-05-28 11:20:00', '2013-06-10 11:20:49', 11, 0, NULL, 'scritta', 'Ripasso di tutti i dialoghi', '', 1),
(240, 10, 5, 1, '2013-05-27 12:52:00', '2013-06-02 12:53:29', 11, 0, NULL, 'orale', 'Verifica orale sulla cucina regionale (dal 10 maggio in poi)', '', 1),
(241, 10, 6, 1, '2013-05-04 13:49:00', '2013-06-02 13:54:44', 11, 0, NULL, 'scritta', 'Esposizione sul Racisme expliquÃ© Ã  ma fille, di Tahar Ben Jelloun', '', 1),
(242, 10, 1, 1, '2013-04-27 15:44:00', '2013-06-02 15:45:46', 11, 0, NULL, 'interrogazione orale', 'chiedere e indicare la direzione (da aprile a maggio)', '', 1),
(249, 18, 8, 1, '2013-06-03 13:40:00', '2013-06-03 13:41:31', 5, 0, 210, 'verifica a risposta aperta', 'La pittura del Trecento: Giotto.', 'Recupero', 1),
(248, 10, 7, 1, '2013-04-26 19:38:00', '2013-06-02 19:38:43', 11, 0, NULL, 'scritta', 'Chiedere e indicare la direzione', '', 1),
(247, 10, 2, 1, '2013-05-31 16:09:00', '2013-06-02 16:10:15', 11, 0, NULL, 'scritta', 'I gallicismi', '', 1),
(246, 10, 1, 1, '2013-05-30 15:55:00', '2013-06-02 15:56:16', 11, 0, NULL, 'interrogazione orale', 'il ristorante', '', 1),
(250, 10, 7, 1, '2013-05-16 14:50:00', '2013-06-03 14:51:23', 11, 0, NULL, 'interrogazione orale', 'Prenotare un tavolo, chiedere e indicare la direzione, fare shopping, presentarsi e descriversi', '', 1),
(251, 10, 3, 1, '2013-06-03 15:55:00', '2013-06-04 15:55:37', 11, 0, NULL, 'scritta', 'Prova d''esame (Cannes 2013)', '', 1),
(252, 10, 6, 1, '2013-06-04 16:04:00', '2013-06-04 16:04:44', 11, 0, 211, 'scritta', 'Prova d''esame (Cannes 2013)', '', 1),
(253, 10, 9, 1, '2013-06-04 16:12:00', '2013-06-04 16:12:40', 11, 0, 212, 'scritta', 'Prova d''esame (Cannes 2013)', '', 1),
(254, 9, 4, 1, '2013-06-01 07:32:00', '2013-06-04 17:41:40', 16, 0, NULL, 'Verifica scritta', 'Espressioni, trasformazioni, problemi.', '', 1),
(255, 10, 8, 1, '2013-06-03 05:44:00', '2013-06-05 05:45:04', 11, 0, NULL, 'scritta', 'i gallicismi', '', 1),
(256, 10, 8, 1, '2013-05-09 05:49:00', '2013-06-05 05:49:40', 11, 0, NULL, 'scritta', 'Dialogo: fare shopping', '', 1),
(257, 10, 8, 1, '2013-05-09 05:53:00', '2013-06-05 05:53:47', 11, 0, NULL, 'scritta', 'il futuro irregolare', '', 1),
(258, 10, 8, 1, '2013-04-15 06:05:00', '2013-06-05 06:06:16', 11, 0, NULL, 'orale', 'La cucina regionale (interrogazioni dal 15 aprile in poi)', '', 1),
(259, 10, 8, 1, '2013-05-13 06:09:00', '2013-06-05 06:10:29', 11, 0, NULL, 'orale', 'la cucina d''importazione (interrogazioni dal 13 maggio in poi)', '', 1),
(260, 10, 8, 1, '2013-05-27 07:09:00', '2013-06-05 07:10:18', 11, 0, NULL, 'scritta', 'Verifica di recupero per 3 alunni (dialogo: fare shopping)', '', 1),
(261, 10, 8, 1, '2013-02-21 08:30:00', '2013-06-05 07:31:05', 11, 0, NULL, 'scritta', 'verifica di recupero per alcuni alunni (chiedere e indicare la direzione)', '', 1),
(262, 10, 8, 1, '2013-04-08 07:40:00', '2013-06-05 07:40:55', 11, 0, NULL, 'scritta', 'Verifica di recupero (prenotare una camera)', '', 1),
(265, 10, 5, 2, '2013-09-25 14:06:00', '2013-09-25 14:07:20', 11, 0, 246, 'orale', 'Le festival de Cannes', 'verifica orale dal 25 settembre 2013 al', 1),
(267, 10, 8, 2, '2013-09-26 16:14:00', '2013-09-27 16:14:28', 11, 0, NULL, 'orale', 'Le festival de Cannes', 'VÃ©rification du 26 septembre au ', 1),
(268, 10, 2, 2, '2013-09-27 18:55:00', '2013-09-27 18:55:38', 11, 0, 260, 'orale', 'Le festival de Cannes', 'interrogazioni dal 27/09/2013 al 30/10/2013 (ultimo interrogato: Lugas) ', 1),
(269, 10, 7, 2, '2013-09-28 20:15:00', '2013-09-28 20:15:56', 11, 0, 267, 'VÃ©rification orale', 'Au restaurant', 'VÃ©rification du 28 septembre au', 1),
(270, 3, 1, 2, '2013-10-02 07:30:00', '2013-10-03 19:23:27', 16, 0, NULL, 'Verifica scritta', 'Problemi sui segmenti e sugli angoli. Espressioni frazionarie.', '', 1),
(271, 2, 5, 2, '2013-10-14 08:14:00', '2013-10-04 08:14:57', 3, 0, 283, 'Verifica di grammatica', 'Il periodo. Struttura gerarchica del periodo', '', 1),
(272, 3, 2, 2, '2013-10-01 09:30:00', '2013-10-04 10:35:45', 16, 0, 284, 'Verifica scritta', 'Applicazione Teorema di Pitagora; numeri decimali e proporzioni.', '', 1),
(273, 10, 1, 2, '2013-10-04 13:47:00', '2013-10-06 13:49:10', 11, 0, NULL, 'scritta', 'Dialogue: au restaurant', '', 1),
(279, 4, 11, 2, '2013-10-05 06:29:00', '2013-10-10 06:29:46', 10, 0, NULL, 'Test', 'Entry test', '', 1),
(276, 10, 4, 2, '2013-10-08 18:49:00', '2013-10-08 18:50:28', 11, 0, 302, 'orale', 'rÃ©server et commander au restaurant; dÃ©crire une carte', '', 1),
(277, 8, 7, 2, '2013-10-07 06:22:00', '2013-10-09 06:23:44', 16, 0, NULL, 'test', 'frazioni', '', 1),
(278, 8, 7, 2, '2013-10-03 14:43:00', '2013-10-09 14:46:09', 17, 0, NULL, 'scritta', 'test:monere,protisti e funghi', '', 1),
(280, 4, 11, 2, '2013-10-09 15:11:00', '2013-10-10 15:11:53', 10, 0, NULL, 'test', 'entry test', '', 1),
(281, 4, 12, 2, '2013-10-05 15:15:00', '2013-10-10 15:16:12', 10, 0, NULL, 'written test', 'entry test', '', 1),
(282, 4, 12, 2, '2013-10-09 15:19:00', '2013-10-10 15:19:33', 10, 0, NULL, 'written test', 'entry test', '', 1),
(297, 9, 12, 2, '2013-10-15 06:30:00', '2013-10-17 17:44:17', 16, 0, NULL, 'Verifica scitta aritmetica', 'Numerazione decimale', '', 1),
(284, 10, 11, 2, '2013-10-05 18:45:00', '2013-10-10 18:47:16', 11, 0, NULL, 'scritta', 'Se prÃ©senter; les chiffres de 1 Ã  12; les jours de la semaine; les salutations', '', 1),
(285, 10, 12, 2, '2013-10-08 14:20:00', '2013-10-11 14:21:12', 11, 0, NULL, 'scritta', 'se prÃ©senter; les chiffres de 1 Ã  11; les jours de la semaine; les salutations', '', 1),
(286, 199, 10, 2, '2013-09-20 18:15:00', '2013-10-11 18:20:35', 32, 0, NULL, 'VERIFICA SCRITTA COMPRENSIONE LETTURA', 'CLASSIFICARE VOCABOLI NOTI\nCOLLEGARE DOMANDE E RISPOSTE NOTE\n', '25 PAROLE DI 5   AMBITI LESSICALI\n10 DOMANDE E RISPOSTE', 1),
(296, 4, 5, 2, '2013-10-15 12:30:00', '2013-10-17 12:31:49', 10, 0, NULL, 'written test', 'unit 22', '', 1),
(304, 4, 2, 2, '2013-10-17 09:57:00', '2013-10-19 09:57:51', 10, 0, NULL, 'written test', 'unit 22', '', 1),
(289, 3, 11, 2, '2013-10-08 14:05:00', '2013-10-14 14:06:30', 16, 0, NULL, 'verifica scritta', 'Gli Insiemi', '', 1),
(325, 10, 11, 2, '2013-10-26 15:23:00', '2013-10-26 15:25:08', 11, 0, 414, 'orale', 'les chiffres; les mois, les jours de la semaine, les verbes etre et avoir; les dialogues Ã©tudiÃ©s.', '', 2),
(291, 10, 4, 2, '2013-10-10 12:33:00', '2013-10-15 12:33:50', 11, 0, NULL, 'scritta', 'Au restaurnt', '', 1),
(295, 10, 5, 2, '2013-10-16 20:22:00', '2013-10-16 20:23:19', 11, 0, 347, 'orale', 'Laila, tirÃ© de Poisson d''or di Le Cl?zio', 'interrogazioni dal 16/10/2013 al', 1),
(298, 9, 5, 2, '2013-10-07 08:35:00', '2013-10-18 06:30:00', 16, 0, NULL, 'Verifica scritta geometria', 'Teorema di Pitagora; le similitudini', '', 1),
(299, 9, 5, 2, '2013-10-08 08:35:00', '2013-10-18 06:38:05', 16, 0, NULL, 'Verifica scritta matematica', 'ProporzionalitÃ  diretta e inversa', '', 1),
(319, 10, 2, 2, '2013-10-23 12:52:00', '2013-10-23 12:52:32', 11, 0, 392, 'orale', 'la francophonie', '', 1),
(300, 8, 13, 2, '2013-10-18 08:30:00', '2013-10-18 16:22:20', 16, 0, 367, 'verifica scritta', 'gli insiemi', '', 1),
(302, 8, 8, 2, '2013-10-07 10:25:00', '2013-10-18 16:42:58', 16, 0, NULL, 'test', 'ripasso:problemi somma ,differenza e rapporto', '', 1),
(305, 10, 8, 2, '2013-10-19 15:34:00', '2013-10-19 15:35:34', 11, 0, 378, 'orale', '"Laila", tirÃ© de "Poisson d''or" de Le ClÃ©zio ', '', 1),
(306, 10, 8, 2, '2013-10-17 15:36:00', '2013-10-19 15:36:50', 11, 0, NULL, 'scritta', 'Questionnaire sur "Laila"', '', 1),
(308, 8, 8, 2, '2013-10-10 07:30:00', '2013-10-19 18:28:24', 16, 0, NULL, 'scritta', 'applicazione del teorema di pitagora', '', 1),
(311, 8, 7, 2, '2013-10-09 07:30:00', '2013-10-19 19:04:09', 16, 0, NULL, 'scritta', 'espressioni', '', 1),
(312, 10, 2, 2, '2013-10-18 10:53:00', '2013-10-20 10:53:42', 11, 0, NULL, 'scritta', 'Questionnaire sur la francophonie', '', 1),
(314, 8, 8, 2, '2013-10-12 10:30:00', '2013-10-20 15:33:49', 16, 0, NULL, 'scritta', 'poligoni con angoli particolari 30,45,60.', '', 1),
(315, 2, 8, 2, '2013-10-14 14:12:00', '2013-10-21 14:12:58', 3, 0, NULL, 'Verifica di grammatica', 'Il periodo. Struttura gerarchica e rapporti tra le proposizioni', 'Recupero di verifica del 7', 1),
(316, 2, 5, 2, '2013-10-19 14:22:00', '2013-10-21 14:22:59', 15, 0, NULL, 'Verifica scritta', 'La Terra.', '', 1),
(317, 3, 2, 2, '2013-10-15 09:31:00', '2013-10-22 13:23:52', 17, 0, NULL, 'Verifica scritta', 'Gli organi di senso; l''occhio e l''orecchio', '', 1),
(318, 10, 1, 2, '2013-10-22 13:42:00', '2013-10-22 13:43:27', 11, 0, 390, 'scritta', 'Usage de l''article partitif, de la prï¿½position de, de la forme nï¿½gative, du "que" restrictif', 'ContrÃ´le de rattrapage', 1),
(320, 221, 17, 2, '2013-10-22 07:00:00', '2013-10-23 16:17:59', 28, 0, NULL, 'completare parole con vocali mancanti', 'riconoscere, scrivere e leggere le vocali', '', 1),
(321, 221, 26, 2, '2013-10-22 09:00:00', '2013-10-24 15:30:49', 28, 0, NULL, 'completa parole con vocali mancanti', 'riconoscere, scrivere e leggere le vocali', '', 1),
(322, 2, 5, 2, '2013-10-23 08:29:00', '2013-10-25 08:29:47', 14, 0, NULL, 'Verifica', 'La societa` industriale', '', 1),
(323, 10, 12, 2, '2013-10-25 14:13:00', '2013-10-25 14:13:54', 11, 0, 408, 'oral', 'Dialogue: se prÃ©senter et prÃ©senter quelqu''un; Ã©peler son prÃ©nom', '', 2),
(324, 3, 11, 2, '2013-10-25 11:28:00', '2013-10-26 11:30:37', 16, 0, NULL, 'Matematica', 'Numerazione decimale e romana', '', 1),
(326, 16, 8, 2, '2013-10-25 11:02:00', '2013-10-28 12:04:16', 10, 0, NULL, 'Classwork', 'Unit 16', '', 1),
(327, 195, 7, 2, '2013-10-15 09:46:00', '2013-10-29 10:48:30', 7, 0, NULL, 'Verifica di Tecnologia', '"La carta"', '', 1),
(328, 195, 4, 2, '2013-10-18 09:58:00', '2013-10-29 10:59:15', 7, 0, NULL, 'Verifica di Tecnologia', '"La Carta"', '', 1),
(329, 10, 1, 2, '2013-10-29 15:07:00', '2013-10-29 15:08:21', 11, 0, 432, 'orale', 'Dialogue: rÃ©server une chambre Ã  l''hÃ´tel', '', 2),
(330, 10, 5, 2, '2013-10-25 14:11:00', '2013-10-29 15:11:53', 11, 0, NULL, 'scritta', 'Questionnaire sur Laila, tirÃ© de Poisson d''or de Le ClÃ©zio', '', 1),
(331, 10, 7, 2, '2013-10-26 14:16:00', '2013-10-29 15:19:18', 11, 0, NULL, 'scritta', 'l''article partitif; l''usage du "de"; la forme nï¿½gative; l''omission du "pas"; le "que" restrictif ', '', 1),
(332, 10, 7, 2, '2013-10-30 14:19:00', '2013-10-30 14:19:40', 11, 0, 436, 'orale', 'Dialogue: rÃ©server une chambre Ã  l''hÃ´tel; lexique', '', 2),
(333, 195, 8, 2, '2013-10-15 08:11:00', '2013-10-31 09:12:39', 7, 0, NULL, 'Verifica grafica', 'Costruzione di elementi geometrici di base', '', 1),
(334, 10, 13, 2, '2013-10-31 14:13:00', '2013-10-31 14:15:02', 11, 0, 445, 'orale', 'dialogue: se prÃ©senter; donner du vous; les chiffres de 1 Ã  79; les verbes etre et avoir; les jours de la semaine, les mois.', '', 2),
(335, 10, 7, 2, '2013-10-09 13:46:00', '2013-10-31 14:47:09', 11, 0, NULL, 'scritta', 'Dialogues: rÃ©server une table, se prÃ©senter au restaurant, commander et payer l''addition.', '', 1),
(336, 10, 4, 2, '2013-10-31 15:17:00', '2013-10-31 15:18:13', 11, 0, 449, 'scritta', 'l''article partitif et la prÃ©position "de"; la forme nÃ©gative et l''omission du "pas"; le "que" restrictif.', '', 1),
(337, 9, 4, 2, '2013-10-26 07:30:00', '2013-10-31 18:49:55', 16, 0, NULL, 'Geometria', 'Poligoni, triangoli; problemi', '', 1),
(338, 9, 12, 2, '2013-10-28 07:30:00', '2013-10-31 18:56:29', 16, 0, NULL, 'Aritmetica', 'Le quattro operazioni, espressione, problemi.', '', 1),
(339, 2, 8, 2, '2013-10-21 08:14:00', '2013-11-01 09:15:15', 14, 0, NULL, 'Verifica', 'La societa` industriale', '', 1),
(340, 195, 7, 2, '2013-10-24 09:13:00', '2013-11-01 10:15:26', 7, 0, NULL, 'Verifica grafica', 'Verifica grafica sulle costruzioni di elementi geometrici di base', '', 1),
(341, 8, 13, 2, '2013-10-26 06:30:00', '2013-11-01 17:49:56', 16, 0, NULL, 'compito in classe', 'la numerazione decimale', '', 1),
(342, 16, 13, 2, '2013-10-14 07:51:00', '2013-11-04 08:52:18', 10, 0, NULL, 'classwork', 'Entry Test', '', 1),
(343, 10, 13, 2, '2013-10-12 06:02:00', '2013-11-05 07:03:21', 11, 0, NULL, 'scritta', 'Se prÃ©senter; les salutations; les chiffres de 1 Ã  10; ', '', 1),
(344, 16, 7, 2, '2013-10-28 12:07:00', '2013-11-05 12:08:44', 10, 0, NULL, 'classwork', 'Unit6', '', 1);

CREATE TABLE IF NOT EXISTS `rb_visite` (
  `id_visita` int(11) NOT NULL AUTO_INCREMENT,
  `data_ora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(15) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `page` int(11) NOT NULL,
  `uri` text NOT NULL,
  `permessi` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_visita`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Struttura stand-in per le viste `rb_vmaterie_orario`
--
CREATE TABLE IF NOT EXISTS `rb_vmaterie_orario` (
`id_materia` int(11)
,`materia` varchar(200)
,`idpadre` tinyint(4)
,`has_sons` tinyint(1)
,`pagella` tinyint(4)
,`tipologia_scuola` int(11)
);

CREATE TABLE IF NOT EXISTS `rb_voti` (
  `id_voto` int(11) NOT NULL AUTO_INCREMENT,
  `alunno` int(11) NOT NULL,
  `docente` int(11) NOT NULL,
  `materia` int(11) NOT NULL,
  `anno` int(11) NOT NULL,
  `voto` float NOT NULL,
  `modificatori` varchar(10) DEFAULT NULL,
  `descrizione` varchar(200) NOT NULL,
  `tipologia` tinyint(1) NOT NULL DEFAULT '1',
  `note` text,
  `data_voto` date NOT NULL,
  `argomento` text NOT NULL,
  `id_verifica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_voto`),
  KEY `media_voto` (`alunno`,`materia`,`anno`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_voti_obiettivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_voto` int(11) NOT NULL,
  `obiettivo` int(11) NOT NULL,
  `voto` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_w_status` (
  `id_status` smallint(6) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `permessi` smallint(50) DEFAULT NULL,
  PRIMARY KEY (`id_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_w_step` (
  `id_step` smallint(6) NOT NULL AUTO_INCREMENT,
  `descrizione` varchar(200) NOT NULL,
  `ufficio` smallint(6) NOT NULL,
  PRIMARY KEY (`id_step`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_w_step_richieste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_richiesta` int(11) NOT NULL COMMENT 'tabella w_richieste',
  `id_step` smallint(6) NOT NULL COMMENT 'tabella w_step',
  `data_inoltro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text,
  `id_operatore` int(11) DEFAULT NULL COMMENT 'tabella utenti, operatore che realizza lo step',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_w_uffici` (
  `id_ufficio` smallint(6) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `codice_permessi` int(11) NOT NULL,
  PRIMARY KEY (`id_ufficio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb_w_workflow` (
  `id_workflow` smallint(6) NOT NULL AUTO_INCREMENT,
  `richiesta` varchar(250) NOT NULL,
  `num_step` smallint(6) NOT NULL,
  `codice_step` varchar(50) NOT NULL,
  `gruppi` smallint(6) NOT NULL,
  PRIMARY KEY (`id_workflow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rb__classi` (
  `id_classe` int(11) NOT NULL AUTO_INCREMENT,
  `anno_creazione` int(11) NOT NULL,
  PRIMARY KEY (`id_classe`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `rb_genitori`;

CREATE VIEW `rb_genitori` AS select `rb_utenti`.`uid` AS `uid`,`rb_utenti`.`username` AS `username`,`rb_utenti`.`password` AS `password`,`rb_utenti`.`nome` AS `nome`,`rb_utenti`.`cognome` AS `cognome`,`rb_utenti`.`accessi` AS `accessi`,`rb_utenti`.`permessi` AS `permessi`,`rb_utenti`.`last_access` AS `last_access`,`rb_utenti`.`previous_access` AS `previous_access` from (`rb_utenti` join `rb_gruppi_utente`) where ((`rb_utenti`.`uid` = `rb_gruppi_utente`.`uid`) and (`rb_gruppi_utente`.`gid` = 4));

DROP TABLE IF EXISTS `rb_vclassi_s1`;

CREATE VIEW `rb_vclassi_s1` AS select `rb_classi`.`id_classe` AS `id_classe`,`rb_classi`.`anno_corso` AS `anno_corso`,`rb_classi`.`sezione` AS `sezione`,`rb_classi`.`anno_scolastico` AS `anno_scolastico`,`rb_classi`.`tempo_prolungato` AS `tempo_prolungato`,`rb_classi`.`sede` AS `sede`,`rb_classi`.`musicale` AS `musicale`,`rb_classi`.`modulo_orario` AS `modulo_orario`,`rb_classi`.`ordine_di_scuola` AS `ordine_di_scuola`,`rb_classi`.`coordinatore` AS `coordinatore`,`rb_classi`.`segretario` AS `segretario` from `rb_classi` where (`rb_classi`.`ordine_di_scuola` = 1);

DROP TABLE IF EXISTS `rb_vclassi_s2`;

CREATE VIEW `rb_vclassi_s2` AS select `rb_classi`.`id_classe` AS `id_classe`,`rb_classi`.`anno_corso` AS `anno_corso`,`rb_classi`.`sezione` AS `sezione`,`rb_classi`.`anno_scolastico` AS `anno_scolastico`,`rb_classi`.`tempo_prolungato` AS `tempo_prolungato`,`rb_classi`.`sede` AS `sede`,`rb_classi`.`musicale` AS `musicale`,`rb_classi`.`modulo_orario` AS `modulo_orario`,`rb_classi`.`ordine_di_scuola` AS `ordine_di_scuola`,`rb_classi`.`coordinatore` AS `coordinatore`,`rb_classi`.`segretario` AS `segretario` from `rb_classi` where (`rb_classi`.`ordine_di_scuola` = 2);

DROP TABLE IF EXISTS `rb_vclassi_s3`;

CREATE VIEW `rb_vclassi_s3` AS select `rb_classi`.`id_classe` AS `id_classe`,`rb_classi`.`anno_corso` AS `anno_corso`,`rb_classi`.`sezione` AS `sezione`,`rb_classi`.`anno_scolastico` AS `anno_scolastico`,`rb_classi`.`tempo_prolungato` AS `tempo_prolungato`,`rb_classi`.`sede` AS `sede`,`rb_classi`.`musicale` AS `musicale`,`rb_classi`.`modulo_orario` AS `modulo_orario`,`rb_classi`.`ordine_di_scuola` AS `ordine_di_scuola`,`rb_classi`.`coordinatore` AS `coordinatore`,`rb_classi`.`segretario` AS `segretario` from `rb_classi` where (`rb_classi`.`ordine_di_scuola` = 3);

DROP TABLE IF EXISTS `rb_vclassi_s5`;

CREATE VIEW `rb_vclassi_s5` AS select `rb_classi`.`id_classe` AS `id_classe`,`rb_classi`.`anno_corso` AS `anno_corso`,`rb_classi`.`sezione` AS `sezione`,`rb_classi`.`anno_scolastico` AS `anno_scolastico`,`rb_classi`.`tempo_prolungato` AS `tempo_prolungato`,`rb_classi`.`sede` AS `sede`,`rb_classi`.`musicale` AS `musicale`,`rb_classi`.`modulo_orario` AS `modulo_orario`,`rb_classi`.`ordine_di_scuola` AS `ordine_di_scuola`,`rb_classi`.`coordinatore` AS `coordinatore`,`rb_classi`.`segretario` AS `segretario` from `rb_classi` where (`rb_classi`.`ordine_di_scuola` = 5);

DROP TABLE IF EXISTS `rb_vmaterie_orario`;

CREATE VIEW `rb_vmaterie_orario` AS select `rb_materie`.`id_materia` AS `id_materia`,`rb_materie`.`materia` AS `materia`,`rb_materie`.`idpadre` AS `idpadre`,`rb_materie`.`has_sons` AS `has_sons`,`rb_materie`.`pagella` AS `pagella`,`rb_materie`.`tipologia_scuola` AS `tipologia_scuola` from `rb_materie` where ((`rb_materie`.`id_materia` <> 2) and (`rb_materie`.`id_materia` <> 27) and ((`rb_materie`.`idpadre` <> 13) or isnull(`rb_materie`.`idpadre`)));

DROP TABLE IF EXISTS `v_ins`;

CREATE VIEW `v_ins` AS select `rb_scrutini`.`alunno` AS `alunno`,concat_ws(' ',`rb_alunni`.`cognome`,`rb_alunni`.`nome`) AS `al`,`rb_scrutini`.`classe` AS `classe`,avg(`rb_scrutini`.`voto`) AS `AVG(voto)`,concat(`rb_classi`.`anno_corso`,`rb_classi`.`sezione`) AS `desc_classe` from ((`rb_scrutini` join `rb_classi`) join `rb_alunni`) where ((`rb_scrutini`.`alunno` = `rb_alunni`.`id_alunno`) and (`rb_scrutini`.`classe` = `rb_classi`.`id_classe`) and (`rb_scrutini`.`quadrimestre` = 2)) group by `rb_scrutini`.`alunno`,`rb_scrutini`.`classe` having ((avg(`rb_scrutini`.`voto`) < 6) and (avg(`rb_scrutini`.`voto`) > 0)) order by `rb_classi`.`anno_corso`,`rb_classi`.`sezione`;

DROP TABLE IF EXISTS `v_ins_55`;

CREATE VIEW `v_ins_55` AS select `rb_scrutini`.`alunno` AS `alunno`,concat_ws(' ',`rb_alunni`.`cognome`,`rb_alunni`.`nome`) AS `al`,`rb_scrutini`.`classe` AS `classe`,avg(`rb_scrutini`.`voto`) AS `AVG(voto)`,concat(`rb_classi`.`anno_corso`,`rb_classi`.`sezione`) AS `desc_classe` from ((`rb_scrutini` join `rb_classi`) join `rb_alunni`) where ((`rb_scrutini`.`alunno` = `rb_alunni`.`id_alunno`) and (`rb_scrutini`.`classe` = `rb_classi`.`id_classe`) and (`rb_scrutini`.`quadrimestre` = 2)) group by `rb_scrutini`.`alunno`,`rb_scrutini`.`classe` having ((avg(`rb_scrutini`.`voto`) < 5.5) and (avg(`rb_scrutini`.`voto`) > 0)) order by `rb_classi`.`anno_corso`,`rb_classi`.`sezione`;

DROP TABLE IF EXISTS `v_ins_56`;

CREATE VIEW `v_ins_56` AS select `rb_scrutini`.`alunno` AS `alunno`,concat_ws(' ',`rb_alunni`.`cognome`,`rb_alunni`.`nome`) AS `al`,`rb_scrutini`.`classe` AS `classe`,avg(`rb_scrutini`.`voto`) AS `AVG(voto)`,concat(`rb_classi`.`anno_corso`,`rb_classi`.`sezione`) AS `desc_classe` from ((`rb_scrutini` join `rb_classi`) join `rb_alunni`) where ((`rb_scrutini`.`alunno` = `rb_alunni`.`id_alunno`) and (`rb_scrutini`.`classe` = `rb_classi`.`id_classe`) and (`rb_scrutini`.`quadrimestre` = 2)) group by `rb_scrutini`.`alunno`,`rb_scrutini`.`classe` having ((avg(`rb_scrutini`.`voto`) < 5.6) and (avg(`rb_scrutini`.`voto`) > 0)) order by `rb_classi`.`anno_corso`,`rb_classi`.`sezione`;

DROP TABLE IF EXISTS `v_ins_57`;

CREATE VIEW `v_ins_57` AS select `rb_scrutini`.`alunno` AS `alunno`,concat_ws(' ',`rb_alunni`.`cognome`,`rb_alunni`.`nome`) AS `al`,`rb_scrutini`.`classe` AS `classe`,avg(`rb_scrutini`.`voto`) AS `AVG(voto)`,concat(`rb_classi`.`anno_corso`,`rb_classi`.`sezione`) AS `desc_classe` from ((`rb_scrutini` join `rb_classi`) join `rb_alunni`) where ((`rb_scrutini`.`alunno` = `rb_alunni`.`id_alunno`) and (`rb_scrutini`.`classe` = `rb_classi`.`id_classe`) and (`rb_scrutini`.`quadrimestre` = 2)) group by `rb_scrutini`.`alunno`,`rb_scrutini`.`classe` having ((avg(`rb_scrutini`.`voto`) < 5.7) and (avg(`rb_scrutini`.`voto`) > 0)) order by `rb_classi`.`anno_corso`,`rb_classi`.`sezione`;

DROP TABLE IF EXISTS `v_ins_58`;

CREATE VIEW `v_ins_58` AS select `rb_scrutini`.`alunno` AS `alunno`,concat_ws(' ',`rb_alunni`.`cognome`,`rb_alunni`.`nome`) AS `al`,`rb_scrutini`.`classe` AS `classe`,avg(`rb_scrutini`.`voto`) AS `AVG(voto)`,concat(`rb_classi`.`anno_corso`,`rb_classi`.`sezione`) AS `desc_classe` from ((`rb_scrutini` join `rb_classi`) join `rb_alunni`) where ((`rb_scrutini`.`alunno` = `rb_alunni`.`id_alunno`) and (`rb_scrutini`.`classe` = `rb_classi`.`id_classe`) and (`rb_scrutini`.`quadrimestre` = 2)) group by `rb_scrutini`.`alunno`,`rb_scrutini`.`classe` having ((avg(`rb_scrutini`.`voto`) < 5.8) and (avg(`rb_scrutini`.`voto`) > 0)) order by `rb_classi`.`anno_corso`,`rb_classi`.`sezione`;

DROP TABLE IF EXISTS `v_num_insuff`;

CREATE VIEW `v_num_insuff` AS select `rb_scrutini`.`alunno` AS `alunno`,concat_ws(' ',`rb_alunni`.`cognome`,`rb_alunni`.`nome`) AS `al`,`rb_scrutini`.`classe` AS `classe`,count(`rb_scrutini`.`voto`) AS `ins`,concat(`rb_classi`.`anno_corso`,`rb_classi`.`sezione`) AS `desc_classe` from ((`rb_scrutini` join `rb_classi`) join `rb_alunni`) where ((`rb_scrutini`.`alunno` = `rb_alunni`.`id_alunno`) and (`rb_scrutini`.`classe` = `rb_classi`.`id_classe`) and (`rb_scrutini`.`quadrimestre` = 2) and (`rb_scrutini`.`voto` < 6)) group by `rb_scrutini`.`alunno`,`rb_scrutini`.`classe` order by `rb_classi`.`anno_corso`,`rb_classi`.`sezione`;
