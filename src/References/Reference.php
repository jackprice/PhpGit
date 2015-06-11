<?php namespace JackPrice\PhpGit\References;

use JackPrice\PhpGit\Contracts\Commit;
use JackPrice\PhpGit\Repository;

/**
 * Reference helper class.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Reference
{
    /**
     * The repository this reference helper is part of.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * The relative path, or name, of this reference.
     *
     * @var string
     */
    protected $path;

    /**
     * The hash that this reference points to.
     *
     * @var string
     */
    protected $hash;

    /**
     * @param Repository $repository
     * @param            $path
     * @param            $hash
     */
    function __construct(Repository &$repository, $path, $hash)
    {
        $this->repository = $repository;
        $this->path = $path;
        $this->hash = $hash;
    }

    /**
     * Get the commit pointed to by this reference.
     *
     * @return Commit
     */
    public function getCommit()
    {
        return $this->repository->getObject($this->getHash());
    }

    /**
     * Get the hash of the commit pointed to by this reference.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Update this reference.
     *
     * @param Commit $commit
     */
    public function setCommit(Commit $commit)
    {
        $this->hash = $commit->getHash();
        $this->repository->setReference($this->path, $this->hash);
    }

    /**
     * Update this reference.
     *
     * @param $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        $this->repository->setReference($this->path, $this->hash);
    }
}