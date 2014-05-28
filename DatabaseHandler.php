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
	
	function newPerson($id, $name, $surname, $password) {
		if (!$this->db->query('INSERT INTO person(id, name, surname, password) VALUES (' .
				"\"{$id}\", \"{$name}\", \"{$surname}\", \"{$password}\")")) {
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
		$res = $this->db->query('SELECT id, name, date FROM election');
		$elections = array();
		while ($election = $res->fetch_assoc()) {
			$elections[] = $election;
		}
		return $elections;
	}
	
	function getElection($election) {
		$res = $this->db->query('SELECT id, name, date FROM election WHERE id = ' . $election);
		return $res->fetch_assoc();
	}
	
	function getNextElections() {
		$res = $this->db->query('SELECT id, name, date FROM election WHERE date > NOW() ORDER BY DATE ASC');
		if ($res != null) {
			$elections = array();
			while ($election = $res->fetch_assoc()) {
				$elections[] = $election;
			}
			return $elections;
		}
		return null;
	}
	
	function newElection($name, $date) {
		$dateInfo = date_parse_from_format('d/m/Y', $date);
		$isoDate = $dateInfo['year'] . '-' . $dateInfo['month'] . '-' . $dateInfo['day'];
		if (!$this->db->query('INSERT INTO election(name, date) VALUES ("' .
				$name . '", "' . $isoDate . '")')) {
			die("Failed to create election: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function getCandidates($election, $stratum) {
		$res = $this->db->query('SELECT person, election, stratum FROM candidate ' .
			'WHERE election=' . $election . ' AND stratum=' . $stratum);
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
	
	function getVoter($person, $election) {
		$res = $this->db->query('SELECT person, election, stratum, hasVoted, role '
				. 'FROM voter WHERE person ="' . $person . '" AND election = ' . $election);
		return $res->fetch_assoc();
	}
	
	function getVoters($election) {
		$res = $this->db->query('SELECT person, election, stratum, hasVoted, role '
				. 'FROM voter WHERE election = ' . $election);
		$voters = array();
		while ($voter = $res->fetch_assoc()) {
			$voters[] = $voter;
		}
		return $voters;
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
	
	function getVotes($person, $election) {
		$res = $this->db->query('SELECT COUNT(*) FROM vote WHERE person = "' . $person .
				'" AND election = ' . $election);
		$row = $res->fetch_assoc();
		return $row['COUNT(*)'];
	}
	
	function newVote($person, $election) {
	if (!$this->db->query('INSERT INTO vote(person, election) VALUES ("' .
			$person . '", ' . $election . ')')) {
			die("Failed to create vote: (" . $this->db->errno . ") " . $this->db->error);
		}
	}
	
	function getCurrentElection() {
		$res = $this->db->query('SELECT * FROM currentElection');
		$row = $res->fetch_assoc();
		$chosen = explode(',', $row['chosen']);
		$strata = array();
		foreach ($chosen as $pair) {
			$temp = explode(':', $pair);
			$strata[$temp[0]] = $temp[1];
		}
		$row['chosen'] = $strata;
		return $row;
	}
	
	function setCurrentElection($start, $end, $strata) {
		$election = $this->getElection(CURRENT_ELECTION);
		if ($election == null) {
			return false;
		}
		$startDate = $election['date'] . ' ' . $start;
		$endDate = $election['date'] . ' ' . $end;
		$stratumPeople = '';
		foreach ($strata as $stratum => $people) {
			if ($stratumPeople !== '') {
				$stratumPeople .= ',';
			}
			$stratumPeople .= $stratum . ':' . $people;
		}
		if (!$this->db->query('DELETE FROM currentElection')) {
			die('Failed to remove current election');
		}
		$query = 'INSERT INTO currentElection(forceUnique, electionId, start, end, chosen) ' .
				'VALUES("id", ' . CURRENT_ELECTION . ', "' . $startDate .
				'",  "' . $endDate . '", "' . $stratumPeople . '")';
		if (!$this->db->query($query)) {
			die('Failed to modify current election: (' . $this->db->errno . ") " . $this->db->error);
		}
	}
}