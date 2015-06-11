<?php namespace JackPrice\PhpGit\Objects;

use JackPrice\PhpGit\Contracts\Blob as BlobInterface;

/**
 *
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Blob extends Object implements BlobInterface
{
    /**
     * Overload our parents constructor with our object type.
     *
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct(Object::TYPE_BLOB, $data);
    }

    /**
     * Get the content represented by this blob.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->getData();
    }
}