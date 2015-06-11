<?php namespace JackPrice\PhpGit\Contracts;

use Datetime;

/**
 * A commit points to a tree and contains a timestamp, a commit message and
 * zero or more parent commits.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
interface Commit
{
    /**
     * Get the hash of this commit.
     *
     * @return mixed
     */
    public function getHash();

    /**
     * Get the timestamp of this commit.
     *
     * @return DateTime
     */
    public function getTimestamp();

    /**
     * Get the message of this commit.
     *
     * @return string
     */
    public function getMessage();

    /**
     * Get the tree that this commit points to.
     *
     * @return Tree
     */
    public function getTree();

    /**
     * Get the parents of this commit.
     *
     * @return Commit[]
     */
    public function getParents();
}