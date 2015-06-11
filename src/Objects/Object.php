<?php namespace JackPrice\PhpGit\Objects;

use JackPrice\PhpGit\Exceptions\Exception;

/**
 * Represents the most abstract form of a git object.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Object
{
    const TYPE_COMMIT = 1;
    const TYPE_TREE = 2;
    const TYPE_BLOB = 3;
    const TYPE_TAG = 4;

    /**
     * The type of this object.
     *
     * @var
     */
    protected $type = null;

    /**
     * The data represented by this object.
     *
     * @var string
     */
    protected $data = null;

    /**
     * Construct a new object.
     *
     * @param $type
     * @param $data
     */
    public function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Get the hash of this object.
     *
     * @return string
     */
    public function getHash()
    {
        $data = $this->getTypeName() . ' ' . $this->getSize() . "\0" . $this->getData();

        return sha1($data);
    }

    /**
     * Get the size of the data represented by this object.
     *
     * @return int
     */
    public function getSize()
    {
        return strlen($this->getData());
    }

    /**
     * Get the data represented by this object.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the type of this object.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the type of this object for use by git internals.
     *
     * @internal
     *
     * @return string
     * @throws Exception
     */
    public function getTypeName()
    {
        switch ($this->type) {
            case static::TYPE_COMMIT:
                return 'commit';
            case static::TYPE_TREE:
                return 'tree';
            case static::TYPE_BLOB:
                return 'blob';
            case static::TYPE_TAG:
                return 'tag';
        }

        throw new Exception('Invalid object type');
    }
}