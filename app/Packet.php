<?php namespace Mosaiqo\Packet;

class Packet
{
    /**
     * Create a new Packet Instance
     */
    public function __construct()
    {
        // constructor body
    }

    /**
     * Friendly welcome
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function helloWorld($phrase)
    {
        return $phrase;
    }
}
