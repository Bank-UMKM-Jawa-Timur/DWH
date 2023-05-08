@if (session('status'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Berhasil!</strong> {{ session('status') }}
    <button type="button" class="close" data-dismiss="alert"  style="top: 0px !important;"aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Gagal!</strong> {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" style="top: 0px !important;" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif