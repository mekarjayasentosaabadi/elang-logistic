<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

if (!function_exists('listMenu')) {
    function listMenu($role)
    {
        $data = [];
        if ($role == 1 || $role == 2) {
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
                    'url' => ['/user', '/outlet', '/customer', '/price'],
                    'child' => [
                        [
                            'title' => 'Pengguna',
                            'url' => '/user',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Destination',
                            'url' => '/destination',
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
                            'url' => '/masterprice',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Vehicle',
                            'url' => '/vehicle',
                            'icon' => 'circle'
                        ]
                    ]
                ],
                [
                    'title' => 'Transaksi',
                    'hasChild' => true,
                    'icon' => 'shopping-cart',
                    'url' => ['/order', '/manifest', '/delivery'],
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
                        // [
                        //     'title' => 'Surat Jalan',
                        //     'url' => '/delivery',
                        //     'icon' => 'circle'
                        // ],
                        [
                            'title' => 'Surat Tugas',
                            'url' => '/surattugas',
                            'icon' => 'circle'
                        ],
                        [
                            'title' => 'Update Resi',
                            'url' => '/update-resi',
                            'icon' => 'circle'

                        ]
                    ]
                ],
                [
                    'title' => 'Shipping Courier',
                    'url' => '/shipping-courier',
                    'hasChild' => false,
                    'icon' => 'truck'
                ],
                [
                    'title' => 'Cek Resi',
                    'url' => '/cek-resi',
                    'hasChild' => false,
                    'icon' => 'search'
                ],
                [
                    'title' => 'Laporan',
                    'url' => '/report',
                    'hasChild' => false,
                    'icon' => 'file'
                ],
                [
                    'title' => 'Profile',
                    'url' => 'profile',
                    'hasChild' => false,
                    'icon' => 'users'
                ]
            ];
        }
        if ($role == 3) {
            $data = [
                [
                    'title' => 'Shipping Courier',
                    'url' => '/shipping-courier',
                    'hasChild' => false,
                    'icon' => 'truck'
                ],
                [
                    'title' => 'Profile',
                    'url' => 'profile',
                    'hasChild' => false,
                    'icon' => 'users'
                ]
            ];
        }
        if ($role == 4) {
            $data = [
                [
                    'title' => 'Dashboard',
                    'url' => '/',
                    'hasChild' => false,
                    'icon' => 'home'
                ],
                [
                    'title' => 'Profile',
                    'url' => 'profile',
                    'hasChild' => false,
                    'icon' => 'users'
                ]
            ];
        }
        if ($role == 6) {
            $data = [
                [
                    'title' => 'Dashboard',
                    'url' => '/',
                    'hasChild' => false,
                    'icon' => 'home'
                ],
                [
                    'title' => 'Log Actifity',
                    'url' => '/logactifity',
                    'hasChild' => false,
                    'icon' => 'clock'
                ],
                [
                    'title' => 'Profile',
                    'url' => 'profile',
                    'hasChild' => false,
                    'icon' => 'users'
                ]
            ];
        }
        return $data;
    }
}

// role_id
if (!function_exists('role')) {
    function role($role_id)
    {
        $data = '';
        switch ($role_id) {
            case 1:
                $data = 'Superadmin';
                break;
            case 2:
                $data = 'Admin';
                break;
            case 3:
                $data = 'Courier';
                break;
            case 4:
                $data = 'Customer';
                break;
            case 5:
                $data = 'Driver';
                break;
            case 6:
                $data = 'Directur';
                break;
        }
        return $data;
    }
}

if (!function_exists('typeOutlet')) {
    function typeOutlet($type)
    {
        $data = '';
        switch ($type) {
            case 1:
                $data = 'Cabang';
                break;
            case 2:
                $data = 'Agen';
                break;
        }
        return $data;
    }
}

if (!function_exists('typeVehicle')) {
    function typeVehicle($type)
    {
        $data = '';
        switch ($type) {
            case 1:
                $data = "Truck Continer";
                break;
            case 2:
                $data = "Truck BOX";
                break;
            case 3:
                $data = "Truck Pickup";
                break;
        }
        return $data;
    }
}


if (!function_exists('armada')) {
    function armada($type)
    {
        $data = '';
        switch ($type) {
            case 1:
                $data = 'Darat';
                break;
            case 2:
                $data = 'Laut';
                break;
            case 3:
                $data = 'Udara';
                break;
        }
        return $data;
    }
}


if (!function_exists('cekUri')) {
    function cekUri($uri)
    {


        if ($uri == '/') {
            return '/';
        } else {
            return '/' . $uri;
        }
    }
}



if (!function_exists('status')) {
    function status($status)
    {
        $data = '';
        switch ($status) {
            case 1:
                $data = 'Pending';
                break;
            case 2:
                $data = 'Diproses';
                break;
            case 3:
                $data = 'Selasai';
                break;
            case 4:
                $data = 'Dibatalkan';
                break;
        }
        return $data;
    }
}

if (!function_exists('status_html')) {
    function status_html($status)
    {
        $data = '';
        switch ($status) {
            case 1:
                $data = '<span class="badge rounded-pill badge-light-warning">Pending</span>';
                break;
            case 2:
                $data = '<span class="badge rounded-pill badge-light-primary">Diproses</span>';
                break;
            case 3:
                $data = '<span class="badge rounded-pill badge-light-success">Selesai</span>';
                break;
            case 4:
                $data = '<span class="badge rounded-pill badge-light-danger">Dibatalkan</span>';
                break;
        }
        return $data;
    }
}

if (!function_exists('generateAwb')) {
    function generateAwb()
    {
        $last = Order::count();
        $data = 'EL' . str_pad($last + 1, 8, '0', STR_PAD_LEFT);
        return $data;
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

