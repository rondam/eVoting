<?php

require_once('DatabaseHandler.php');

$dbHandler = new DatabaseHandler();

$dbHandler->newPerson('78852243D', 'David', 'Martín González');
echo '<br>';
\var_dump($dbHandler->getPerson('78852243D'));
echo '<br>';

$dbHandler->newStratum('Estudiante');
echo '<br>';
\var_dump($dbHandler->getStrata());
echo '<br>';

$dbHandler->newElection('Test');
echo '<br>';
\var_dump($dbHandler->getElections());
echo '<br>';

$dbHandler->newCandidate('78852243D', '1', '1');
echo '<br>';
\var_dump($dbHandler->getCandidates('1'));
echo '<br>';

$dbHandler->newVoter('78852243D', '1', '1', 'voter');
echo '<br>';
\var_dump($dbHandler->getVoter('78852243D'));
echo '<br>';

$dbHandler->hasVoted('78852243D', '1');
echo '<br>';
\var_dump($dbHandler->getVoter('78852243D'));
echo '<br>';

$dbHandler->newVote('78852243D');
echo '<br>';
\var_dump($dbHandler->getVotes('78852243D'));
echo '<br>';