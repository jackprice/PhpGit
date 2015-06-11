<?php namespace JackPrice\PhpGit\Contracts;

/**
 *
 *
 * @author Jack Price <jackprice@outlook.com>
 */
interface Reference
{
    public function getHash();

    public function getObject();
}