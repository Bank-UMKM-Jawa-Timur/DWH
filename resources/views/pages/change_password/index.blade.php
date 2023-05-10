@extends('layout.master')

@section('title', $title)

@section('content')

    <div class="panel-header">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-primary pb-2 fw-bold">{{ $pageTitle }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        @include('components.alert')
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('update_password') }}" id="modal-form">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="passwordLama">
                                            <label for="old_password">Password Lama</label>
                                            <input autofocus type="password"
                                                class="form-control @if ($errors->has('old_password')) is-invalid @endif"
                                                id="old_password" name="old_password" required>
                                            @if ($errors->has('old_password'))
                                                <small
                                                    class="form-text text-danger error">{{ $errors->first('old_password') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="passwordBaru">
                                            <label for="password">Password Baru</label>
                                            <input autofocus type="password"
                                                class="form-control @if ($errors->has('password')) is-invalid @endif"
                                                id="password" name="password" required>
                                            @if ($errors->has('password'))
                                                <small
                                                    class="form-text text-danger error">{{ $errors->first('password') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="konfirmasiPassword">
                                            <label for="password_confirmation">Konfirmasi Password</label>
                                            <input autofocus type="password"
                                                class="form-control @if ($errors->has('password_confirmation')) is-invalid @endif"
                                                id="password_confirmation" name="password_confirmation" required>
                                            @if ($errors->has('password_confirmation'))
                                                <small
                                                    class="form-text text-danger error">{{ $errors->first('password_confirmation') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
