<?php namespace JackPrice\PhpGit\Contracts;

/**
 * A blob (binary large object) is the content of a file. It has no timestamp,
 * filename or other metadata.
 * Note that blobs are immutable, so cannot be edited.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
interface Blob
{
    /**
     * Get the content represented by this blob.
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Get the hash of this blob.
     *
     * @return mixed
     */
    public function getHash();
}