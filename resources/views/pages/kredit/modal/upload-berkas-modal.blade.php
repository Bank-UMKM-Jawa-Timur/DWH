<div class="modal fade" id="uploadBerkasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="modal-berkas" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_kkb" id="id_kkb">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="stnk-tab" data-toggle="tab" href="#stnk"
                                role="tab" aria-controls="stnk" aria-selected="true">STNK</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="polis-tab" data-toggle="tab" href="#polis" role="tab"
                                aria-controls="polis" aria-selected="false">Polis</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="bpkb-tab" data-toggle="tab" href="#bpkb" role="tab"
                                aria-controls="bpkb" aria-selected="false">BPKB</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        {{--  STNK  --}}
                        <div class="tab-pane fade show active" id="stnk" role="tabpanel"
                            aria-labelledby="stnk-tab">
                            <input type="hidden" name="id_stnk" id="id_stnk">
                            <div class="form-group">
                                <label>Nomor</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="no_stnk" name="no_stnk" @if (Auth::user()->role_id == 2) readonly @endif>
                                </div>
                                <small class="form-text text-danger error"></small>
                            </div>
                            <iframe id="preview_stnk" src="" width="100%" height="450px"></iframe>
                            @if (Auth::user()->role_id == 3)
                                <div class="form-group">
                                    <label>Scan Berkas (pdf)</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="stnk_scan" name="stnk_scan"
                                            accept="application/pdf">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-file"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            @endif
                        </div>
                        {{--  Polis  --}}
                        <div class="tab-pane fade" id="polis" role="tabpanel" aria-labelledby="polis-tab">
                            <input type="hidden" name="id_polis" id="id_polis">
                            <div class="form-group">
                                <label>Nomor</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="no_polis" name="no_polis" @if (Auth::user()->role_id == 2) readonly @endif>
                                </div>
                                <small class="form-text text-danger error"></small>
                            </div>
                            <iframe id="preview_polis" src="" width="100%" height="450px"></iframe>
                            @if (Auth::user()->role_id == 3)
                                <div class="form-group">
                                    <label>Scan Berkas (pdf)</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="polis_scan" name="polis_scan"
                                            accept="application/pdf">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-file"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            @endif
                        </div>
                        {{--  BPKB  --}}
                        <div class="tab-pane fade" id="bpkb" role="tabpanel" aria-labelledby="bpkb-tab">
                            <input type="hidden" name="id_bpkb" id="id_bpkb">
                            <div class="form-group">
                                <label>Nomor</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="no_bpkb" name="no_bpkb" @if (Auth::user()->role_id == 2) readonly @endif>
                                </div>
                                <small class="form-text text-danger error"></small>
                            </div>
                            <iframe id="preview_bpkb" src="" width="100%" height="450px"></iframe>
                            @if (Auth::user()->role_id == 3)
                                <div class="form-group">
                                    <label>Scan Berkas (pdf)</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="bpkb_scan" name="bpkb_scan"
                                            accept="application/pdf">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="fa fa-file"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="form-text text-danger error"></small>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            @if (Auth::user()->role_id == 2)
                                Konfirmasi
                            @endif
                            @if (Auth::user()->role_id == 3)
                                Kirim
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>