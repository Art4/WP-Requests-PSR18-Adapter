<?php

$finder = (new PhpCsFixer\Finder())
    ->in('src')
    ->in('tests')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS2.0' => true,
        '@PER-CS2.0:risky' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHPUnit84Migration:risky' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'void_return' => false,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;
