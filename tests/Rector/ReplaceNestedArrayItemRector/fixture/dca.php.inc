<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_complex'] = [
            'config' => [
                'annoyingItem'     => 'JustTestingWildcardSupport',
                'dataContainer'    => 'Table',
                'ctable'           => ['tl_my_elements'],
                'switchToEdit'     => true,
                'enableVersioning' => true,
                'onload_callback'  => [
                    ['tl_complex', 'checkPermission'],
                ],
                'sql'              => [
                    'keys' => [
                        'id' => 'primary',
                    ],
                ],
            ],
            'fields' => [
                'screenshot' => [
                    'exclude' => true,
                    'inputType' => 'fileTree',
                    'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=> Config::get('validImageTypes')],
                    'sql' => "binary(16) NULL"
                ],
            ]
        ];
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_complex'] = [
            'config' => [
                'annoyingItem'     => 'JustTestingWildcardSupport',
                'dataContainer'    => \Contao\DC_Table::class,
                'ctable'           => ['tl_my_elements'],
                'switchToEdit'     => true,
                'enableVersioning' => true,
                'onload_callback'  => [
                    ['tl_complex', 'checkPermission'],
                ],
                'sql'              => [
                    'keys' => [
                        'id' => 'primary',
                    ],
                ],
            ],
            'fields' => [
                'screenshot' => [
                    'exclude' => true,
                    'inputType' => 'fileTree',
                    'eval' => ['fieldType'=>'radio', 'filesOnly'=>true, 'isGallery'=>true, 'extensions'=> Config::get('validImageTypes')],
                    'sql' => "binary(16) NULL"
                ],
            ]
        ];
    }
}
?>
