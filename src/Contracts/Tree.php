<?php namespace JackPrice\PhpGit\Contracts;

/**
 * A tree contains a list of file names, each with metadata and the blob or
 * subtree that it represents.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
interface Tree
{
    /**
     * Get the hash of this tree.
     *
     * @return mixed
     */
    public function getHash();
}