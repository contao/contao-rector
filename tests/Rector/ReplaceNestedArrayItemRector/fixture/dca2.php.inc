<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_bazbar'] = array
        (

            // Config
            'config' => array
            (
                'dataContainer'             => 'Table',
                'enableVersioning'          => false,
                'ptable'                    => '',
                'dynamicPtable'             => true,
                'onload_callback'           => array(),
                'sql' => array
                (
                    'keys' => array
                    (
                        'id' => 'primary',
                        'pid,store_id' => 'index',
                    )
                ),
            ),

            // List
            'list' => array
            (
                'sorting' => array
                (
                    'mode'                  => 4,
                    'headerFields'          => array('firstname','lastname', 'username'),
                    'disableGrouping'       => true,
                    'flag'                  => 1,
                    'panelLayout'           => 'filter;sort,search,limit',
                ),
                'global_operations' => array
                (
                    'all' => array
                    (
                        'href'              => 'act=select',
                        'class'             => 'header_edit_all',
                        'attributes'        => 'onclick="Backend.getScrollOffset();"'
                    )
                ),
                'operations' => array
                (
                    'edit' => array
                    (
                        'href'              => 'act=edit',
                        'icon'              => 'edit.svg'
                    ),
                    'copy' => array
                    (
                        'href'              => 'act=copy',
                        'icon'              => 'copy.svg'
                    ),
                    'delete' => array
                    (
                        'href'              => 'act=delete',
                        'icon'              => 'delete.svg',
                        'attributes'        => 'onclick="if (!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\')) return false; Backend.getScrollOffset();"'
                    ),
                    'show' => array
                    (
                        'href'              => 'act=show',
                        'icon'              => 'show.svg'
                    )
                )
            ),

            // Palettes
            'palettes' => array
            (
                'default'                   => '{store_legend},label,store_id;{personal_legend},gender,salutation,firstname,lastname,dateOfBirth,company,vat_no;{address_legend},street_1,street_2,street_3,street_number_1,street_number_2,street_number_3,postal,city,subdivision,country;{contact_legend},email,phone;{default_legend:hide},isDefaultBilling,isDefaultShipping',
            ),

            // Fields
            'fields' => array
            (
                'id' => array
                (
                    'sql'                 =>  "int(10) unsigned NOT NULL auto_increment",
                ),
                'pid' => array
                (
                    'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
                ),
                'tstamp' => array
                (
                    'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
                ),
                'ptable' => array
                (
                    'sql'                 =>  "varchar(64) NOT NULL default ''",
                ),
                'label' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'store_id' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'sorting'               => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>2, 'rgxp'=>'digit', 'tl_class'=>'w50'),
                    'sql'                   => "int(2) unsigned NOT NULL default '0'",
                ),
                'gender' => array
                (
                    'exclude'               => true,
                    'inputType'             => 'select',
                    'options'               => array('male', 'female', 'other'),
                    'reference'             => &$GLOBALS['TL_LANG']['MSC'],
                    'eval'                  => array('includeBlankOption'=>true, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(32) NOT NULL default ''",
                ),
                'salutation' => array
                (
                    'exclude'               => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'firstname' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'lastname' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'sorting'               => true,
                    'flag'                  => 1,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'dateOfBirth' => array
                (
                    'exclude'                 => true,
                    'inputType'               => 'text',
                    'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50 wizard'),
                    'sql'                     => "varchar(11) NOT NULL default ''"
                ),
                'company' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'sorting'               => true,
                    'flag'                  => 1,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>false, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'vat_no' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'street_1' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'street_2' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'street_3' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'postal' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>32, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'clr w50'),
                    'sql'                   => "varchar(32) NOT NULL default ''",
                ),
                'city' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'search'                => true,
                    'sorting'               => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'subdivision' => array
                (
                    'exclude'               => true,
                    'sorting'               => true,
                    'inputType'             => 'conditionalselect',
                    'eval'                  => array('feEditable'=>true, 'feGroup'=>'address', 'conditionField'=>'country', 'includeBlankOption'=>true, 'tl_class'=>'w50'),
                    'sql'                   => "varchar(10) NOT NULL default ''",
                ),
                'country' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'sorting'               => true,
                    'inputType'             => 'select',
                    'options'               => \Contao\System::getCountries(),
                    'eval'                  => array('mandatory'=>true, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50', 'chosen'=>true),
                    'sql'                   => "varchar(32) NOT NULL default ''",
                ),
                'phone' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>false, 'maxlength'=>64, 'rgxp'=>'phone', 'feEditable'=>true, 'feGroup'=>'contact', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(64) NOT NULL default ''",
                ),
                'email' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'email', 'feEditable'=>true, 'feGroup'=>'contact', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'isDefaultBilling' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'inputType'             => 'checkbox',
                    'eval'                  => array('feEditable'=>true, 'feGroup'=>'login', 'membersOnly'=>true, 'tl_class'=>'w50'),
                    'sql'                   => "char(1) NOT NULL default ''",
                ),
                'isDefaultShipping' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'inputType'             => 'checkbox',
                    'eval'                  => array('feEditable'=>true, 'feGroup'=>'login', 'membersOnly'=>true, 'tl_class'=>'w50'),
                    'sql'                   => "char(1) NOT NULL default ''",
                ),
            )
        );
    }
}
?>
-----
<?php

class Foo
{
    public function bar()
    {
        $GLOBALS['TL_DCA']['tl_bazbar'] = array
        (

            // Config
            'config' => array
            (
                'dataContainer'             => \Contao\DC_Table::class,
                'enableVersioning'          => false,
                'ptable'                    => '',
                'dynamicPtable'             => true,
                'onload_callback'           => array(),
                'sql' => array
                (
                    'keys' => array
                    (
                        'id' => 'primary',
                        'pid,store_id' => 'index',
                    )
                ),
            ),

            // List
            'list' => array
            (
                'sorting' => array
                (
                    'mode'                  => 4,
                    'headerFields'          => array('firstname','lastname', 'username'),
                    'disableGrouping'       => true,
                    'flag'                  => 1,
                    'panelLayout'           => 'filter;sort,search,limit',
                ),
                'global_operations' => array
                (
                    'all' => array
                    (
                        'href'              => 'act=select',
                        'class'             => 'header_edit_all',
                        'attributes'        => 'onclick="Backend.getScrollOffset();"'
                    )
                ),
                'operations' => array
                (
                    'edit' => array
                    (
                        'href'              => 'act=edit',
                        'icon'              => 'edit.svg'
                    ),
                    'copy' => array
                    (
                        'href'              => 'act=copy',
                        'icon'              => 'copy.svg'
                    ),
                    'delete' => array
                    (
                        'href'              => 'act=delete',
                        'icon'              => 'delete.svg',
                        'attributes'        => 'onclick="if (!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? '') . '\')) return false; Backend.getScrollOffset();"'
                    ),
                    'show' => array
                    (
                        'href'              => 'act=show',
                        'icon'              => 'show.svg'
                    )
                )
            ),

            // Palettes
            'palettes' => array
            (
                'default'                   => '{store_legend},label,store_id;{personal_legend},gender,salutation,firstname,lastname,dateOfBirth,company,vat_no;{address_legend},street_1,street_2,street_3,street_number_1,street_number_2,street_number_3,postal,city,subdivision,country;{contact_legend},email,phone;{default_legend:hide},isDefaultBilling,isDefaultShipping',
            ),

            // Fields
            'fields' => array
            (
                'id' => array
                (
                    'sql'                 =>  "int(10) unsigned NOT NULL auto_increment",
                ),
                'pid' => array
                (
                    'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
                ),
                'tstamp' => array
                (
                    'sql'                 =>  "int(10) unsigned NOT NULL default '0'",
                ),
                'ptable' => array
                (
                    'sql'                 =>  "varchar(64) NOT NULL default ''",
                ),
                'label' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'store_id' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'sorting'               => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>2, 'rgxp'=>'digit', 'tl_class'=>'w50'),
                    'sql'                   => "int(2) unsigned NOT NULL default '0'",
                ),
                'gender' => array
                (
                    'exclude'               => true,
                    'inputType'             => 'select',
                    'options'               => array('male', 'female', 'other'),
                    'reference'             => &$GLOBALS['TL_LANG']['MSC'],
                    'eval'                  => array('includeBlankOption'=>true, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(32) NOT NULL default ''",
                ),
                'salutation' => array
                (
                    'exclude'               => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'firstname' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'lastname' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'sorting'               => true,
                    'flag'                  => \Contao\DataContainer::SORT_INITIAL_LETTER_ASC,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'dateOfBirth' => array
                (
                    'exclude'                 => true,
                    'inputType'               => 'text',
                    'eval'                    => array('rgxp'=>'date', 'datepicker'=>true, 'feEditable'=>true, 'feViewable'=>true, 'feGroup'=>'personal', 'tl_class'=>'w50 wizard'),
                    'sql'                     => "varchar(11) NOT NULL default ''"
                ),
                'company' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'sorting'               => true,
                    'flag'                  => \Contao\DataContainer::SORT_INITIAL_LETTER_ASC,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>false, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'vat_no' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'street_1' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'street_2' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'street_3' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'postal' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>32, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'clr w50'),
                    'sql'                   => "varchar(32) NOT NULL default ''",
                ),
                'city' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'search'                => true,
                    'sorting'               => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>255, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'subdivision' => array
                (
                    'exclude'               => true,
                    'sorting'               => true,
                    'inputType'             => 'conditionalselect',
                    'eval'                  => array('feEditable'=>true, 'feGroup'=>'address', 'conditionField'=>'country', 'includeBlankOption'=>true, 'tl_class'=>'w50'),
                    'sql'                   => "varchar(10) NOT NULL default ''",
                ),
                'country' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'sorting'               => true,
                    'inputType'             => 'select',
                    'options'               => \Contao\System::getCountries(),
                    'eval'                  => array('mandatory'=>true, 'feEditable'=>true, 'feGroup'=>'address', 'tl_class'=>'w50', 'chosen'=>true),
                    'sql'                   => "varchar(32) NOT NULL default ''",
                ),
                'phone' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>false, 'maxlength'=>64, 'rgxp'=>'phone', 'feEditable'=>true, 'feGroup'=>'contact', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(64) NOT NULL default ''",
                ),
                'email' => array
                (
                    'exclude'               => true,
                    'search'                => true,
                    'inputType'             => 'text',
                    'eval'                  => array('mandatory'=>true, 'maxlength'=>64, 'rgxp'=>'email', 'feEditable'=>true, 'feGroup'=>'contact', 'tl_class'=>'w50'),
                    'sql'                   => "varchar(255) NOT NULL default ''",
                ),
                'isDefaultBilling' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'inputType'             => 'checkbox',
                    'eval'                  => array('feEditable'=>true, 'feGroup'=>'login', 'membersOnly'=>true, 'tl_class'=>'w50'),
                    'sql'                   => "char(1) NOT NULL default ''",
                ),
                'isDefaultShipping' => array
                (
                    'exclude'               => true,
                    'filter'                => true,
                    'inputType'             => 'checkbox',
                    'eval'                  => array('feEditable'=>true, 'feGroup'=>'login', 'membersOnly'=>true, 'tl_class'=>'w50'),
                    'sql'                   => "char(1) NOT NULL default ''",
                ),
            )
        );
    }
}
?>
