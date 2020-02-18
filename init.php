<?php

include "lib/Game.php";
include "lib/Player.php";

//creating two player objects
$p1 = new Player('Marvin');
$p2 = new Player('Hannah');

//initiate game instance
$game = new Game($p1,$p2);

$unmatched_cards = [];

while (true) {

    if ($game->isGameOver()) break;

    //display current playing cards
    $cards = $game->displayPlayingCards();
    //randomly select two indices
    $unmatched = $game->getUnmatchedIndices();
    $random1 = array_rand($unmatched);

    //making sure we do not get a random duplicate
    while (true) {
        $random2 = array_rand($unmatched);
        if ($random2 != $random1) break;
    }

    $card1 = (int) $unmatched[$random1];
    $card2 = (int) $unmatched[$random2];
    echo 'setting turn'.PHP_EOL;
    $game->setTurn();
    $game->play($card1,$card2);

}

$game->gameOver();
$game = null;


