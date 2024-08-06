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
                <form id="form-create-shipping-done" action="{{ url('shipping-courier/'.Crypt::encrypt($detailShippingCourier->id).'/storeShippingDone') }}" method="post" enctype="multipart/form-data">
                    <div class="card-body mb-5">
                        @csrf
                        <input type="hidden" name="outlet_id" id="outlet_id_hidden">
                        <div id="hidden-inputs-container"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="penerima">Nama Penerima</label>
                                    <input type="text" name="penerima" id="penerima" class="form-control"
                                        placeholder="Masukan nama penerima" value="{{ $detailShippingCourier->order->penerima }}">
                                </div>
                                <div class="form-group mb-2">
                                    <div>
                                        <label for="bukti_diterima">Foto bukti pengiriman</label>
                                        <input name="bukti_diterima" class="form-control" type="file" id="bukti_diterima">
                                    </div>
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
        <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
        <script>
            $(document).ready(function () {
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
            })
        </script>
    @endsection
