<?php


class Game
{
    private $_player1;
    private $_player2;
    //registers who is current player
    private $_currentPlayer;
    private $_matched_pairs = 0;

    private $_colors = ['Red','Blue','Green','Yellow'];
    private $_shapes = ['Square','Circle','Triangle','Oval'];
    private $_available_cards = [];
    private $_playing_cards = [];
    private $_unmatched_indices = [];

    private $_max_pairs = 16;

    public function __construct(Player $p1, Player $p2,int $pairs = null) {
        //set players
        if (!empty($pairs)) $this->setMaxPairs($pairs);
        $this->setPlayers($p1,$p2);
        $this->initGame();

    }
    private function setMaxPairs(int $val)
    {
        echo 'Setting Maximun Pairs'.PHP_EOL;
        $this->_max_pairs = $val;
    }

    private function setPlayers (Player $p1, Player $p2)
    {
        echo "Setting Players...".PHP_EOL;

        $this->_player1 = $p1;
        echo 'Player 1 ['.$p1->getName().'] ready!'.PHP_EOL;
        $this->_player2 = $p2;
        echo 'Player 2 ['.$p2->getName().'] ready!'.PHP_EOL;

    }

    private function initGame()
    {
        //initiate game environment
        echo 'Initiating game environment...'.PHP_EOL;

        $this->selectCards();
        $this->shuffleCards();

    }

    private function selectCards()
    {
        echo 'Selecting '.($this->_max_pairs*2).' random pairs...'.PHP_EOL;

        //lets select a random combination of cards
        for ($i=0;$i<$this->_max_pairs;$i++) {
            $cKey = array_rand($this->_colors);
            $sKey = array_rand($this->_shapes);

            //now select random shape for this color
            $cardName = $this->_colors[$cKey] . ' '.$this->_shapes[$sKey];

            //create two and push into playing card deck
            $this->_playing_cards[] = [
                'label'     => $cardName,
                'matched'   => false
            ];
            $this->_playing_cards[] = [
                'label'     => $cardName,
                'matched'   => false
            ];
        }

    }
    private function shuffleCards()
    {
        echo 'Shuffling cards...'.PHP_EOL;

        shuffle($this->_playing_cards);

    }

    /**
     * @return array of all cards. **** denotes not revealed, else label of card
     */
    public function displayPlayingCards()
    {
        $list = [];
        //reset unmatched_indices
        $this->_unmatched_indices = [];

        foreach ($this->_playing_cards as $k => $v) {

            if ($v['matched']) {
                $list[] = $v['label'];
            } else {
                $list[] = '****';
                //pushed unmatched indices to avoid making another iteration
                $this->_unmatched_indices[] = $k;
            }


        }
        print_r($list);
        return $list;
    }

    /**
     * Flip two cards to see if they matched.
     * Cards will be set to matched=true
     * and player score will increment by 1
     *
     * @param int $card1 index of first card to flip
     * @param int $card2 index of second card to flip
     */
    public function play(int $card1, int $card2)
    {
        echo '--------------------------'.PHP_EOL;
        $c1 = $this->flipCard($card1);
        $c2 = $this->flipCard($card2);

        //if matched, increment score for player
        if ($c1 == $c2) {
            echo 'Cards Matched!'.PHP_EOL;
            $this->updateCards($card1,$card2);
            $this->updateScore();
            $this->_matched_pairs++;
        } else {
            echo 'Sorry, cards did not match.'.PHP_EOL;
        }
        echo '--------------------------'.PHP_EOL;

    }

    private function updateScore()
    {
        if ($this->_currentPlayer == 1) {
            $this->_player1->addScore();
        } else {
            $this->_player2->addScore();
        }
    }
    private function updateCards(int $card1, int $card2)
    {
        $this->_playing_cards[$card1]['matched'] = true;
        $this->_playing_cards[$card2]['matched'] = true;
    }

    /**
     * @param int $val index of card to flip
     * @return mixed returns false if card is already matched or reveals label
     */
    private function flipCard(int $val)
    {
        echo 'Flipping card #'.$val;

        if (!$this->_playing_cards[$val]['matched']) {

            echo ' '.$this->_playing_cards[$val]['label'].' card revealed.'.PHP_EOL;
            return $this->_playing_cards[$val]['label'];

        } else {
            echo 'Card already matched. Try again!'.PHP_EOL;
            return false;
        }
    }

    public function getUnmatchedIndices()
    {
        return $this->_unmatched_indices;
    }
    public function isGameOver()
    {
        //check through all records to see
        return $this->_matched_pairs === $this->_max_pairs ? true : false;

    }

    public function gameOver()
    {
        echo 'GAME OVER!!!'.PHP_EOL.PHP_EOL.
             'The Score:'.PHP_EOL.
             '-------------------------'.PHP_EOL.
             $this->_player1->getName().': '.$this->_player1->getScore().PHP_EOL.
             $this->_player2->getName().': '.$this->_player2->getScore().PHP_EOL.PHP_EOL.
             'Thank you for playing.';
    }

        /**
     * switches players turn and checks if all cards have been matched.
     */
    public function setTurn()
    {
        echo '' . PHP_EOL;
        if ($this->_currentPlayer != 1) {
            $this->_currentPlayer = 1;
            echo "Player 1's turn." . PHP_EOL;
        } else {
            $this->_currentPlayer = 2;
            echo "Player 2's turn." . PHP_EOL;
        }

        echo PHP_EOL.'Select 2 cards...'.PHP_EOL;

    }

}