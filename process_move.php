<?php
require_once 'TicTacToe.php';
require_once 'Database.php';
require_once 'SessionManager.php';

// Create instances of Database and SessionManager
$db = new Database("localhost", "root", "", "tictactoe_db");
$sessionManager = new SessionManager();
$game = new TicTacToe($db, $sessionManager);

$tileId = $_POST['tileId']; // Get the tile ID from the AJAX request
$response = $game->makeMove($tileId); // Pass the tile ID to the makeMove method

echo json_encode($response); // Return the response as JSON
