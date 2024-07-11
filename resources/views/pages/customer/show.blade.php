@extends('layout.app')

@section('title')
    <span>Customer</span>
    <small>/</small>
    <small>Detail</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Customer</h4>
                    <a href="{{ route('customer.index') }}" class="btn btn-warning"> Kembali </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12">
                            <img src="{{ Storage::url('customer/'.$customer->pictures) }}" alt="" class="img-thumnail">
                        </div>
                        <div class="col-md-10 col-lg-10 col-sm-12">
                            <table class="table ">
                                <tr>
                                    <th width="20%">Nama</th>
                                    <td>: {{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>: {{ $customer->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Harga Customer</h4>
                </div>
                {{-- <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <ul class="nav nav-pills flex-column">
                                @foreach ($customer_prices as $customer_price)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                            id="stacked-pill-{{ $loop->iteration }}" data-bs-toggle="pill"
                                            href="#vertical-pill-{{ $loop->iteration }}" aria-expanded="true">
                                            {{ $customer_price['outlet'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-9 col-sm-12">
                            <div class="tab-content">
                                @foreach ($customer_prices as $i => $customer_price)
                                    <div role="tabpanel" class="tab-pane {{ $loop->first ? 'active' : '' }}"
                                        id="vertical-pill-{{ $loop->iteration }}"
                                        aria-labelledby="stacked-pill-{{ $loop->iteration }}" aria-expanded="true">
                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach ($customer_price['prices'] as $j => $armada)
                                                <li class="nav-item">
                                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                        id="tab-{{ $i . $j }}" data-bs-toggle="tab"
                                                        href="#home-{{ $i . $j }}"
                                                        aria-controls="home-{{ $i . $j }}" role="tab"
                                                        aria-selected="true">{{ armada($j) }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @foreach ($customer_price['prices'] as $j => $armada)
                                                <div class="tab-pane {{ $loop->first ? 'active' : '' }}"
                                                    id="home-{{ $i . $j }}"
                                                    aria-labelledby="tab-{{ $i . $j }}" role="tabpanel">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Destinasi</th>
                                                                <th>Harga</th>
                                                                <th>Estimasi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($armada as $k => $price)
                                                                <tr>
                                                                    <td>{{ $k + 1 }}</td>
                                                                    <td>{{ $price->destination->name }}</td>
                                                                    <td>{{ $price['price'] }}</td>
                                                                    <td>{{ $price['estimation'] }} hari</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
