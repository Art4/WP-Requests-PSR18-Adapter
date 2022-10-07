<?php

$finder = (new PhpCsFixer\Finder())
    ->in('src')
    ->in('tests')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        'void_return' => false,
        '@PHPUnit84Migration:risky' => true,
    ])
    ->setFinder($finder)
;
