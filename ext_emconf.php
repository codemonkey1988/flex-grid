<?php

$EM_CONF[$_EXTKEY] = array(
    'title'            => 'Flexible grid',
    'description'      => 'Adds a new flexible grid element based on flux.',
    'category'         => 'frontend',
    'constraints'      => array(
        'depends'   => array(
            'php'   => '7.0.0-7.1.99',
            'typo3' => '7.6.0-7.6.99',
            'flux'  => '8.0.0-8.0.99',
            'vhs'   => '4.1.0-4.9.99'
        ),
        'conflicts' => array(),
    ),
    'state'            => 'experimental',
    'uploadfolder'     => false,
    'createDirs'       => '',
    'clearCacheOnLoad' => true,
    'author'           => 'Tim Schreiner',
    'author_email'     => 'schreiner.tim@gmail.com',
    'author_company'   => '',
    'version'          => '0.0.1-dev'
);