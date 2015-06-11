<?php namespace JackPrice\PhpGit\Meta;

use DateTime;
use JackPrice\PhpGit\Exceptions\InvalidDataException;

/**
 * Class Committer
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Committer
{
    private $raw = null;
    private $parts = null;

    public function __construct($raw)
    {
        $this->raw = $raw;
        $this->parts = $this->getParts();
    }

    public function getName()
    {
        return $this->parts['name'];
    }

    public function getEmail()
    {
        return $this->parts['email'];
    }

    public function getTime()
    {
        $timestamp = (int) $this->parts['timestamp'];
        $offset    = $this->parts['offset'];
        $sign      = $offset[0] == '-' ? -1 : 1;
        $hours     = (int) substr($offset, 1, 2);
        $minutes   = (int) substr($offset, 3, 2);
        $timestamp = $timestamp + $sign * (($hours * 3600) + ($minutes * 60));

        $time      = (new DateTime())->setTimestamp($timestamp);

        return new DateTime($time->format('Y-m-d H:i:s ' . $offset));
    }

    private function getParts()
    {
        if (preg_match('#^(.*) <(.*)> ([0-9]+) ([+-][0-9]{4})$#', $this->raw, $matches) !== 1) {
            throw new InvalidDataException('Committer does not parse');
        }

        return array(
            'name' => $matches[1],
            'email' => $matches[2],
            'timestamp' => $matches[3],
            'offset' => $matches[4],
        );
    }

    public static function create($name, $email, DateTime $time)
    {
        $timestamp = $time->getTimestamp();
        $timezone  = $time->getTimezone();
        $offset    = sprintf("%'04d", (int) $timezone->getOffset($time) / 3600);

        $data = "$name <$email> $timestamp $offset";

        return new Committer($data);
    }
}