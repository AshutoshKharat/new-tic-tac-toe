<?php
require_once 'SessionManager.php';
require_once 'Database.php';
require_once 'TicTacToe.php';

$sessionManager = new SessionManager();
$db = new Database("localhost", "root", "", "tictactoe_db");
$game = new TicTacToe($db, $sessionManager);

// Ensure that the game starts with player X only if the game is new
if (!$sessionManager->get('currentPlayer')) {
    $sessionManager->set('currentPlayer', 'X'); // Player X starts
}

// Get the current player from the session
$currentPlayer = $sessionManager->get('currentPlayer');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe - Dark Theme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .table {
            background-color: #1e1e1e;
        }
        .cell {
            cursor: pointer;
            height: 100px;
            font-size: 2em;
            color: white;
            min-width: 35px;
            max-width: 35px;
        }
        #message {
            font-size: 1.5em;
            margin-bottom: 15px;
            text-align: center;
            color: #ff4081;
        }
        #resetBtn {
            background-color: #ff4081;
            border: none;
            color: white;
        }
        #resetBtn:hover {
            background-color: #e91e63;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Tic Tac Toe</h1>
        <div id="message">Current Player: <?= $currentPlayer ?></div> <!-- Display the current player -->
        <div class="row justify-content-center">
            <div class="col-4">
                <table class="table table-bordered text-center">
                    <tbody>
                        <!-- Dynamically create board cells with t1 to t9 -->
                        <?php for ($i = 0; $i < 3; $i++): ?>
                            <tr>
                                <?php for ($j = 0; $j < 3; $j++): ?>
                                    <td class="cell" data-position="<?= $i * 3 + $j ?>" id="t<?= $i * 3 + $j + 1 ?>">
                                        <?php
                                        // Display current board state for each cell
                                        if ($sessionManager->get('board')) {
                                            $currentCell = $sessionManager->get('board'); // Use the session variable directly
                                            echo htmlspecialchars($currentCell[$i * 3 + $j]); // Show 'X', 'O', or '-'
                                        } else {
                                            echo '-'; // Default empty cell if session is not set
                                        }
                                        ?>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
                <button id="resetBtn" class="btn btn-warning">Reset Game</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let gameActive = true; // Track if the game is active

            $('.cell').click(function() {
                if (!gameActive) return; // Prevent clicking if game is over

                let tileId = $(this).attr('id'); // Get the tile ID (e.g., t1, t2, ...)
                let cell = $(this); // Store the reference to the clicked cell

                // AJAX call to process move
                $.post('process_move.php', { tileId: tileId }, function(response) {
                    let result = JSON.parse(response);
                    if (result.success) {
                        cell.text(result.player); // Update the clicked cell with the player's symbol
                        $('#message').text("Current Player: " + (result.player === 'X' ? 'O' : 'X')); // Update the message for the next player
                        if (result.winner) {
                            $('#message').text("Winner: " + result.winner);
                            gameActive = false; // Game is over
                        } else if (result.draw) {
                            $('#message').text("It's a draw!");
                            gameActive = false; // Game is over
                        }
                    } else {
                        alert(result.message);
                    }
                });
            });

            $('#resetBtn').click(function() {
                $.post('reset_game.php', function(response) {
                    let result = JSON.parse(response);
                    if (result.success) {
                        location.reload(); // Reload the page to reset the game
                    }
                });
            });
        });
    </script>
</body>
</html>
