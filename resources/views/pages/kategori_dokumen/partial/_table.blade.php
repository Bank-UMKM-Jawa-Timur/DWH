<div class="tables mt-2">
    <table class="table-auto w-full" id="basic-datatables">
        <thead>
            <tr class="">
                <th>No</th>
                <th>Nama Dokumen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>
                        @if ($item->id > 6)
                            <div class="dropdown max-w-[280px]">
                                <button class="px-4 py-2 bg-theme-btn/10 rounded text-theme-btn">
                                    Selengkapnya
                                </button>
                                <ul class="dropdown-menu">
                                    <li class="">
                                        <a class="item-dropdown toggle-modal" data-toggle="modal" data-target-id="edit-kd" data-target="#edit-kd" href="#" data-id="{{ $item->id }}" data-name="{{ $item->name }}">Edit</a>
                                    </li>
                                    <li class="">
                                        <a class="item-dropdown deleteModal" href="#" data-id="{{ $item->id }}">Hapus</a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <span class="text-danger">Maaf data belum tersedia.</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
