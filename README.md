**Tic Tac Toe Game**
A simple Tic Tac Toe game built with PHP, HTML, MySQL, and JavaScript (using jQuery). This project allows two players to play Tic Tac Toe, and stores the game state in a MySQL database.

**Features**
Classic Tic Tac Toe game with a 3x3 grid.
Players take turns between X and O.
Stores each move and the game's progress in a MySQL database.
Detects wins, losses, and draws.
Reset functionality to restart the game.
Simple dark-themed interface with Bootstrap for styling.

**Requirements**
PHP (>= 7.4 or 8.x)
MySQL (or MariaDB)
Apache/Nginx web server (XAMPP/WAMP for local development)
Composer (for package management)
jQuery (for handling AJAX requests)

**Installation**
1. Clone the Repository
bash
Copy code
git clone https://github.com/yourusername/tic-tac-toe.git
cd tic-tac-toe
2. Set Up Database
Create a MySQL database (e.g., tictactoe_db).
Import the SQL schema provided below:
sql
Copy code
CREATE DATABASE tictactoe_db;
USE tictactoe_db;

CREATE TABLE tic_tac_toe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    board_state TEXT,
    current_player VARCHAR(1),
    next_player VARCHAR(1)
);

CREATE TABLE winners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    winner VARCHAR(1),
    board_state TEXT
);
3. Configure Database
Edit the database configuration inside the Database.php file:

php
Copy code
class Database {
    private $connection;

    public function __construct() {
        $this->connection = new mysqli("localhost", "root", "", "tictactoe_db");
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
}
Ensure the connection details match your local MySQL setup (username, password, and database name).

4. Set Up Apache/Nginx
Place the project in your web server's root directory (e.g., htdocs for XAMPP).
Start your Apache or Nginx server.
Open the application in your browser (e.g., http://localhost/tic-tac-toe/).
5. Dependencies
Using Composer
If you are using Composer, run:

bash
Copy code
composer install
6. Playing the Game
Open the Tic Tac Toe application in your browser.
The first player is X.
Click on the grid to make a move.
The game will detect a winner or draw and display the result.
Use the Reset Game button to start a new game.
File Structure
bash
Copy code
tic-tac-toe/
├── Database.php          # Handles database connection and queries
├── SessionManager.php    # Manages the game session
├── TicTacToe.php         # Core game logic (handling moves, checking winners, etc.)
├── index.php             # Main game interface (HTML & PHP)
├── process_move.php      # Processes player moves via AJAX
├── reset_game.php        # Resets the game state
├── assets/               # Static assets (optional, like CSS or images)
├── README.md             # Project documentation
How It Works
index.php: Displays the Tic Tac Toe board and handles front-end interactions with the game.
TicTacToe.php: Contains the core game logic such as move validation, player switching, winner detection, and game reset.
Database.php: Connects to MySQL and stores game states and player moves.
SessionManager.php: Handles the PHP session, ensuring game state is stored between requests.
process_move.php: Processes moves sent from the front-end via AJAX, updates the game state, and returns the response.
reset_game.php: Resets the game state, clearing the session and database records.
AJAX Interaction
When a player clicks a tile, an AJAX request is sent to process_move.php, which handles the following:

Checks if the move is valid.
Updates the board with the current player's symbol.
Switches to the next player.
Checks for a winner or a draw.
Resetting the Game
The Reset Game button sends an AJAX request to reset_game.php, which:

Clears the session and database entries.
Restores the game to its initial state.
