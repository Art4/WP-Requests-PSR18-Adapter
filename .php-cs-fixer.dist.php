<?php

$finder = (new PhpCsFixer\Finder())
    ->in('src')
    ->in('tests')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PHP70Migration' => true,
        '@PHP70Migration:risky' => true,
    ])
    ->setFinder($finder)
;