<?php

return [

    'single' => [

        'label' => 'Hapus permanen',

        'modal' => [

            'heading' => 'Hapus permanen :label',

            'actions' => [

                'delete' => [
                    'label' => 'Hapus',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Data berhasil dihapus',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Hapus permanen data yang dipilih',

        'modal' => [

            'heading' => 'Hapus permanen :label yang dipilih',

            'actions' => [

                'delete' => [
                    'label' => 'Hapus',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Data berhasil dihapus',
            ],

            'deleted_partial' => [
                'title' => 'Menghapus :count dari :total',
                'missing_authorization_failure_message' => 'Anda tidak mempunyai akses untuk menghapus :count.',
                'missing_processing_failure_message' => ':count tidak dapat dihapus.',
            ],

            'deleted_none' => [
                'title' => 'Gagal menghapus',
                'missing_authorization_failure_message' => 'Anda tidak mempunyai akses untuk menghapus :count.',
                'missing_processing_failure_message' => ':count tidak dapat dihapus.',
            ],

        ],

    ],

];
