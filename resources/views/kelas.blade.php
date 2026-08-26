@extends('layouts.app')

@section('title', 'Data Kelas | Cyber Olympus E-Raport System')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/kelas.css') }}">
@endpush

@section('content')
<main aria-labelledby="page-title" class="kelas-content" id="data-kelas">
        <header class="k-div">
          <div class="k-div-2">
            <h1 aria-label="Data Kelas" class="k-text-wrapper" id="page-title">
              Data Kelas
            </h1>
            <p class="k-page-subtitle">
              Kelola data kelas dan wali kelas.
            </p>
          </div>
          <button aria-label="Tambah kelas baru" class="k-button-tambah-siswa" id="kelas-add-button" type="button">
            <span aria-hidden="true" class="k-ic-round-plus">
              <svg aria-hidden="true" class="icon-svg k-vector" focusable="false" viewbox="0 0 24 24">
                <path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-linecap="round"
                  stroke-linejoin="round" stroke-width="2"></path>
              </svg>
            </span>
            <span class="k-tambah-siswa">Tambah Kelas</span>
          </button>
        </header>
        <section aria-label="Ringkasan data kelas" class="k-div-3">
          <article class="k-div-4">
            <div class="k-div-5">
              <p class="k-text-wrapper-3">Total Kelas</p>
              <p class="k-text-wrapper-4">15</p>
            </div>
            <span aria-hidden="true" class="k-radix-icons-people summary-icon-box summary-icon-total-kelas"><svg
                aria-hidden="true" class="summary-icon-svg summary-icon-group" focusable="false" viewBox="0 0 24 20"
                role="img">
                <circle cx="9" cy="7.5" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>
                <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2"></path>
                <path d="M16 5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor"
                  stroke-width="2"></path>
              </svg></span>
          </article>
          <article class="k-div-4">
            <div class="k-div-5">
              <p class="k-text-wrapper-3">Total Siswa</p>
              <p class="k-text-wrapper-4">447</p>
            </div>
            <span aria-hidden="true" class="k-griddy-icons-user-box summary-icon-box summary-icon-total-siswa"><svg
                aria-hidden="true" class="summary-icon-svg summary-icon-single" focusable="false" viewBox="0 0 20 20"
                role="img">
                <circle cx="10" cy="7.5" r="3.5" fill="none" stroke="currentColor" stroke-width="2"></circle>
                <path d="M3 20c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>
              </svg></span>
          </article>
          <article class="k-div-4">
            <div class="k-div-5">
              <p class="k-text-wrapper-3">Rata rata perkelas</p>
              <p class="k-text-wrapper-4">30</p>
            </div>
            <span aria-hidden="true" class="k-vector-wrapper summary-icon-box summary-icon-average"><svg
                aria-hidden="true" class="summary-icon-svg summary-icon-group" focusable="false" viewBox="0 0 24 20"
                role="img">
                <circle cx="9" cy="7.5" r="3" fill="none" stroke="currentColor" stroke-width="2"></circle>
                <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2"></path>
                <path d="M16 5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor"
                  stroke-width="2"></path>
              </svg></span>
          </article>
        </section>
        <!-- TINGKAT 1 -->
        <section aria-labelledby="tingkat-1" class="k-div-6">
          <h2 class="k-text-wrapper-5" id="tingkat-1">Tingkat 1</h2>
          <div class="k-div-7">
            <article aria-labelledby="kelas-1a" class="k-div-8" data-kelas-id="1" data-kelas-name="1A" data-siswa="28"
              data-tahun="2025/2026" data-wali="Pak Fuang">
              <div class="k-div-9">
                <div class="k-div-10">
                  <div class="k-div-11">
                    <div class="k-div-12">
                      <h3 class="k-text-wrapper-5" id="kelas-1a">Kelas 1A</h3>
                      <p class="k-text-wrapper-2">Tahun Ajaran 2025/2026</p>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="12" cy="8" fill="none" r="3.5" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Wali Kelas</p>
                        <p class="k-text-wrapper-2">Pak Fuang</p>
                      </div>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="9" cy="8" fill="none" r="3" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2">
                        </path>
                        <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor"
                          stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Jumlah Siswa</p>
                        <p class="k-text-wrapper-2">28 Siswa</p>
                      </div>
                    </div>
                  </div>
                  <span aria-label="Tingkat 1" class="k-div-wrapper">
                    <span class="k-text-wrapper-7">1</span>
                  </span>
                </div>
                <span aria-hidden="true" class="k-line"></span>
              </div>
              <nav aria-label="Aksi Kelas 1A" class="k-div-14">
                <a aria-label="Lihat detail Kelas 1A" class="k-text-wrapper-8 kelas-detail-btn" href="#">Detail</a>
                <button aria-label="Edit 1A" class="k-text-wrapper-9 kelas-edit-btn" type="button">Edit</button></nav>
            </article>
            <article aria-labelledby="kelas-1b" class="k-div-8" data-kelas-id="2" data-kelas-name="1B" data-siswa="30"
              data-tahun="2025/2026" data-wali="Pak Fauzi">
              <div class="k-div-9">
                <div class="k-div-10">
                  <div class="k-div-11">
                    <div class="k-div-12">
                      <h3 class="k-text-wrapper-5" id="kelas-1b">Kelas 1B</h3>
                      <p class="k-text-wrapper-2">Tahun Ajaran 2025/2026</p>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="12" cy="8" fill="none" r="3.5" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Wali Kelas</p>
                        <p class="k-text-wrapper-2">Pak Fauzi</p>
                      </div>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="9" cy="8" fill="none" r="3" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2">
                        </path>
                        <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor"
                          stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Jumlah Siswa</p>
                        <p class="k-text-wrapper-2">30 Siswa</p>
                      </div>
                    </div>
                  </div>
                  <span aria-label="Tingkat 1" class="k-div-wrapper">
                    <span class="k-text-wrapper-7">1</span>
                  </span>
                </div>
                <span aria-hidden="true" class="k-line"></span>
              </div>
              <nav aria-label="Aksi Kelas 1B" class="k-div-14">
                <a aria-label="Lihat detail Kelas 1B" class="k-text-wrapper-8 kelas-detail-btn" href="#">Detail</a>
                <button aria-label="Edit 1B" class="k-text-wrapper-9 kelas-edit-btn" type="button">Edit</button></nav>
            </article>
          </div>
        </section>
        <!-- TINGKAT 2 -->
        <section aria-labelledby="tingkat-2" class="k-div-6">
          <h2 class="k-text-wrapper-5" id="tingkat-2">Tingkat 2</h2>
          <div class="k-div-15">
            <article aria-labelledby="kelas-2a" class="k-div-16" data-kelas-id="3" data-kelas-name="2A" data-siswa="30"
              data-tahun="2025/2026" data-wali="Pak Ipul Gaming">
              <div class="k-div-9">
                <div class="k-div-10">
                  <div class="k-div-11">
                    <div class="k-div-12">
                      <h3 class="k-text-wrapper-5" id="kelas-2a">Kelas 2A</h3>
                      <p class="k-text-wrapper-2">Tahun Ajaran 2025/2026</p>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="12" cy="8" fill="none" r="3.5" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Wali Kelas</p>
                        <p class="k-text-wrapper-2">Pak Ipul Gaming</p>
                      </div>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="9" cy="8" fill="none" r="3" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2">
                        </path>
                        <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor"
                          stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Jumlah Siswa</p>
                        <p class="k-text-wrapper-2">30 Siswa</p>
                      </div>
                    </div>
                  </div>
                  <span aria-label="Tingkat 2" class="k-div-wrapper">
                    <span class="k-text-wrapper-7">2</span>
                  </span>
                </div>
                <span aria-hidden="true" class="k-line"></span>
              </div>
              <nav aria-label="Aksi Kelas 2A" class="k-div-14">
                <a aria-label="Lihat detail Kelas 2A" class="k-text-wrapper-8 kelas-detail-btn" href="#">Detail</a>
                <button aria-label="Edit 2A" class="k-text-wrapper-9 kelas-edit-btn" type="button">Edit</button></nav>
            </article>
            <article aria-labelledby="kelas-2b" class="k-div-16" data-kelas-id="4" data-kelas-name="2B" data-siswa="27"
              data-tahun="2025/2026" data-wali="Pak Ramus">
              <div class="k-div-9">
                <div class="k-div-10">
                  <div class="k-div-11">
                    <div class="k-div-12">
                      <h3 class="k-text-wrapper-5" id="kelas-2b">Kelas 2B</h3>
                      <p class="k-text-wrapper-2">Tahun Ajaran 2025/2026</p>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="12" cy="8" fill="none" r="3.5" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Wali Kelas</p>
                        <p class="k-text-wrapper-2">Pak Ramus</p>
                      </div>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="9" cy="8" fill="none" r="3" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2">
                        </path>
                        <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor"
                          stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Jumlah Siswa</p>
                        <p class="k-text-wrapper-2">27 Siswa</p>
                      </div>
                    </div>
                  </div>
                  <span aria-label="Tingkat 2" class="k-div-wrapper">
                    <span class="k-text-wrapper-7">2</span>
                  </span>
                </div>
                <span aria-hidden="true" class="k-line"></span>
              </div>
              <nav aria-label="Aksi Kelas 2B" class="k-div-14">
                <a aria-label="Lihat detail Kelas 2B" class="k-text-wrapper-8 kelas-detail-btn" href="#">Detail</a>
                <button aria-label="Edit 2B" class="k-text-wrapper-9 kelas-edit-btn" type="button">Edit</button></nav>
            </article>
            <article aria-labelledby="kelas-2c" class="k-div-16" data-kelas-id="5" data-kelas-name="2C" data-siswa="29"
              data-tahun="2025/2026" data-wali="Pak Rama">
              <div class="k-div-9">
                <div class="k-div-10">
                  <div class="k-div-11">
                    <div class="k-div-12">
                      <h3 class="k-text-wrapper-5" id="kelas-2c">Kelas 2C</h3>
                      <p class="k-text-wrapper-2">Tahun Ajaran 2025/2026</p>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="12" cy="8" fill="none" r="3.5" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M5 21c0-4 3.1-7 7-7s7 3 7 7" fill="none" stroke="currentColor" stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Wali Kelas</p>
                        <p class="k-text-wrapper-2">Pak Rama</p>
                      </div>
                    </div>
                    <div class="k-div-13">
                      <svg aria-hidden="true" class="icon-svg k-img-2" focusable="false" viewbox="0 0 24 24">
                        <circle cx="9" cy="8" fill="none" r="3" stroke="currentColor" stroke-width="2"></circle>
                        <path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6" fill="none" stroke="currentColor" stroke-width="2">
                        </path>
                        <path d="M16 5.5a3 3 0 0 1 0 5.8M17 14.5a5.5 5.5 0 0 1 4 5.5" fill="none" stroke="currentColor"
                          stroke-width="2"></path>
                      </svg>
                      <div class="k-div-2">
                        <p class="k-text-wrapper-6">Jumlah Siswa</p>
                        <p class="k-text-wrapper-2">29 Siswa</p>
                      </div>
                    </div>
                  </div>
                  <span aria-label="Tingkat 2" class="k-div-wrapper">
                    <span class="k-text-wrapper-7">2</span>
                  </span>
                </div>
                <span aria-hidden="true" class="k-line"></span>
              </div>
              <nav aria-label="Aksi Kelas 2C" class="k-div-14">
                <a aria-label="Lihat detail Kelas 2C" class="k-text-wrapper-8 kelas-detail-btn" href="#">Detail</a>
                <button aria-label="Edit 2C" class="k-text-wrapper-9 kelas-edit-btn" type="button">Edit</button></nav>
            </article>
          </div>
        </section>
      </main>

<div class="kelas-modal" hidden id="kelas-modal">
    <div class="kelas-modal-backdrop" data-kelas-modal-close></div>
    <section aria-labelledby="kelas-modal-title" aria-modal="true" class="kelas-modal-dialog" role="dialog">
      <div class="kelas-modal-header">
        <div>
          <h2 id="kelas-modal-title">Tambah Data Kelas</h2>
          <p id="kelas-modal-desc">Lengkapi data kelas yang akan ditambahkan.</p>
        </div>
        <button aria-label="Tutup modal" class="kelas-modal-close" id="kelas-modal-close" type="button">
          <svg aria-hidden="true" viewBox="0 0 24 24">
            <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2">
            </path>
          </svg>
        </button>
      </div>

      <form class="kelas-form" id="kelas-form">
        <div class="kelas-form-group">
          <label for="kelas-name">Nama Kelas</label>
          <input autocomplete="off" id="kelas-name" maxlength="5" name="nama_kelas" placeholder="Contoh: 3B" required
            type="text">
          <small class="kelas-form-help">Maksimal 5 karakter, misalnya 1A, 2B, atau 3C.</small>
        </div>

        <div class="kelas-form-group">
          <label for="kelas-year">Tahun Ajaran</label>
          <input autocomplete="off" id="kelas-year" maxlength="10" name="tahun_ajaran" placeholder="Contoh: 2026/2027"
            pattern="\d{4}/\d{4}" required type="text">
          <small class="kelas-form-help">Format sesuai database: YYYY/YYYY.</small>
        </div>

        <div class="kelas-form-group">
          <label for="kelas-wali">Wali Kelas</label>
          <div class="kelas-select-wrap">
            <select id="kelas-wali" name="wali_kelas_id" required>
              <option value="">Pilih wali kelas</option>
              <option value="1">Pak Fuang</option>
              <option value="2">Pak Fauzi</option>
              <option value="3">Pak Ipul Gaming</option>
              <option value="4">Pak Ramus</option>
              <option value="5">Pak Rama</option>
              <option value="6">Pak Dimas</option>
              <option value="7">Bu Larisa</option>
              <option value="8">Pak Rudy</option>
            </select>
          </div>
          <small class="kelas-form-help">
            Wali kelas berasal dari users dengan role guru. Satu guru hanya dapat menjadi wali satu kelas.
          </small>
        </div>

        <div class="kelas-form-actions">
          <button class="kelas-form-cancel" id="kelas-form-cancel" type="button">Batal</button>
          <button class="kelas-form-delete" id="kelas-form-delete-btn" type="button" hidden>Hapus Data Kelas</button>
          <button class="kelas-form-submit" id="kelas-form-submit-btn" type="submit">Simpan Data Kelas</button>
        </div>
      </form>
    </section>
  </div>

  <!-- =========================================================
       MODAL DETAIL KELAS
       Daftar siswa menggunakan data dummy.
       ========================================================= -->
  <div class="kelas-modal" hidden id="kelas-detail-modal">
    <div class="kelas-modal-backdrop" data-detail-modal-close></div>
    <section aria-labelledby="kelas-detail-title" aria-modal="true" class="kelas-modal-dialog kelas-detail-dialog"
      role="dialog">
      <div class="kelas-modal-header">
        <div>
          <h2 id="kelas-detail-title">Detail Kelas</h2>
          <p>Informasi kelas dan daftar siswa.</p>
        </div>
        <button aria-label="Tutup detail kelas" class="kelas-modal-close" id="kelas-detail-close" type="button">
          <svg aria-hidden="true" viewBox="0 0 24 24">
            <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2">
            </path>
          </svg>
        </button>
      </div>

      <div class="kelas-detail-summary">
        <div class="kelas-detail-info"><span>Nama Kelas</span><strong id="detail-nama-kelas">-</strong></div>
        <div class="kelas-detail-info"><span>Tahun Ajaran</span><strong id="detail-tahun">-</strong></div>
        <div class="kelas-detail-info"><span>Wali Kelas</span><strong id="detail-wali">-</strong></div>
        <div class="kelas-detail-info"><span>Jumlah Siswa</span><strong id="detail-jumlah-siswa">0 Siswa</strong></div>
      </div>

      <div class="kelas-student-section">
        <div class="kelas-student-heading">
          <h3>Daftar Siswa</h3>
          <span id="detail-student-count">0 siswa</span>
        </div>
        <div class="kelas-student-scroll">
          <table class="kelas-student-table">
            <thead>
              <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
              </tr>
            </thead>
            <tbody id="detail-student-body"></tbody>
          </table>
          <div class="kelas-no-student" id="kelas-no-student" hidden>Belum ada siswa pada kelas ini.</div>
        </div>
      </div>
    </section>
  </div>

  <!-- =========================================================
       MODAL KONFIRMASI DELETE
       ========================================================= -->
  <div class="kelas-modal" hidden id="kelas-delete-modal">
    <div class="kelas-modal-backdrop" data-delete-modal-close></div>
    <section aria-labelledby="kelas-delete-title" aria-modal="true" class="kelas-modal-dialog kelas-delete-dialog"
      role="dialog">
      <div class="kelas-delete-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24">
          <path d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13M10 11v6M14 11v6" fill="none" stroke="currentColor"
            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
      </div>
      <h2 class="kelas-delete-title" id="kelas-delete-title">Hapus Data Kelas?</h2>
      <p class="kelas-delete-text" id="kelas-delete-text">Anda akan menghapus data kelas.</p>
      <p class="kelas-delete-warning" id="kelas-delete-warning"></p>
      <div class="kelas-form-actions">
        <button class="kelas-form-cancel" id="kelas-delete-cancel" type="button">Batal</button>
        <button class="kelas-form-delete-confirm" id="kelas-delete-confirm" type="button">Ya, Hapus</button>
      </div>
    </section>
  </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/kelas.js') }}"></script>
@endpush