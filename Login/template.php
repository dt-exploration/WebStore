<?php

abstract class Book
{
    protected $title;
    protected $content;

    public function setTitle($str)
    {
        $this->title = $str;
    }

    public function setContent($str)
    {
        $this->content = $str;
    }

    public abstract function read();

}


abstract class PaperBack extends Book
{
    public function printBook()
    {
        echo "The book $this->title was printed !";
    }
}


class Ebook extends Book
{
    public function generatePDF()
    {
        echo "A pdf was generated for the eBook $this->title";
    }

    public function read()
    {
        $manual = "Gledaj u monitor, pomeraj mis, citaj;";
        return $manual;
    }
}


$paperback = new Ebook;

$paperback->setTitle('Harry Potter');
$paperback->read();



 ?>
