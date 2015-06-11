<?php namespace JackPrice\PhpGit;

use JackPrice\PhpGit\Exceptions\Exception;
use JackPrice\PhpGit\Exceptions\FileNotFoundException;
use JackPrice\PhpGit\Exceptions\InvalidDataException;
use JackPrice\PhpGit\Objects\Blob;
use JackPrice\PhpGit\Objects\Commit;
use JackPrice\PhpGit\Objects\Object;
use JackPrice\PhpGit\Objects\Tree;
use JackPrice\PhpGit\References\Reference;

/**
 * Class Repository
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Repository
{
    /**
     * The top-level directory of this repository, i.e. without the '.git/'.
     *
     * @var string
     */
    private $directory;

    /**
     * Initialise a new repository.
     *
     * @param string $directory The path to the git repository
     *
     * @throws FileNotFoundException
     */
    public function __construct($directory)
    {
        $realDirectory = realpath($directory);

        if ($realDirectory === false || !is_dir($realDirectory)) {
            throw new FileNotFoundException("Git directory [$directory] could not be opened");
        }

        $this->directory = $realDirectory;

        if (!is_dir("{$this->directory}/.git")) {
            throw new FileNotFoundException("Directory [$directory] is not a valid git repository");
        }
    }

    /**
     * Get the description of this repository.
     *
     * @return string
     */
    public function getDescription()
    {
        return file_get_contents("{$this->directory}/.git/description");
    }

    /**
     * Set the description of this repository.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        file_put_contents("{$this->directory}/.git/description", $description);

        return $this;
    }

    /**
     * Convert a short hash into its full form.
     *
     * @param $hash
     *
     * @return string
     * @throws Exception
     */
    public function expandHash($hash)
    {
        $glob = implode(DIRECTORY_SEPARATOR, array(
            $this->directory,
            '.git',
            'objects',
            substr($hash, 0, 2),
            substr($hash, 2) . '*',
        ));

        $files = glob($glob);

        if ($files === false || count($files) === 0) {
            throw new Exception("Hash [$hash] does not exist in repository");
        }

        if (count($files) > 1) {
            throw new Exception("Ambigious hash [$hash]");
        }

        $file = explode(DIRECTORY_SEPARATOR, $files[0]);

        return implode('', array_slice($file, -2));
    }

    /**
     * Get an object by its sha-1 hash.
     *
     * @param string $hash
     *
     * @return Object
     * @throws FileNotFoundException
     */
    public function getObject($hash)
    {
        $hash = $this->expandHash($hash);

        $path = implode(DIRECTORY_SEPARATOR, array(
            $this->directory,
            '.git',
            'objects',
            substr($hash, 0, 2),
            substr($hash, 2),
        ));

        if (!is_file($path)) {
            throw new FileNotFoundException("Object [$hash] could not be located");
        }

        $contents = file_get_contents($path);
        $contents = gzuncompress($contents);

        $parts = explode("\0", $contents, 2);
        $details = explode(' ', $parts[0]);
        $type = $details[0];
        $length = $details[1];
        $content = $parts[1];

        switch ($type) {
            case 'commit':
                return new Commit($content);
            case 'tree':
                return new Tree($content);
            case 'blob':
                return new Blob($content);
            case 'tag':
                $type = Object::TYPE_TAG;
                break;
        }

        return new Object($type, $content);
    }

    /**
     * Create an object.
     *
     * @param $type
     * @param $data
     *
     * @return Object
     */
    public function createObject($type, $data)
    {
        $object = new Object($type, $data);
        $hash = $object->getHash();

        $path = $this->makeGitPath('objects', substr($hash, 0, 2));

        if (!is_dir($path)) {
            mkdir($path);
        }

        $path = $this->makeGitPath('objects', substr($hash, 0, 2), substr($hash, 2));

        $fileData = $object->getTypeName() . strlen($data) . "\0" . $data;
        $fileData = gzcompress($fileData);

        file_put_contents($path, $fileData);

        return $object;
    }

    /**
     * Build a path to a git file from the given arguments.
     * Takes a variable number of path components
     *
     * @return string
     */
    private function makeGitPath()
    {
        $components = func_get_args();
        array_unshift($components, '.git');
        array_unshift($components, $this->directory);

        return implode(DIRECTORY_SEPARATOR, $components);
    }

    /**
     * Get the reference specified.
     *
     * @param $name
     *
     * @return Reference
     */
    public function getReference($name)
    {
        $path = $this->makeGitPath('refs', $name);
        $hash = trim(file_get_contents($path));

        return new Reference($this, $name, $hash);
    }

    /**
     * Update a reference.
     *
     * @param $name
     * @param $hash
     */
    public function setReference($name, $hash)
    {
        $path = $this->makeGitPath('refs', $name);

        file_put_contents($path, "$hash\n");
    }

    /**
     * Get the commit at the head reference specified.
     *
     * @param null $name
     *
     * @return Contracts\Commit
     * @throws InvalidDataException
     */
    public function getHead($name = null)
    {
        if ($name === null) {
            $path = $this->makeGitPath('HEAD');
            $head = trim(file_get_contents($path));

            if (strpos($head, 'ref: refs/') === 0) {
                $name = str_replace('ref: refs/', '', $head);

                return $this->getReference($name);
            }

            else {
                throw new InvalidDataException('Invalid HEAD file');
            }
        }

        return $this->getReference("heads/$name")->getCommit();
    }
}