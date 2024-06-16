<?php

if (!function_exists('listMenu')) {
    function listMenu($role)
    {
        $data = [];
        if ($role == 'superadmin') {
            $data = [
                [
                    'title' => 'Dashboard',
                    'url' => '/',
                    'hasChild' => false,
                    'icon' => 'home'
                ],
                [
                    'title' => 'Master Data',
                    'hasChild' => true,
                    'icon' => 'database',
                    'child' => [
                        [
                            'title' => 'User',
                            'url' => '/user',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Outlet',
                            'url' => '/outlet',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Customer',
                            'url' => '/customer',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Harga Public',
                            'url' => '/price',
                            'icon' => 'circle'
                        ]
                    ]
                ],
                [
                    'title' => 'Transaksi',
                    'hasChild' => true,
                    'icon' => 'shopping-cart',
                    'child' => [
                        [
                            'title' => 'Order',
                            'url' => '/order',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Manifest',
                            'url' => '/manifest',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Surat Jalan',
                            'url' => '/delivery',
                            'icon' => 'circle'
                        ]
                    ]
                ],
                [
                    'title' => 'Cek Resi',
                    'url' => '/cek-resi',
                    'hasChild' => false,
                    'icon' => 'search'
                ],
                [
                    'title' => 'Laporan',
                    'url' => 'Report',
                    'hasChild' => false,
                    'icon' => 'file'
                ]
            ];
        }
        return $data;
    }
}
