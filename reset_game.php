<?php
require_once 'TicTacToe.php';
require_once 'Database.php';
require_once 'SessionManager.php';

// Create instances of Database and SessionManager
$db = new Database("localhost", "root", "", "tictactoe_db");
$sessionManager = new SessionManager();
$game = new TicTacToe($db, $sessionManager);

$game->resetGame(); // Reset the game
echo json_encode(['success' => true, 'message' => 'Game reset!']); // Return success message
