@extends('layout.app')
@section('title')
    <span>Harga Public</span>
    <small>/</small>
    <small>Create</small>
@endsection
@section('content')
    @if (Auth::user()->role_id == '1')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Outlet Asal</h4>
                        <a href="{{ url('/masterprice') }}" class="btn btn-warning">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="outlet">Asal Outlet</label>
                            <select name="outlet" id="outlet" class="form-control outlet">
                                <option value="">-- Pilih Outlet --</option>
                                @foreach ($outlet as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Add Harga Public</h4>
                    @if (Auth::user()->role_id != 1)
                        <a href="{{ route('masterprice.index') }}" class="btn btn-warning"><i class="fa fa-undo"></i> Kembali</a>
                    @endif
                </div>
                <div class="card-body">
                    <form action="#" id="form-add-price">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group mt-1">
                                    <label for="armada">Service</label>
                                    <select name="armada" id="armada" class="form-control armada">
                                        <option value="">-- Pilih Armada --</option>
                                        <option value="1">Darat</option>
                                        <option value="2">Laut</option>
                                        <option value="3">Udara</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group mt-1 input-origin">
                                    <label for="origin_id">Origin</label>
                                    <select name="origin_id" id="origin_id" class="form-control select2 origin_id">
                                        <option value="">-- Pilih Origin --</option>
                                        @foreach ($destination as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-primary float-end btn-md"><i class="fa fa-pen"></i> Isi Daftar Harga</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card card-list-isiharga hidden">
                <div class="card-header">
                    <h4 class="card-title">Isi Detail List Harga</h4>
                </div>
                <div class="card-body">
                    <form action="/masterprice/store" method="post" id="form-price-darat" class="hidden">  
                        @csrf
                        <input type="hidden" name="outlet_id" class="hidden_outlet_id">
                        <input type="hidden" name="armada" class="hidden_armada">
                        <input type="hidden" name="origin_id" class="hidden_origin_id">
                        
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Destinasi</th>
                                            <th>10KG Pertama</th>
                                            <th>Harga Kilo Selanjutnya</th>
                                            <th>Estimasi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-isi-list-harga">
                                        
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary mt-3 ">Simpan</button>
                        </div>
                    </form>
                        <div class="p-1">
                            <form action="/masterprice/store" method="post" id="form-price-laut" class="hidden">  
                                @csrf
                                <input type="hidden" name="outlet_id" class="hidden_outlet_id">
                                <input type="hidden" name="armada" class="hidden_armada">
                                <input type="hidden" name="origin_id" class="hidden_origin_id">                       
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Destinasi</th>
                                                <th>Minimal Kilo</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl-isi-list-harga-laut">
                                            
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary mt-3 ">Simpan</button>
                                </form>
                        </div>
                        <div class="p-1">
                            <form action="/masterprice/store" method="post" id="form-price-udara" class="hidden">  
                                @csrf
                                <input type="hidden" name="outlet_id" class="hidden_outlet_id">
                                <input type="hidden" name="armada" class="hidden_armada">
                                <input type="hidden" name="origin_id" class="hidden_origin_id">                       
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Destinasi</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbl-isi-list-harga-udara">
                                            
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary mt-3 ">Simpan</button>
                                </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>

    <script>
        $('.select2').select2()

        function listPrice() {
            var outlet_id = $('#outlet').val()
            var armada    = $('#armada').val()
            var origin_id = $('#origin_id').val()
            
            if (outlet_id == "") {
                notifSweetAlertErrors('Gagal Pilih Salah Satu Outlet Terlebih Dahulu.!')
            }
            
            $.ajax({
                'url': "{{ url('/masterprice/getGetListPrice') }}",
                'type': 'POST',
                'data': {
                    'outlet_id': outlet_id,
                    'armada': armada,
                    'origin_id': origin_id
                },
                
                'success': function(data) {
                    if ($('#tbl-isi-list-harga tr').length > 0) {
                        $('#tbl-isi-list-harga').empty();
                    }


                    var outlet_id = $('.outlet').val();
                    var armada    = $('.armada').val();
                    var origin_id = $('.origin_id').val();


                    $('.hidden_outlet_id').val(outlet_id);
                    $('.hidden_armada').val(armada);
                    $('.hidden_origin_id').val(origin_id);


                    if (data.destination.length > 0) {
                        var rows = '';
                        if (data.armada == "1") {
                            $.each(data.destination, function(index, item) {
                                var destination_id = item.id;
                                var existingPrice = data.existingPrices.find(price => price.destinations_id == destination_id);
                                if (existingPrice &&  existingPrice.price != 0 && existingPrice.nextweightprices != 0 && existingPrice.estimation != 0) {
                                    rows += `
                                          <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.name}</td>
                                            <td>
                                                <input type="hidden" name="destination_id[]" id="destination_id" value="${item.id}" class="form-control">
                                                <input type="text" class="form-control" name="price_weight[]" id="price_weight" value="${existingPrice.price}"  placeholder="masukan harga kilo 10kg pertama" readonly>
                                                <small class="text-danger">Harga untuk data ini sudah tersedia</small>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="next_weight_price[]" id="next_weight_price" placeholder="masukan harga kilo selanjutnya" value="${existingPrice.nextweightprices}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="estimation[]" id="estimation" placeholder="masukan estimasi" value="${existingPrice.estimation}" readonly>
                                            </td>
                                        </tr>
                                    `
                                } else{
                                        rows += `
                                            <tr>
                                                <td>${index + 1}</td>
                                                <td>${item.name}</td>
                                                <td>
                                                    <input type="hidden" name="destination_id[]" id="destination_id" value="${item.id}" class="form-control">
                                                    <input type="text" class="form-control" name="price_weight[]" id="price_weight" placeholder="masukan harga kilo 10kg pertama">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="next_weight_price[]" id="next_weight_price" placeholder="masukan harga kilo selanjutnya">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="estimation[]" id="estimation" placeholder="masukan estimasi">
                                                </td>
                                            </tr>`;
                                    }
                                });


                                $('#tbl-isi-list-harga').append(rows);
                                $('.card-list-isiharga').removeClass('hidden');
                                $('#form-price-darat').removeClass('hidden');
                        }
                        else if(data.armada == "2") {
                            $.each(data.destination, function(index, item) {
                                var destination_id = item.id;
                                var existingPrice = data.existingPrices.find(price => price.destinations_id == destination_id);
                                if (existingPrice &&  existingPrice.price != 0 && existingPrice.minimumweight != 0) {
                                    rows += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.name}</td>
                                        <td>
                                            <input type="hidden" name="destination_id[]" id="destination_id" value="${item.id}" class="form-control" readonly>
                                            <input type="text" class="form-control" name="weight[]" id="weight" value="${existingPrice.minweights}" placeholder="masukan minimal kilo" readonly>
                                            <small class="text-danger">Harga untuk data ini sudah tersedia</small>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="price_weight[]" id="price_weight" value="${existingPrice.price}" placeholder="masukan harga" readonly>
                                        </td>
                                    </tr>`;
                                }
                               else{
                                    rows += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.name}</td>
                                            <td>
                                                <input type="hidden" name="destination_id[]" id="destination_id" value="${item.id}" class="form-control">
                                                <input type="text" class="form-control" name="weight[]" id="weight" placeholder="masukan minimal kilo">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="price_weight[]" id="price_weight" placeholder="masukan harga">
                                            </td>
                                        </tr>`;
                               }
                            });
                                $('#tbl-isi-list-harga-laut').append(rows);
                                $('.card-list-isiharga').removeClass('hidden');
                                $('#form-price-laut').removeClass('hidden');
                        }
                        else if(data.armada == "3") {
                            $.each(data.destination, function(index, item) {
                                var destination_id = item.id;
                                var existingPrice = data.existingPrices.find(price => price.destinations_id == destination_id);

                                if (existingPrice &&  existingPrice.price != 0) {
                                    rows += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.name}</td>
                                            <td>
                                                <input type="hidden" name="destination_id[]" id="destination_id" value="${item.id}" class="form-control">
                                                <input type="text" class="form-control" name="price_weight[]" value="${existingPrice.price}"  id="price" placeholder="masukan harga" readonly>
                                                <small class="text-danger">Harga untuk data ini sudah tersedia</small>
                                            </td>
                                        </tr>`;
                                }else{
                                    rows += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.name}</td>
                                            <td>
                                                <input type="hidden" name="destination_id[]" id="destination_id" value="${item.id}" class="form-control">
                                                <input type="text" class="form-control" name="price_weight[]"  id="price" placeholder="masukan harga">
                                            </td>
                                        </tr>`;
                               }



                                });
                                $('#tbl-isi-list-harga-udara').append(rows);
                                $('.card-list-isiharga').removeClass('hidden');
                                $('#form-price-udara').removeClass('hidden');
                        }
                        
                    } else {
                        $('#tbl-isi-list-harga').append('<tr><td colspan="4">Data tidak ditemukan</td></tr>');
                    }
                    
                },

                'error': function(data) {
                    console.log(data)
                }
            })
        }
        $('#form-add-price').on('submit', function(e) {
            e.preventDefault(); 
            listPrice();
        });


        $('#form-add-price').validate({
            rules:{
                'armada'    : 'required',
                'origin_id' : 'required'
            },

            messages:{
                'armada'    : 'Pilih armada terlebih dahulu',
                'origin_id' : 'Pilih origin terlebih dahulu'
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "origin_id") {
                    error.insertAfter(".input-origin");
                } else {
                    error.insertAfter(element); 
                }
            }


        })
    </script>
@endsection
