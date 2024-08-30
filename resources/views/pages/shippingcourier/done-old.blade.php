    @extends('layout.app')
    @section('title')
        <span>Shipping Courier</span>
        <small>/</small>
        <small>done</small>
    @endsection

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Bukti pengiriman selesai</h4>
                        <a href="{{ url('/shipping-courier') }}" class="btn btn-warning">Kembali</a>
                    </div>
                    <form id="form-create-shipping-done"
                        action="{{ url('shipping-courier/' . Crypt::encrypt($detailShippingCourier->id) . '/storeShippingDone') }}"
                        method="post" enctype="multipart/form-data">
                        <div class="card-body mb-5">
                            @csrf
                            <input type="hidden" name="outlet_id" id="outlet_id_hidden">
                            <div id="hidden-inputs-container"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="penerima">Nama Penerima</label>
                                        <input type="text" name="penerima" id="penerima" class="form-control"
                                            placeholder="Masukan nama penerima"
                                            value="{{ $detailShippingCourier->order->penerima }}">
                                    </div>
                                    <div class="form-group mb-2">
                                        <div>
                                            <label for="bukti_diterima">Foto bukti pengiriman</label>
                                            <div id="my_camera"></div>
                                            <div id="my_result" class=" my-2"></div>
                                            <button type="button" id="capture" onclick="take_snapshot()"
                                                class="btn btn-primary">Ambil
                                                Foto</button>

                                            <input type="hidden" name="bukti_diterima" class="image-tag">
                                        </div>
                                    </div>
                                    {{-- preview --}}
                                    <div class="form-group mb-2">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan</label>
                                        <textarea name="note" rows="4" id="note" class="form-control" placeholder="masukan catatan">Pesanan telah diterima oleh yang bersangkutan.</textarea>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-primary btn-md float-end">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('custom-js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
        <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('#form-create-shipping-done').validate({
                    rules: {
                        'penerima': 'required',
                        'bukti_diterima': 'required',
                        'note': 'required',
                    },
                    messages: {
                        'penerima': "Nomor pengiriman harus diisi.",
                        'bukti_diterima': "Lampirkan gambar bukti di terima.",
                        'note': "Catatan harus diisi."
                    },
                })


                $('#bukti_diterima').change(function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });

            })
            Webcam.set({

                width: 300,

                height: 200,

                image_format: 'jpeg|png',


            });



            Webcam.attach('#my_camera');



            function take_snapshot() {

                Webcam.snap(function(data_uri) {
                    $(".image-tag").val(data_uri);
                    document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '" class="img-fluid" />';
                    // close camera
                    Webcam.reset();

                    $('#my_camera').hide();
                });

            }
        </script>
    @endsection
