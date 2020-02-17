<?php


class Player
{
    private $_name;
    private $_score = 0;

    public function __construct(string $name) {
        $this->setName($name);
    }

    private function setName(string $val)
    {
        $this->_name = $val;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function addScore()
    {
        $this->_score++;
    }

    public function getScore ()
    {
        return $this->_score;
    }
}