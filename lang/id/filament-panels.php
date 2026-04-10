<?php

return [

    'resources' => [

        'pages' => [

            'create-record' => [

                'title' => 'Tambah :label',

                'breadcrumb' => 'Tambah',

                'form' => [

                    'actions' => [

                        'cancel' => [
                            'label' => 'Batal',
                        ],

                        'create' => [
                            'label' => 'Simpan',
                        ],

                        'create_another' => [
                            'label' => 'Simpan & Tambah Lagi',
                        ],

                    ],

                ],

            ],

            'edit-record' => [

                'title' => 'Ubah :label',

                'breadcrumb' => 'Ubah',

                'form' => [

                    'actions' => [

                        'cancel' => [
                            'label' => 'Batal',
                        ],

                        'save' => [
                            'label' => 'Simpan',
                        ],

                    ],

                ],

            ],

        ],

    ],

];
