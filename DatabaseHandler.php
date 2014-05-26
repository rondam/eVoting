<?php

define('DB_SERVER', 'localhost');
define('DB_USER', 'evoting');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'votacion');

class DatabaseHandler {
	private $db;
	function __construct() {
		$this->db = new mysqli ( DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME );
		if ($this->db->connect_errno) {
			die("Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error);
		}
	}
	
	function __destruct() {
		$this->db->close();
	}
	
	function startTransaction() {
		$this->db->autocommit(false);
	}
	
	function commit() {
		$this->db->commit();
	}
	
	function rollback() {
		$this->db->rollback();
	}
	
	function getPerson($id) {
		$res = $this->db->query('SELECT id, name, surname FROM person ' .
				'WHERE id = "' . $id . '"');
		return $res->fetch_assoc();
	}
	
	function newPerson($id, $name, $surname) {
		if (!$this->db->query('INSERT INTO person(id, name, surname) VALUES (' .
				"\"{$id}\", \"{$name}\", \"{$surname}\")")) {
			die("Failed to create person: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function getStrata() {
		$res = $this->db->query('SELECT id, name FROM stratum');
		$strata = array();
		while ($stratum = $res->fetch_assoc()) {
			$strata[] = $stratum;
		}
		return $strata;
	}
	
	function newStratum($name) {
		if (!$this->db->query('INSERT INTO stratum(name) VALUES ("' . $name . '")')) {
			die("Failed to create stratum: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function getElections() {
		$res = $this->db->query('SELECT id, name FROM election');
		$elections = array();
		while ($election = $res->fetch_assoc()) {
			$elections[] = $election;
		}
		return $elections;
	}
	
	function newElection($name) {
		if (!$this->db->query('INSERT INTO election(name) VALUES ("' . $name . '")')) {
			die("Failed to create election: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function getCandidates($election) {
		$res = $this->db->query('SELECT person, election, stratum FROM candidate ' .
			'WHERE election="' . $election . '"');
		$candidates = array();
		while ($candidate = $res->fetch_assoc()) {
			$candidates[] = $candidate;
		}
		return $candidates;
	}
	
	function newCandidate($person, $election, $stratum) {
		if (!$this->db->query('INSERT INTO candidate(person, election, stratum) VALUES (' .
				"\"{$person}\", {$election}, {$stratum})")) {
			die("Failed to create candidate: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function getVoter($person) {
		$res = $this->db->query('SELECT person, election, stratum, hasVoted, role '
				. 'FROM voter WHERE person = "' . $person . '"');
		return $res->fetch_assoc();
	}
	
	function newVoter($person, $election, $stratum, $role) {
		if (!$this->db->query('INSERT INTO voter(person, election, stratum, hasVoted, role) VALUES (' .
				"\"{$person}\", \"{$election}\", \"{$stratum}\", false, \"{$role}\")")) {
			die("Failed to create voter: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function hasVoted($person, $election) {
		if (!$this->db->query('UPDATE voter SET hasVoted=true ' .
				"WHERE person = \"{$person}\" AND election = \"{$election}\"")) {
			die("Failed to update voter: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function getVotes($candidate) {
		$res = $this->db->query('SELECT COUNT(*) FROM vote WHERE candidate = "' . $candidate . '"');
		$row = $res->fetch_assoc();
		return $row['COUNT(*)'];
	}
	
	function newVote($candidate) {
	if (!$this->db->query('INSERT INTO vote(candidate) VALUES ("' . $candidate . '")')) {
			die("Failed to create vote: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
}