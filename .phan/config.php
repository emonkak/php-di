<?php

return [
    'directory_list' => [
        'src',
        'vendor/composer',
        'vendor/container-interop/container-interop',
        'vendor/doctrine/annotations',
        'vendor/jeremeamia/SuperClosure',
    ],

    'exclude_analysis_directory_list' => [
        'vendor/',
    ],

    'analyze_signature_compatibility' => false,
];
