@extends('layout.app')

@section('title')
    <span>Cek Resi</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Cek Resi</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-1">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Masukkan Nomor Resi" name="awb"
                                    aria-label="Recipient's username" aria-describedby="button-addon2" />
                                <button class="btn btn-outline-primary" id="button-cek" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <ul class="timeline mt-3 resi-found">
                        <li class="timeline-item">
                            <span class="timeline-point timeline-point-indicator"></span>
                            <div class="timeline-event">
                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                    <h6>12 Invoices have been paid</h6>
                                </div>
                                <p>12 Min Ago</p>

                            </div>
                        </li>
                    </ul>
                    {{-- invalid awb --}}
                    <div class="alert alert-danger mt-3 p-2 resi-not-found" role="alert">
                        Nomor Resi tidak ditemukan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $('.resi-not-found').hide()
        $('.resi-found').hide()
        $(document).ready(function() {
            $('#button-cek').on('click', function() {
                $.ajax({
                    url: "{{ url('/cek-resi/') }}" + '/' + $('input[name=awb]').val(),
                    type: 'GET',
                    success: function(response) {
                        if (response.length > 0) {
                            $('.resi-found').html('');
                            response.map(function(data) {
                                console.log(data)
                                $('.resi-found').append(`
                                    <li class="timeline-item">
                                        <span class="timeline-point timeline-point-indicator"></span>
                                        <div class="timeline-event">
                                            <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                <h6>${data.status}</h6>
                                            </div>
                                            <p>${data.created_at}</p>
                                        </div>
                                    </li>
                                `);
                            });
                            $('.resi-found').show();
                            $('.resi-not-found').hide();
                        } else {
                            $('.resi-found').html('');
                            $('.resi-not-found').show();
                        }
                    }
                });
            });
        });
    </script>
@endsection
