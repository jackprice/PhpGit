# phpgit

PhpGit is a pure-php implementation of Git.

It's a work-in-progress at the moment - but for now, here are some examples.

```
<?php

use JackPrice\PhpGit\Repository;

$repository = new Repository('/path/to/repository');
$master     = $repository->getHead();
$hash       = $master->getHash();

echo "master is at {$hash}\n";
```