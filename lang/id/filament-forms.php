<?php

return [

    'components' => [

        'builder' => [

            'actions' => [

                'add' => [
                    'label' => 'Tambah :label',
                ],

            ],

        ],

        'key_value' => [

            'actions' => [

                'add' => [
                    'label' => 'Tambah baris',
                ],

            ],

        ],

        'repeater' => [

            'actions' => [

                'add' => [
                    'label' => 'Tambah :label',
                ],

            ],

        ],

        'select' => [

            'actions' => [

                'create_option' => [
                    'modal' => [
                        'heading' => 'Tambah Data',
                        'actions' => [
                            'create' => [
                                'label' => 'Simpan',
                            ],
                        ],
                    ],
                ],

            ],

        ],

    ],

];
