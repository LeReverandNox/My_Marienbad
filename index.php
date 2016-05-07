<?php
session_start();
require_once("includes/functions.php");

if (!isset($_SESSION["leaderboard"])) {

    $_SESSION["leaderboard"] = ["1" => 0, "2" => 0];
    $_SESSION["game_count"] = 0;

}

if (!isset($_SESSION["on_game"]) || isset($_POST["reset"]) || $_SESSION["on_game"] === false) {

    $_SESSION["first_step"] = true;
    $_SESSION["on_game"] = false;
    unset($_SESSION["endgame"]);

}

if (isset($_POST["begin"])) {

    beginGame();

}

if (isset($_POST["reset"])) {

    unset($_SESSION["grid"]);

}

if (!isset($_SESSION["grid"])) {

    $grid = [[1],
    [1, 1, 1],
    [1, 1, 1, 1, 1],
    [1, 1, 1, 1, 1, 1, 1]];

} else {

    $grid = $_SESSION["grid"];

}


if (isset($_POST["jouer"])) {

    // echo "On var_dump POST<br />";
    // var_dump($_POST);
    // echo "<br />Le joueur " . $_SESSION["turn"] . " joue un coup !<br /><br />";
    $grid = makeMove($grid);
    $_SESSION["grid"] = $grid;

}

// echo "<br /><br />On var_dump SESSION<br />";
// var_dump($_SESSION);
// echo "Nbr de parties jouées : <br />";
// var_dump($_SESSION["game_count"]);
// echo "Score <br />";
// var_dump($_SESSION["leaderboard"]["1"]);
// var_dump($_SESSION["leaderboard"]["2"]);

require_once("views/index.html");