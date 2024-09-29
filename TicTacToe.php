<?php
require_once 'Database.php';
require_once 'SessionManager.php';

class TicTacToe
{
    private $db;
    private $sessionManager;
    private $board; // Array to represent the board
    private $currentPlayer;
    private $nextPlayer; // Track next player
    private $isFirstMove; // Track first move

    public function __construct(Database $db, SessionManager $sessionManager)
    {
        $this->db = $db;
        $this->sessionManager = $sessionManager;

        // Initialize game state
        if (!$this->sessionManager->get('board')) {
            $this->resetGame(); // Initialize the game if not set
        } else {
            $this->board = $this->sessionManager->get('board'); // Use the session variable directly
            $this->nextPlayer = $this->sessionManager->get('nextPlayer'); // Load next player from session
            $this->isFirstMove = $this->sessionManager->get('isFirstMove'); // Load first move status from session
        }

        $this->currentPlayer = $this->sessionManager->get('currentPlayer');
    }

    public function resetGame()
    {
        // Initialize the board as a mapping of tile identifiers to empty spaces
        $this->sessionManager->set('board', array_fill(0, 9, '-')); // Initialize board with 9 empty spaces
        $this->sessionManager->set('currentPlayer', 'X'); // Player X starts
        $this->sessionManager->set('nextPlayer', 'O'); // Next player will be O after X
        $this->sessionManager->set('isFirstMove', true); // Track the first move

        $this->board = $this->sessionManager->get('board');
        $this->currentPlayer = $this->sessionManager->get('currentPlayer');
        $this->nextPlayer = $this->sessionManager->get('nextPlayer');
        $this->isFirstMove = $this->sessionManager->get('isFirstMove');

        // Truncate the moves table
        $this->truncateMoves();
    }

    private function truncateMoves()
    {
        $this->db->query("TRUNCATE TABLE tic_tac_toe"); // Clear the moves table
    }

    public function makeMove($tileId)
    {
        // Map tile IDs to their respective array positions
        $position = intval(substr($tileId, 1)) - 1; // e.g., 't1' -> 0, 't2' -> 1, etc.

        // Check for valid move
        if ($this->board[$position] !== '-') {
            return ['success' => false, 'message' => 'Invalid move!'];
        }
        
        // Handle first move
        if ($this->isFirstMove) {
            $this->isFirstMove = false; // Mark the first move as done
            $this->sessionManager->set('isFirstMove', false);
        } else {
            // Switch player only after the first move
            $this->currentPlayer = $this->nextPlayer;
            $this->nextPlayer = ($this->currentPlayer === 'X') ? 'O' : 'X';
        }

        // Update session variables
        $this->board[$position] = $this->currentPlayer; // Set the current player's mark
        $this->sessionManager->set('board', $this->board); // Store the updated board in the session
        $this->sessionManager->set('currentPlayer', $this->currentPlayer);
        $this->sessionManager->set('nextPlayer', $this->nextPlayer);

        // Store the current move in the database
        $this->storeMove($tileId, $this->currentPlayer, $this->nextPlayer); // Store the move with current and next players

        // Check for winner
        if ($this->checkWinner()) {
            $this->storeWinner($this->currentPlayer); // Store winner in the winners table
            return ['success' => true, 'player' => $this->currentPlayer, 'winner' => $this->currentPlayer];
        }

        // Check for draw
        if ($this->isDraw()) {
            return ['success' => true, 'draw' => true];
        }

        return ['success' => true, 'player' => $this->currentPlayer];
    }

    private function storeMove($tileId, $currentPlayer, $nextPlayer)
    {
        // Format the board as tile:value pairs
        $formattedBoard = '';
        foreach ($this->board as $index => $value) {
            $tileId = 't' . ($index + 1); // e.g., t1, t2, ...
            $formattedBoard .= "$tileId:$value "; // e.g., t1:X t2:O ...
        }
        $formattedBoard = trim($formattedBoard); // Trim any trailing spaces

        // Store the move in the tic_tac_toe table, including next player
        $stmt = $this->db->prepare("INSERT INTO tic_tac_toe (board_state, current_player, next_player) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $formattedBoard, $currentPlayer, $nextPlayer); // Bind parameters
        $stmt->execute();
        $stmt->close();
    }

    private function checkWinner()
    {
        // Winning combinations using tile identifiers
        $winningCombinations = [
            ['t1', 't2', 't3'], // Row 1
            ['t4', 't5', 't6'], // Row 2
            ['t7', 't8', 't9'], // Row 3
            ['t1', 't4', 't7'], // Column 1
            ['t2', 't5', 't8'], // Column 2
            ['t3', 't6', 't9'], // Column 3
            ['t1', 't5', 't9'], // Diagonal \
            ['t3', 't5', 't7']  // Diagonal /
        ];

        foreach ($winningCombinations as $combination) {
            if (
                $this->board[$this->getPosition($combination[0])] === $this->currentPlayer &&
                $this->board[$this->getPosition($combination[1])] === $this->currentPlayer &&
                $this->board[$this->getPosition($combination[2])] === $this->currentPlayer
            ) {
                return true; // Found a winning combination
            }
        }
        return false; // No winner found
    }

    private function getPosition($tileId)
    {
        return intval(substr($tileId, 1)) - 1; // Convert tile ID (e.g., 't1') to index (0 for 't1')
    }

    private function isDraw()
    {
        return !in_array('-', $this->board); // If there are no empty spaces left
    }

    private function storeWinner($winner)
    {
        $boardState = implode('', $this->board); // Convert array to a string
        $stmt = $this->db->prepare("INSERT INTO winners (winner, board_state) VALUES (?, ?)");
        $stmt->bind_param("ss", $winner, $boardState); // Bind parameters
        $stmt->execute();
        $stmt->close();
    }
}
