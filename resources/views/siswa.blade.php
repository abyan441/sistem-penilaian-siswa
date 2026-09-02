@extends('layouts.app')

@section('title', 'Data Siswa | Cyber Olympus E-Raport System')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/siswa.css') }}">
@endpush

@section('content')
<section id="data-siswa" class="siswa-content" aria-labelledby="siswa-page-title">
    <div class="siswa-heading">
        <div class="siswa-heading-text">
            <h1 id="siswa-page-title">Data Siswa</h1>
            <p>Kelola data siswa Cyber Olympus</p>
        </div>

        <button class="siswa-add-button" id="siswa-add-button" type="button">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" />
            </svg>
            <span>Tambah Siswa</span>
        </button>
    </div>

    <section class="siswa-search-card" aria-label="Filter dan pencarian siswa" style="height:auto;">
        <form method="GET" action="{{ route('siswa') }}" style="display:flex;align-items:center;gap:12px;width:100%;margin-bottom:12px;">
            <label for="tahun-ajaran-filter" style="flex:0 0 auto;color:var(--primarypr-50);font-family:var(--label-l16-medium-font-family);font-size:var(--label-l16-medium-font-size);font-weight:var(--label-l16-medium-font-weight);">Tahun Ajaran</label>
            <div style="position:relative;flex:1;min-width:0;">
                <select id="tahun-ajaran-filter" name="tahun_ajaran" onchange="this.form.submit()" style="width:100%;height:41px;padding:0 38px 0 14px;border:1px solid var(--secondarysc-50);border-radius:12px;outline:none;background:var(--primarypr-00);color:var(--primarypr-50);font-family:var(--paragraph-p16-regular-font-family);font-size:var(--paragraph-p16-regular-font-size);box-sizing:border-box;appearance:none;cursor:pointer;">
                    <option value="semua" @selected($tahunAjaranTerpilih === null)>Semua Tahun Ajaran</option>
                    @foreach ($tahunAjaranOptions as $tahun)
                        <option value="{{ $tahun }}" @selected($tahunAjaranTerpilih === $tahun)>{{ $tahun }}</option>
                    @endforeach
                </select>
                <span style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid var(--primarypr-50);pointer-events:none;"></span>
            </div>
        </form>

        <form class="siswa-search-form" id="siswa-search-form" role="search">
            <label class="sr-only" for="student-search">Cari siswa</label>
            <input id="student-search" name="search" type="search" placeholder="Cari siswa berdasarkan NISN, Nama Siswa atau Kelas..." autocomplete="off">
            <button type="submit" aria-label="Cari siswa">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="2" />
                    <path d="M16 16l4 4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" />
                </svg>
            </button>
        </form>
    </section>

    <section class="siswa-table-card" aria-label="Daftar siswa">
        <div class="siswa-table-scroll">
            <div class="siswa-table" role="table" aria-label="Data siswa">
                <div class="siswa-table-header" role="row">
                    <div role="columnheader">No</div>
                    <div role="columnheader">NISN</div>
                    <div role="columnheader">Nama Siswa</div>
                    <div role="columnheader">Jenis Kelamin</div>
                    <div role="columnheader">Kelas</div>
                    <div role="columnheader">Aksi</div>
                </div>

                <div class="siswa-table-body" role="rowgroup">
                    @forelse ($siswa as $index => $item)
                        <div class="siswa-table-row" data-siswa-id="{{ $item->id }}" data-kelas-id="{{ $item->kelas_id }}" data-tahun-ajaran="{{ $item->kelas?->tahun_ajaran }}" role="row">
                            <div class="cell-no" role="cell">{{ $index + 1 }}</div>
                            <div class="cell-nisn" role="cell">{{ $item->nisn }}</div>
                            <div class="cell-nama" role="cell">{{ $item->nama_siswa }}</div>
                            <div class="cell-jk" role="cell">{{ $item->jenis_kelamin }}</div>
                            <div class="cell-kelas" role="cell">{{ $item->kelas?->nama_kelas ?? '-' }}</div>
                            <div class="siswa-actions" role="cell">
                                <button class="edit-btn" type="button" aria-label="Edit {{ $item->nama_siswa }}">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20h4l10.5-10.5a2.12 2.12 0 0 0-3-3L5 17v3z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="2" /><path d="M14.5 7.5l2 2" fill="none" stroke="currentColor" stroke-width="2" /></svg>
                                </button>
                                <button class="delete-btn" type="button" aria-label="Hapus {{ $item->nama_siswa }}">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="siswa-table-row siswa-empty-row" role="row">
                            <div class="cell-no" role="cell">-</div>
                            <div class="cell-nisn" role="cell">-</div>
                            <div class="cell-nama" role="cell">Belum ada data siswa pada tahun ajaran ini.</div>
                            <div class="cell-jk" role="cell">-</div>
                            <div class="cell-kelas" role="cell">-</div>
                            <div class="siswa-actions" role="cell">-</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</section>

<div class="siswa-modal" id="siswa-modal" hidden>
    <div class="siswa-modal-backdrop" data-siswa-modal-close></div>
    <section class="siswa-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="siswa-modal-title">
        <div class="siswa-modal-header">
            <div>
                <h2 id="siswa-modal-title">Tambah Data Siswa</h2>
                <p id="siswa-modal-desc">Lengkapi data siswa yang akan ditambahkan.</p>
            </div>
            <button class="siswa-modal-close" id="siswa-modal-close" type="button" aria-label="Tutup modal"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2" /></svg></button>
        </div>

        <form class="siswa-form" id="siswa-form">
            <div class="siswa-form-group">
                <label for="siswa-nisn">NISN</label>
                <input id="siswa-nisn" name="nisn" type="text" inputmode="numeric" maxlength="15" placeholder="Masukkan NISN" autocomplete="off" required>
                <small class="siswa-form-help">NISN dapat digunakan kembali pada tahun ajaran berbeda, tetapi tidak pada kelas yang sama.</small>
            </div>
            <div class="siswa-form-group">
                <label for="siswa-name">Nama Siswa</label>
                <input id="siswa-name" name="namaSiswa" type="text" maxlength="40" placeholder="Masukkan nama siswa" autocomplete="off" required>
            </div>
            <div class="siswa-form-group">
                <label for="siswa-jk">Jenis Kelamin</label>
                <div class="siswa-select-wrap"><select id="siswa-jk" name="jenisKelamin" required><option value="" disabled selected>Pilih jenis kelamin</option><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
            </div>
            <div class="siswa-form-group">
                <label for="siswa-tahun">Tahun Ajaran</label>
                <div class="siswa-select-wrap">
                    <select id="siswa-tahun" name="tahunAjaran">
                        <option value="" selected>Pilih tahun ajaran</option>
                        @foreach ($tahunAjaranOptions as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <small class="siswa-form-help">Pilih tahun ajaran terlebih dahulu agar daftar kelas sesuai.</small>
            </div>
            <div class="siswa-form-group">
                <label for="siswa-kelas">Kelas</label>
                <div class="siswa-select-wrap">
                    <select id="siswa-kelas" name="kelasId" required>
                        <option value="" disabled selected>Pilih kelas</option>
                        @foreach ($kelas as $itemKelas)
                            <option value="{{ $itemKelas->id }}" data-tahun="{{ $itemKelas->tahun_ajaran }}">{{ $itemKelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <small class="siswa-form-help">Kelas ditampilkan berdasarkan tahun ajaran yang dipilih.</small>
            </div>
            <div class="siswa-form-actions">
                <button class="siswa-form-cancel" id="siswa-form-cancel" type="button">Batal</button>
                <button class="siswa-form-submit" id="siswa-form-submit-btn" type="submit">Simpan Data Siswa</button>
            </div>
        </form>
    </section>
</div>

<div class="siswa-modal" id="delete-modal" hidden>
    <div class="siswa-modal-backdrop" data-delete-modal-close></div>
    <section class="siswa-modal-dialog delete-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="delete-modal-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14M9 7V4h6v3M8 10v8M12 10v8M16 10v8M6 7l1 14h10l1-14" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg></div>
        <h2 class="delete-modal-title" id="delete-modal-title">Hapus Data Siswa</h2>
        <p class="delete-modal-text">Apakah Anda yakin ingin menghapus data siswa <strong id="delete-student-name"></strong>? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="siswa-form-actions delete-actions">
            <button class="siswa-form-cancel" id="delete-form-cancel" type="button">Batal</button>
            <button class="siswa-form-delete-confirm" id="delete-form-confirm-btn" type="button">Ya, Hapus Data</button>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/siswa.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tahunSelect = document.getElementById('siswa-tahun');
    const kelasSelect = document.getElementById('siswa-kelas');
    if (!tahunSelect || !kelasSelect) return;

    const options = Array.from(kelasSelect.options).filter(option => option.value !== '').map(option => ({
        value: option.value,
        text: option.textContent.trim(),
        tahun: option.dataset.tahun || ''
    }));

    function rebuildKelas(selectedValue = '') {
        const tahun = tahunSelect.value;
        const filtered = tahun ? options.filter(option => option.tahun === tahun) : [];
        kelasSelect.innerHTML = '<option value="" disabled selected>Pilih kelas</option>';
        const seen = new Set();
        filtered.forEach(option => {
            if (seen.has(option.text)) return;
            seen.add(option.text);
            const element = document.createElement('option');
            element.value = option.value;
            element.textContent = option.text;
            element.dataset.tahun = option.tahun;
            kelasSelect.appendChild(element);
        });
        if (filtered.some(option => option.value === String(selectedValue))) kelasSelect.value = selectedValue;
    }

    tahunSelect.addEventListener('change', () => rebuildKelas());
    kelasSelect.addEventListener('change', function () {
        const selected = options.find(option => option.value === kelasSelect.value);
        if (selected && tahunSelect.value !== selected.tahun) tahunSelect.value = selected.tahun;
    });

    document.getElementById('siswa-add-button')?.addEventListener('click', function () {
        tahunSelect.value = '';
        rebuildKelas();
    });

    document.querySelector('.siswa-table-body')?.addEventListener('click', function (event) {
        const editButton = event.target.closest('.edit-btn');
        if (!editButton) return;
        const row = editButton.closest('.siswa-table-row');
        if (!row) return;
        setTimeout(function () {
            const selected = options.find(option => option.value === row.dataset.kelasId);
            if (!selected) return;
            tahunSelect.value = selected.tahun;
            rebuildKelas(row.dataset.kelasId);
        }, 0);
    });
});
</script>
@endpush
