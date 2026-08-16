<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AddActivityController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\CompetencyController;
use App\Http\Controllers\Admin\PeriodeAkademikController;
use App\Http\Controllers\Admin\SkKompetensiController;
use App\Http\Controllers\Admin\ProdiCompetencyController;
use App\Http\Controllers\Admin\DosenCompetencyController;
use App\Http\Controllers\Admin\ProdiUnitController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Operasi\KalenderController;
use App\Http\Controllers\Admin\RequirementController;
use App\Http\Controllers\Admin\AccreditationController;
use App\Http\Controllers\Operasi\DaftarTugasController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\ActivityController as HomeActivityController;
use App\Http\Controllers\AccreditationController as HomeAccreditationController;
use App\Http\Controllers\AccreditationDocumentController;

use App\Http\Controllers\Admin\SertifikatController;
use App\Http\Controllers\TestingController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DosenCompetencyController as HomeDosenCompetencyController;
use App\Http\Controllers\Admin\DocumentKeputusanRektorController;
use App\Http\Controllers\Admin\DocumentSpmiController;
use App\Http\Controllers\Admin\DocumentSiklusPpeppController;
use App\Http\Controllers\Admin\DocumentLaporanBanchmarkingController;
use App\Http\Controllers\Admin\DocumentLaporanEvaluasiPpeppController;
use App\Http\Controllers\Admin\DocumentStatutaUiiDalwaController;
use App\Http\Controllers\Admin\DocumentRenstraUiiDalwaController;
use App\Http\Controllers\Admin\DocumentRenstraFakultasController;
use App\Http\Controllers\Admin\DocumentRenopUiiDalwaController;
use App\Http\Controllers\Admin\DocumentRipController;
use App\Http\Controllers\Admin\DocumentPedomanController;
use App\Http\Controllers\Admin\DocumentSotkUiiDalwaController;
use App\Http\Controllers\Admin\DocumentKurikulumProdiController;
use App\Http\Controllers\Admin\UnitDokumentController;
use App\Http\Controllers\Admin\DokumentImplementasiSpmiPddiktiController;
use App\Http\Controllers\VisiMisiController;
use App\Http\Controllers\SambutanKetuaController;
use App\Http\Controllers\SejarahController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\Admin\SambutanKetuaController as AdminSambutanKetuaController;
use App\Http\Controllers\Admin\StrukturOrganisasiController as AdminStrukturOrganisasiController;
use App\Http\Controllers\Admin\AnggotaStrukturOrganisasiController as AdminAnggotaStrukturOrganisasiController;
use App\Http\Controllers\Admin\PeriodeLpmController as AdminPeriodeLpmController;
use App\Http\Controllers\Admin\SkorAkreditasiController as AdminSkorAkreditasiController;
use App\Http\Controllers\SkorAkreditasiController;
use App\Http\Controllers\Admin\SkPendirianProdiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();
Route::get('/beranda', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [RootController::class, 'index'])->name('root.index');

Route::get('/about', [AboutController::class, 'index'])->name('about.index');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::get('/visi-misi', [VisiMisiController::class, 'index'])->name('visi-misi.index');
Route::get('/sambutan-ketua', [SambutanKetuaController::class, 'index'])->name('sambutan-ketua.index');
Route::get('/sejarah', [SejarahController::class, 'index'])->name('sejarah.index');
Route::get('/struktur-organisasi', [StrukturOrganisasiController::class, 'index'])->name('struktur-organisasi.index');
Route::get('/skor-akreditasi', [SkorAkreditasiController::class, 'index'])->name('skor-akreditasi.index');

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/detail/{slug}', [NewsController::class, 'detail'])->name('news.detail');
    Route::post('/detail/{news}/storeComment', [NewsController::class, 'storeComment'])->name('news.storeComment');
});

Route::prefix('accreditation')->group(function () {
    Route::get('/', [HomeAccreditationController::class, 'index'])->name('accreditation.index');
    Route::get('/detail/{accreditation}', [HomeAccreditationController::class, 'detail'])->name('accreditation.detail');
});

Route::prefix('dakung-prodi')->group(function () {
    Route::get('/download/{file}', [\App\Http\Controllers\DakungProdiController::class, 'download'])->name('dakung-prodi.download');
    Route::get('/preview/{file}', [\App\Http\Controllers\DakungProdiController::class, 'preview'])->name('dakung-prodi.preview');
});

Route::prefix('activity')->group(function () {
    Route::get('/', [HomeActivityController::class, 'index'])->name('activity.index');
    Route::get('/detail/{slug}', [HomeActivityController::class, 'detail'])->name('activity.detail');
});

Route::prefix('accreditation-certificate')->group(function () {
    Route::get('/', [AccreditationDocumentController::class, 'index'])->name('accreditation-certificate.index'); // List
});

// Institution Document (Frontend Public)
Route::prefix('institution-document')->group(function () {
    Route::get('/', [App\Http\Controllers\InstitutionDocumentController::class, 'index'])->name('institution-document.index');

    // API Routes untuk DataTables
    Route::get('/data/sk-pendirian-prodi', [App\Http\Controllers\InstitutionDocumentController::class, 'dataSkPendirianProdi'])->name('institution-document.data.sk-pendirian-prodi');
    Route::get('/data/keputusan-rektor', [App\Http\Controllers\InstitutionDocumentController::class, 'dataKeputusanRektor'])->name('institution-document.data.keputusan-rektor');
    Route::get('/data/spmi', [App\Http\Controllers\InstitutionDocumentController::class, 'dataSpmi'])->name('institution-document.data.spmi');
    Route::get('/data/siklus-ppepp', [App\Http\Controllers\InstitutionDocumentController::class, 'dataSiklusPpepp'])->name('institution-document.data.siklus-ppepp');
    Route::get('/data/statuta', [App\Http\Controllers\InstitutionDocumentController::class, 'dataStatuta'])->name('institution-document.data.statuta');
    Route::get('/data/renstra', [App\Http\Controllers\InstitutionDocumentController::class, 'dataRenstra'])->name('institution-document.data.renstra');
    Route::get('/data/rip', [App\Http\Controllers\InstitutionDocumentController::class, 'dataRip'])->name('institution-document.data.rip');
    Route::get('/data/renop', [App\Http\Controllers\InstitutionDocumentController::class, 'dataRenop'])->name('institution-document.data.renop');
    Route::get('/data/sotk', [App\Http\Controllers\InstitutionDocumentController::class, 'dataSotk'])->name('institution-document.data.sotk');
    Route::get('/data/kurikulum-prodi', [App\Http\Controllers\InstitutionDocumentController::class, 'dataKurikulumProdi'])->name('institution-document.data.kurikulum-prodi');
    Route::get('/data/laporan-benchmarking', [App\Http\Controllers\InstitutionDocumentController::class, 'dataLaporanBenchmarking'])->name('institution-document.data.laporan-benchmarking');
    Route::get('/data/laporan-evaluasi-ppepp', [App\Http\Controllers\InstitutionDocumentController::class, 'dataLaporanEvaluasiPpepp'])->name('institution-document.data.laporan-evaluasi-ppepp');
    Route::get('/data/pedoman', [App\Http\Controllers\InstitutionDocumentController::class, 'dataPedoman'])->name('institution-document.data.pedoman');

    // API Routes untuk External API (Buku, Jurnal, Prosiding, Artikel)
    Route::get('/data/buku', [App\Http\Controllers\InstitutionDocumentController::class, 'dataBuku'])->name('institution-document.data.buku');
    Route::get('/data/jurnal', [App\Http\Controllers\InstitutionDocumentController::class, 'dataJurnal'])->name('institution-document.data.jurnal');
    Route::get('/data/prosiding', [App\Http\Controllers\InstitutionDocumentController::class, 'dataProsiding'])->name('institution-document.data.prosiding');
    Route::get('/data/artikel', [App\Http\Controllers\InstitutionDocumentController::class, 'dataArtikel'])->name('institution-document.data.artikel');
    Route::get('/data/rps', [App\Http\Controllers\InstitutionDocumentController::class, 'dataRps'])->name('institution-document.data.rps');

    // Download Route (tanpa storage:link)
    Route::get('/download/{path}', [App\Http\Controllers\InstitutionDocumentController::class, 'download'])->where('path', '.*')->name('institution-document.download');
    // Preview Route (inline file viewing)
    Route::get('/preview/{path}', [App\Http\Controllers\InstitutionDocumentController::class, 'preview'])->where('path', '.*')->name('institution-document.preview');
});

Route::prefix('addactivity/{code}')->group(function () {
    Route::get('/', [AddActivityController::class, 'index'])->name('addactivity.index');
    Route::post('/store', [AddActivityController::class, 'store'])->name('addactivity.store');
    Route::post('/storeDokumen', [AddActivityController::class, 'storeDokumen'])->name('addactivity.storeDokumen');
    Route::post('/destroyDokumen', [AddActivityController::class, 'destroyDokumen'])->name('addactivity.destroyDokumen');
    Route::get('/getDataDokumen', [AddActivityController::class, 'getDataDokumen'])->name('addactivity.getDataDokumen');
});

Route::prefix('dosen-competency')->group(function () {
    Route::get('/', [HomeDosenCompetencyController::class, 'index'])->name('dosen-competency.index');
    Route::get('/data', [HomeDosenCompetencyController::class, 'getData'])->name('dosen-competency.data');
    Route::get('/search-dosen/{search}', [HomeDosenCompetencyController::class, 'searchDosen'])->name('dosen-competency.search-dosen');
    Route::get('/get-competencies', [HomeDosenCompetencyController::class, 'getCompetencies'])->name('dosen-competency.get-competencies');
    Route::post('/store', [HomeDosenCompetencyController::class, 'store'])->name('dosen-competency.store');
    Route::delete('/destroy/{id}', [HomeDosenCompetencyController::class, 'destroy'])->name('dosen-competency.destroy');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Document Keputusan Rektor
    Route::prefix('document-keputusan-rektor')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentKeputusanRektorController::class, 'index'])->name('admin.document-keputusan-rektor.index');
        Route::get('/data', [DocumentKeputusanRektorController::class, 'getData'])->name('admin.document-keputusan-rektor.data');
        Route::post('/store', [DocumentKeputusanRektorController::class, 'store'])->name('admin.document-keputusan-rektor.store');
        Route::put('/update/{id}', [DocumentKeputusanRektorController::class, 'update'])->name('admin.document-keputusan-rektor.update');
        Route::delete('/destroy/{id}', [DocumentKeputusanRektorController::class, 'destroy'])->name('admin.document-keputusan-rektor.destroy');
    });

    // SK Pendirian Prodi
    Route::prefix('sk-pendirian-prodi')->middleware('role:admin')->group(function () {
        Route::get('/', [SkPendirianProdiController::class, 'index'])->name('admin.sk-pendirian-prodi.index');
        Route::get('/data', [SkPendirianProdiController::class, 'getData'])->name('admin.sk-pendirian-prodi.data');
        Route::post('/store', [SkPendirianProdiController::class, 'store'])->name('admin.sk-pendirian-prodi.store');
        Route::put('/update/{id}', [SkPendirianProdiController::class, 'update'])->name('admin.sk-pendirian-prodi.update');
        Route::delete('/destroy/{id}', [SkPendirianProdiController::class, 'destroy'])->name('admin.sk-pendirian-prodi.destroy');
    });

    // Document SPMI
    Route::prefix('document-spmi')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentSpmiController::class, 'index'])->name('admin.document-spmi.index');
        Route::get('/data', [DocumentSpmiController::class, 'getData'])->name('admin.document-spmi.data');
        Route::post('/store', [DocumentSpmiController::class, 'store'])->name('admin.document-spmi.store');
        Route::put('/update/{id}', [DocumentSpmiController::class, 'update'])->name('admin.document-spmi.update');
        Route::delete('/destroy/{id}', [DocumentSpmiController::class, 'destroy'])->name('admin.document-spmi.destroy');
    });

    // Dokument Implementasi SPMI PDDIKTI
    Route::prefix('dokument-implementasi-spmi-pddikti')->middleware('role:admin')->group(function () {
        Route::get('/', [DokumentImplementasiSpmiPddiktiController::class, 'index'])->name('admin.dokument-implementasi-spmi-pddikti.index');
        Route::get('/data', [DokumentImplementasiSpmiPddiktiController::class, 'getData'])->name('admin.dokument-implementasi-spmi-pddikti.data');
        Route::post('/store', [DokumentImplementasiSpmiPddiktiController::class, 'store'])->name('admin.dokument-implementasi-spmi-pddikti.store');
        Route::put('/update/{id}', [DokumentImplementasiSpmiPddiktiController::class, 'update'])->name('admin.dokument-implementasi-spmi-pddikti.update');
        Route::delete('/destroy/{id}', [DokumentImplementasiSpmiPddiktiController::class, 'destroy'])->name('admin.dokument-implementasi-spmi-pddikti.destroy');
    });

    // Document Siklus PPEPP
    Route::prefix('document-siklus-ppepp')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentSiklusPpeppController::class, 'index'])->name('admin.document-siklus-ppepp.index');
        Route::get('/data', [DocumentSiklusPpeppController::class, 'getData'])->name('admin.document-siklus-ppepp.data');
        Route::post('/store', [DocumentSiklusPpeppController::class, 'store'])->name('admin.document-siklus-ppepp.store');
        Route::put('/update/{id}', [DocumentSiklusPpeppController::class, 'update'])->name('admin.document-siklus-ppepp.update');
        Route::delete('/destroy/{id}', [DocumentSiklusPpeppController::class, 'destroy'])->name('admin.document-siklus-ppepp.destroy');
    });

    // Document Laporan Benchmarking
    Route::prefix('document-laporan-banchmarking')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentLaporanBanchmarkingController::class, 'index'])->name('admin.document-laporan-banchmarking.index');
        Route::get('/data', [DocumentLaporanBanchmarkingController::class, 'getData'])->name('admin.document-laporan-banchmarking.data');
        Route::post('/store', [DocumentLaporanBanchmarkingController::class, 'store'])->name('admin.document-laporan-banchmarking.store');
        Route::put('/update/{id}', [DocumentLaporanBanchmarkingController::class, 'update'])->name('admin.document-laporan-banchmarking.update');
        Route::delete('/destroy/{id}', [DocumentLaporanBanchmarkingController::class, 'destroy'])->name('admin.document-laporan-banchmarking.destroy');
    });

    // Document Laporan Evaluasi PPEPP
    Route::prefix('document-laporan-evaluasi-ppepp')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentLaporanEvaluasiPpeppController::class, 'index'])->name('admin.document-laporan-evaluasi-ppepp.index');
        Route::get('/data', [DocumentLaporanEvaluasiPpeppController::class, 'getData'])->name('admin.document-laporan-evaluasi-ppepp.data');
        Route::post('/store', [DocumentLaporanEvaluasiPpeppController::class, 'store'])->name('admin.document-laporan-evaluasi-ppepp.store');
        Route::put('/update/{id}', [DocumentLaporanEvaluasiPpeppController::class, 'update'])->name('admin.document-laporan-evaluasi-ppepp.update');
        Route::delete('/destroy/{id}', [DocumentLaporanEvaluasiPpeppController::class, 'destroy'])->name('admin.document-laporan-evaluasi-ppepp.destroy');
    });

    // Document Statuta UII Dalwa
    Route::prefix('document-statuta-uii-dalwa')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentStatutaUiiDalwaController::class, 'index'])->name('admin.document-statuta-uii-dalwa.index');
        Route::get('/data', [DocumentStatutaUiiDalwaController::class, 'getData'])->name('admin.document-statuta-uii-dalwa.data');
        Route::post('/store', [DocumentStatutaUiiDalwaController::class, 'store'])->name('admin.document-statuta-uii-dalwa.store');
        Route::put('/update/{id}', [DocumentStatutaUiiDalwaController::class, 'update'])->name('admin.document-statuta-uii-dalwa.update');
        Route::delete('/destroy/{id}', [DocumentStatutaUiiDalwaController::class, 'destroy'])->name('admin.document-statuta-uii-dalwa.destroy');
    });

    // Document Renstra UII Dalwa
    Route::prefix('document-renstra-uii-dalwa')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentRenstraUiiDalwaController::class, 'index'])->name('admin.document-renstra-uii-dalwa.index');
        Route::get('/data', [DocumentRenstraUiiDalwaController::class, 'getData'])->name('admin.document-renstra-uii-dalwa.data');
        Route::post('/store', [DocumentRenstraUiiDalwaController::class, 'store'])->name('admin.document-renstra-uii-dalwa.store');
        Route::put('/update/{id}', [DocumentRenstraUiiDalwaController::class, 'update'])->name('admin.document-renstra-uii-dalwa.update');
        Route::delete('/destroy/{id}', [DocumentRenstraUiiDalwaController::class, 'destroy'])->name('admin.document-renstra-uii-dalwa.destroy');
    });

    // Unit Dokument
    Route::prefix('unit-dokument')->middleware('role:admin')->group(function () {
        Route::get('/', [UnitDokumentController::class, 'index'])->name('admin.unit-dokument.index');
        Route::get('/data', [UnitDokumentController::class, 'data'])->name('admin.unit-dokument.data');
        Route::post('/store', [UnitDokumentController::class, 'store'])->name('admin.unit-dokument.store');
        Route::put('/update', [UnitDokumentController::class, 'update'])->name('admin.unit-dokument.update');
        Route::delete('/delete', [UnitDokumentController::class, 'delete'])->name('admin.unit-dokument.delete');
    });

    // Document RIP (Rencana Induk Pengembangan)
    Route::prefix('document-rip')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentRipController::class, 'index'])->name('admin.document-rip.index');
        Route::get('/data', [DocumentRipController::class, 'getData'])->name('admin.document-rip.data');
        Route::post('/store', [DocumentRipController::class, 'store'])->name('admin.document-rip.store');
        Route::put('/update/{id}', [DocumentRipController::class, 'update'])->name('admin.document-rip.update');
        Route::delete('/destroy/{id}', [DocumentRipController::class, 'destroy'])->name('admin.document-rip.destroy');
    });

    // Document Renop UII Dalwa
    Route::prefix('document-renop-uii-dalwa')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentRenopUiiDalwaController::class, 'index'])->name('admin.document-renop-uii-dalwa.index');
        Route::get('/data', [DocumentRenopUiiDalwaController::class, 'getData'])->name('admin.document-renop-uii-dalwa.data');
        Route::post('/store', [DocumentRenopUiiDalwaController::class, 'store'])->name('admin.document-renop-uii-dalwa.store');
        Route::put('/update/{id}', [DocumentRenopUiiDalwaController::class, 'update'])->name('admin.document-renop-uii-dalwa.update');
        Route::delete('/destroy/{id}', [DocumentRenopUiiDalwaController::class, 'destroy'])->name('admin.document-renop-uii-dalwa.destroy');
    });

    // Document SOTK UII Dalwa
    Route::prefix('document-sotk-uii-dalwa')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentSotkUiiDalwaController::class, 'index'])->name('admin.document-sotk-uii-dalwa.index');
        Route::get('/data', [DocumentSotkUiiDalwaController::class, 'getData'])->name('admin.document-sotk-uii-dalwa.data');
        Route::post('/store', [DocumentSotkUiiDalwaController::class, 'store'])->name('admin.document-sotk-uii-dalwa.store');
        Route::put('/update/{id}', [DocumentSotkUiiDalwaController::class, 'update'])->name('admin.document-sotk-uii-dalwa.update');
        Route::delete('/destroy/{id}', [DocumentSotkUiiDalwaController::class, 'destroy'])->name('admin.document-sotk-uii-dalwa.destroy');
    });

    // Document Pedoman
    Route::prefix('document-pedoman')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentPedomanController::class, 'index'])->name('admin.document-pedoman.index');
        Route::get('/data', [DocumentPedomanController::class, 'getData'])->name('admin.document-pedoman.data');
        Route::post('/store', [DocumentPedomanController::class, 'store'])->name('admin.document-pedoman.store');
        Route::put('/update/{id}', [DocumentPedomanController::class, 'update'])->name('admin.document-pedoman.update');
        Route::delete('/destroy/{id}', [DocumentPedomanController::class, 'destroy'])->name('admin.document-pedoman.destroy');
    });

    // Document Kurikulum Prodi
    Route::prefix('document-kurikulum-prodi')->middleware('role:admin')->group(function () {
        Route::get('/', [DocumentKurikulumProdiController::class, 'index'])->name('admin.document-kurikulum-prodi.index');
        Route::get('/data', [DocumentKurikulumProdiController::class, 'getData'])->name('admin.document-kurikulum-prodi.data');
        Route::post('/store', [DocumentKurikulumProdiController::class, 'store'])->name('admin.document-kurikulum-prodi.store');
        Route::put('/update/{id}', [DocumentKurikulumProdiController::class, 'update'])->name('admin.document-kurikulum-prodi.update');
        Route::delete('/destroy/{id}', [DocumentKurikulumProdiController::class, 'destroy'])->name('admin.document-kurikulum-prodi.destroy');
    });

    Route::prefix('category')->middleware('role:admin')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get('/data', [CategoryController::class, 'data'])->name('admin.category.data');
        Route::post('/store', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::put('/update', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/delete', [CategoryController::class, 'delete'])->name('admin.category.delete');
    });
    Route::prefix('news')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminNewsController::class, 'index'])->name('admin.news.index');
        Route::get('/data', [AdminNewsController::class, 'data'])->name('admin.news.data');
        Route::get('/add', [AdminNewsController::class, 'add'])->name('admin.news.add');
        Route::post('/store', [AdminNewsController::class, 'store'])->name('admin.news.store');
        Route::get('/edit/{news}', [AdminNewsController::class, 'edit'])->name('admin.news.edit');
        Route::put('/update/{news}', [AdminNewsController::class, 'update'])->name('admin.news.update');
        Route::delete('/delete', [AdminNewsController::class, 'delete'])->name('admin.news.delete');
    });
    Route::prefix('unit')->middleware('role:admin')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('admin.unit.index');
        Route::get('/data', [UnitController::class, 'data'])->name('admin.unit.data');
        Route::post('/store', [UnitController::class, 'store'])->name('admin.unit.store');
        Route::put('/update', [UnitController::class, 'update'])->name('admin.unit.update');
        Route::delete('/delete', [UnitController::class, 'delete'])->name('admin.unit.delete');
    });
    Route::prefix('tag')->middleware('role:admin')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('admin.tag.index');
        Route::get('/data', [TagController::class, 'data'])->name('admin.tag.data');
        Route::post('/store', [TagController::class, 'store'])->name('admin.tag.store');
        Route::put('/update', [TagController::class, 'update'])->name('admin.tag.update');
        Route::delete('/delete', [TagController::class, 'delete'])->name('admin.tag.delete');
    });
    Route::prefix('activity')->middleware('role:admin')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('admin.activity.index');
        Route::get('/data', [ActivityController::class, 'data'])->name('admin.activity.data');
        Route::get('/add', [ActivityController::class, 'add'])->name('admin.activity.add');
        Route::post('/store', [ActivityController::class, 'store'])->name('admin.activity.store');
        Route::get('/edit/{activity}', [ActivityController::class, 'edit'])->name('admin.activity.edit');
        Route::put('/update/{activity}', [ActivityController::class, 'update'])->name('admin.activity.update');
        Route::delete('/delete', [ActivityController::class, 'delete'])->name('admin.activity.delete');
    });
    Route::prefix('prodi')->middleware('role:admin')->group(function () {
        Route::get('/', [ProdiController::class, 'index'])->name('admin.prodi.index');
        Route::get('/data', [ProdiController::class, 'data'])->name('admin.prodi.data');
        Route::post('/store', [ProdiController::class, 'store'])->name('admin.prodi.store');
        Route::put('/update', [ProdiController::class, 'update'])->name('admin.prodi.update');
        Route::delete('/delete', [ProdiController::class, 'delete'])->name('admin.prodi.delete');
    });

    Route::prefix('dosen')->middleware('role:admin')->group(function () {
        Route::get('/', [DosenController::class, 'index'])->name('admin.dosen.index');
        Route::get('/search/{search}', [DosenController::class, 'search'])->name('admin.dosen.search');
        Route::get('/data', [DosenController::class, 'data'])->name('admin.dosen.data');
        Route::post('/store', [DosenController::class, 'store'])->name('admin.dosen.store');
        Route::put('/update', [DosenController::class, 'update'])->name('admin.dosen.update');
        Route::delete('/delete', [DosenController::class, 'delete'])->name('admin.dosen.delete');
    });

    Route::prefix('competency')->middleware('role:admin')->group(function () {
        Route::get('/', [CompetencyController::class, 'index'])->name('admin.competency.index');
        Route::get('/data', [CompetencyController::class, 'data'])->name('admin.competency.data');
        Route::post('/store', [CompetencyController::class, 'store'])->name('admin.competency.store');
        Route::put('/update', [CompetencyController::class, 'update'])->name('admin.competency.update');
        Route::delete('/delete', [CompetencyController::class, 'delete'])->name('admin.competency.delete');
    });

    Route::prefix('periode-akademik')->middleware('role:admin')->group(function () {
        Route::get('/', [PeriodeAkademikController::class, 'index'])->name('admin.periode-akademik.index');
        Route::get('/data', [PeriodeAkademikController::class, 'data'])->name('admin.periode-akademik.data');
        Route::post('/store', [PeriodeAkademikController::class, 'store'])->name('admin.periode-akademik.store');
        Route::put('/update', [PeriodeAkademikController::class, 'update'])->name('admin.periode-akademik.update');
        Route::delete('/delete', [PeriodeAkademikController::class, 'delete'])->name('admin.periode-akademik.delete');
    });

    Route::prefix('sk-kompetensi')->middleware('role:admin')->group(function () {
        Route::get('/', [SkKompetensiController::class, 'index'])->name('admin.sk-kompetensi.index');
        Route::get('/data', [SkKompetensiController::class, 'data'])->name('admin.sk-kompetensi.data');
        Route::post('/store', [SkKompetensiController::class, 'store'])->name('admin.sk-kompetensi.store');
        Route::put('/update', [SkKompetensiController::class, 'update'])->name('admin.sk-kompetensi.update');
        Route::delete('/delete', [SkKompetensiController::class, 'delete'])->name('admin.sk-kompetensi.delete');
    });

    Route::prefix('prodi-competency')->middleware('role:admin')->group(function () {
        Route::get('/', [ProdiCompetencyController::class, 'index'])->name('admin.prodi-competency.index');
        Route::get('/data', [ProdiCompetencyController::class, 'data'])->name('admin.prodi-competency.data');
        Route::get('/get-competencies/{id}', [ProdiCompetencyController::class, 'getProdiCompetencies'])->name('admin.prodi-competency.get-competencies');
        Route::get('/edit/{id}', [ProdiCompetencyController::class, 'edit'])->name('admin.prodi-competency.edit');
        Route::put('/update/{id}', [ProdiCompetencyController::class, 'update'])->name('admin.prodi-competency.update');
    });

    Route::prefix('dosen-competency')->middleware('role:admin')->group(function () {
        Route::get('/', [DosenCompetencyController::class, 'index'])->name('admin.dosen-competency.index');
        Route::get('/data', [DosenCompetencyController::class, 'data'])->name('admin.dosen-competency.data');
        Route::get('/get-competencies-by-prodi/{id}', [DosenCompetencyController::class, 'getCompetenciesByProdi'])->name('admin.dosen-competency.get-competencies-by-prodi');
        Route::get('/add', [DosenCompetencyController::class, 'add'])->name('admin.dosen-competency.add');
        Route::post('/store', [DosenCompetencyController::class, 'store'])->name('admin.dosen-competency.store');
        Route::post('/import', [DosenCompetencyController::class, 'import'])->name('admin.dosen-competency.import');
        Route::get('/export-template', [DosenCompetencyController::class, 'exportTemplate'])->name('admin.dosen-competency.export-template');
        Route::get('/edit/{id}', [DosenCompetencyController::class, 'edit'])->name('admin.dosen-competency.edit');
        Route::put('/update/{id}', [DosenCompetencyController::class, 'update'])->name('admin.dosen-competency.update');
        Route::delete('/delete', [DosenCompetencyController::class, 'delete'])->name('admin.dosen-competency.delete');
    });
    Route::prefix('accreditation')->middleware('role:admin')->group(function () {
        Route::get('/', [AccreditationController::class, 'index'])->name('admin.accreditation.index');
        Route::get('/data', [AccreditationController::class, 'data'])->name('admin.accreditation.data');
        Route::post('/store', [AccreditationController::class, 'store'])->name('admin.accreditation.store');
        Route::put('/update', [AccreditationController::class, 'update'])->name('admin.accreditation.update');
        Route::delete('/delete', [AccreditationController::class, 'delete'])->name('admin.accreditation.delete');

        Route::prefix('{accreditation}/requirement')->middleware('role:admin')->group(function () {
            Route::get('/', [RequirementController::class, 'index'])->name('admin.accreditation.requirement.index');
            Route::get('/data', [RequirementController::class, 'data'])->name('admin.accreditation.requirement.data');
            Route::post('/store', [RequirementController::class, 'store'])->name('admin.accreditation.requirement.store');
            Route::put('/update', [RequirementController::class, 'update'])->name('admin.accreditation.requirement.update');
            Route::delete('/delete', [RequirementController::class, 'delete'])->name('admin.accreditation.requirement.delete');
        });
    });

    Route::prefix('dakung-prodi')->middleware('role:admin,lpm')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DakungProdiCategoryController::class, 'index'])->name('admin.dakung-prodi.index');
        Route::get('/data', [\App\Http\Controllers\Admin\DakungProdiCategoryController::class, 'data'])->name('admin.dakung-prodi.data');
        Route::get('/get-categories', [\App\Http\Controllers\Admin\DakungProdiCategoryController::class, 'getCategories'])->name('admin.dakung-prodi.get-categories');
        Route::post('/store', [\App\Http\Controllers\Admin\DakungProdiCategoryController::class, 'store'])->name('admin.dakung-prodi.store');
        Route::put('/update', [\App\Http\Controllers\Admin\DakungProdiCategoryController::class, 'update'])->name('admin.dakung-prodi.update');
        Route::delete('/delete', [\App\Http\Controllers\Admin\DakungProdiCategoryController::class, 'delete'])->name('admin.dakung-prodi.delete');

        Route::prefix('{dakungProdiCategory}/file')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DakungProdiFileController::class, 'index'])->name('admin.dakung-prodi.file.index');
            Route::get('/data', [\App\Http\Controllers\Admin\DakungProdiFileController::class, 'data'])->name('admin.dakung-prodi.file.data');
            Route::post('/store', [\App\Http\Controllers\Admin\DakungProdiFileController::class, 'store'])->name('admin.dakung-prodi.file.store');
            Route::put('/update', [\App\Http\Controllers\Admin\DakungProdiFileController::class, 'update'])->name('admin.dakung-prodi.file.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\DakungProdiFileController::class, 'delete'])->name('admin.dakung-prodi.file.delete');
        });
    });

    // Akreditasi Kampus
    Route::prefix('akreditasi-kampus')->middleware('role:admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AkreditasiKampusController::class, 'index'])->name('admin.akreditasi-kampus.index');
        Route::get('/data', [App\Http\Controllers\Admin\AkreditasiKampusController::class, 'data'])->name('admin.akreditasi-kampus.data');
        Route::get('/add', [App\Http\Controllers\Admin\AkreditasiKampusController::class, 'add'])->name('admin.akreditasi-kampus.add');
        Route::post('/store', [App\Http\Controllers\Admin\AkreditasiKampusController::class, 'store'])->name('admin.akreditasi-kampus.store');
        Route::get('/edit/{akreditasiKampus}', [App\Http\Controllers\Admin\AkreditasiKampusController::class, 'edit'])->name('admin.akreditasi-kampus.edit');
        Route::put('/update/{akreditasiKampus}', [App\Http\Controllers\Admin\AkreditasiKampusController::class, 'update'])->name('admin.akreditasi-kampus.update');
        Route::delete('/delete', [App\Http\Controllers\Admin\AkreditasiKampusController::class, 'delete'])->name('admin.akreditasi-kampus.delete');
    });
    Route::prefix('sertifikat')->middleware('role:admin')->group(function () {
        Route::get('/', [SertifikatController::class, 'indexSertifikat'])->name('admin.sertifikat.index');
        Route::get('/data', [SertifikatController::class, 'dataSertifikat'])->name('admin.sertifikat.data');
        Route::post('/store', [SertifikatController::class, 'storeSertifikat'])->name('admin.sertifikat.store');
        Route::put('/update', [SertifikatController::class, 'updateSertifikat'])->name('admin.sertifikat.update');
        Route::delete('/delete', [SertifikatController::class, 'deleteSertifikat'])->name('admin.sertifikat.delete');
    });

    Route::prefix('prodi-unit')->middleware('role:admin')->group(function () {
        Route::get('/', [ProdiUnitController::class, 'index'])->name('admin.prodi-unit.index');
        Route::get('/data', [ProdiUnitController::class, 'data'])->name('admin.prodi-unit.data');
        Route::post('/store', [ProdiUnitController::class, 'store'])->name('admin.prodi-unit.store');
        Route::put('/update', [ProdiUnitController::class, 'update'])->name('admin.prodi-unit.update');
        Route::delete('/delete', [ProdiUnitController::class, 'delete'])->name('admin.prodi-unit.delete');

        // User management routes
        Route::get('/search-users', [ProdiUnitController::class, 'searchUsers'])->name('admin.prodi-unit.search-users');
        Route::post('/add-user', [ProdiUnitController::class, 'addUser'])->name('admin.prodi-unit.add-user');
        Route::delete('/remove-user', [ProdiUnitController::class, 'removeUser'])->name('admin.prodi-unit.remove-user');
        Route::get('/{prodiUnit}/users', [ProdiUnitController::class, 'users'])->name('admin.prodi-unit.users');
        Route::get('/{prodiUnit}/users/data', [ProdiUnitController::class, 'usersData'])->name('admin.prodi-unit.users.data');
    });



    Route::prefix('user')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
        Route::get('/data', [UserController::class, 'data'])->name('admin.user.data');
        Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
        Route::put('/update', [UserController::class, 'update'])->name('admin.user.update');
        Route::delete('/delete', [UserController::class, 'delete'])->name('admin.user.delete');
    });

    // Sambutan Ketua
    Route::prefix('sambutan-ketua')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminSambutanKetuaController::class, 'index'])->name('admin.sambutan-ketua.index');
        Route::get('/data', [AdminSambutanKetuaController::class, 'data'])->name('admin.sambutan-ketua.data');
        Route::get('/add', [AdminSambutanKetuaController::class, 'add'])->name('admin.sambutan-ketua.add');
        Route::post('/store', [AdminSambutanKetuaController::class, 'store'])->name('admin.sambutan-ketua.store');
        Route::get('/edit/{sambutanKetua}', [AdminSambutanKetuaController::class, 'edit'])->name('admin.sambutan-ketua.edit');
        Route::put('/update/{sambutanKetua}', [AdminSambutanKetuaController::class, 'update'])->name('admin.sambutan-ketua.update');
        Route::delete('/delete', [AdminSambutanKetuaController::class, 'delete'])->name('admin.sambutan-ketua.delete');
    });

    // Struktur Organisasi
    Route::prefix('struktur-organisasi')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminStrukturOrganisasiController::class, 'index'])->name('admin.struktur-organisasi.index');
        Route::get('/data', [AdminStrukturOrganisasiController::class, 'data'])->name('admin.struktur-organisasi.data');
        Route::get('/add', [AdminStrukturOrganisasiController::class, 'add'])->name('admin.struktur-organisasi.add');
        Route::post('/store', [AdminStrukturOrganisasiController::class, 'store'])->name('admin.struktur-organisasi.store');
        Route::get('/edit/{strukturOrganisasi}', [AdminStrukturOrganisasiController::class, 'edit'])->name('admin.struktur-organisasi.edit');
        Route::put('/update/{strukturOrganisasi}', [AdminStrukturOrganisasiController::class, 'update'])->name('admin.struktur-organisasi.update');
        Route::delete('/delete', [AdminStrukturOrganisasiController::class, 'delete'])->name('admin.struktur-organisasi.delete');
    });

    // Anggota Struktur Organisasi
    Route::prefix('anggota-struktur-organisasi')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminAnggotaStrukturOrganisasiController::class, 'index'])->name('admin.anggota-struktur-organisasi.index');
        Route::get('/data', [AdminAnggotaStrukturOrganisasiController::class, 'data'])->name('admin.anggota-struktur-organisasi.data');
        Route::get('/add', [AdminAnggotaStrukturOrganisasiController::class, 'add'])->name('admin.anggota-struktur-organisasi.add');
        Route::post('/store', [AdminAnggotaStrukturOrganisasiController::class, 'store'])->name('admin.anggota-struktur-organisasi.store');
        Route::get('/edit/{anggotaStrukturOrganisasi}', [AdminAnggotaStrukturOrganisasiController::class, 'edit'])->name('admin.anggota-struktur-organisasi.edit');
        Route::put('/update/{anggotaStrukturOrganisasi}', [AdminAnggotaStrukturOrganisasiController::class, 'update'])->name('admin.anggota-struktur-organisasi.update');
        Route::delete('/delete', [AdminAnggotaStrukturOrganisasiController::class, 'delete'])->name('admin.anggota-struktur-organisasi.delete');
    });

    // Periode LPM
    Route::prefix('periode-lpm')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminPeriodeLpmController::class, 'index'])->name('admin.periode-lpm.index');
        Route::get('/data', [AdminPeriodeLpmController::class, 'data'])->name('admin.periode-lpm.data');
        Route::get('/add', [AdminPeriodeLpmController::class, 'add'])->name('admin.periode-lpm.add');
        Route::post('/store', [AdminPeriodeLpmController::class, 'store'])->name('admin.periode-lpm.store');
        Route::get('/edit/{periodeLpm}', [AdminPeriodeLpmController::class, 'edit'])->name('admin.periode-lpm.edit');
        Route::put('/update/{periodeLpm}', [AdminPeriodeLpmController::class, 'update'])->name('admin.periode-lpm.update');
        Route::delete('/delete', [AdminPeriodeLpmController::class, 'delete'])->name('admin.periode-lpm.delete');
    });

    // Skor Akreditasi
    Route::prefix('skor-akreditasi')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminSkorAkreditasiController::class, 'index'])->name('admin.skor-akreditasi.index');
        Route::get('/data', [AdminSkorAkreditasiController::class, 'data'])->name('admin.skor-akreditasi.data');
        Route::get('/add', [AdminSkorAkreditasiController::class, 'add'])->name('admin.skor-akreditasi.add');
        Route::post('/store', [AdminSkorAkreditasiController::class, 'store'])->name('admin.skor-akreditasi.store');
        Route::get('/edit/{skorAkreditasi}', [AdminSkorAkreditasiController::class, 'edit'])->name('admin.skor-akreditasi.edit');
        Route::put('/update/{skorAkreditasi}', [AdminSkorAkreditasiController::class, 'update'])->name('admin.skor-akreditasi.update');
        Route::delete('/delete', [AdminSkorAkreditasiController::class, 'delete'])->name('admin.skor-akreditasi.delete');
    });

    // Profil
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::put('/update', [ProfileController::class, 'update'])->name('admin.profile.update');
    });

    // ============================================================
    // AMI (Audit Mutu Internal)
    // ============================================================
    Route::prefix('ami')->middleware('role:admin,lpm,unit')->group(function () {
        // Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiDashboardController::class, 'index'])->name('admin.ami.dashboard');

        // AMI Mode Switcher
        Route::post('/switch-mode', [\App\Http\Controllers\Admin\Ami\AmiModeSwitcherController::class, 'switchMode'])->name('admin.ami.switch-mode');

        // Unit Audit (admin/lpm only)
        Route::prefix('unit-audit')->middleware('role:admin,lpm')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiUnitAuditController::class, 'index'])->name('admin.ami.unit-audit.index');
            Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiUnitAuditController::class, 'data'])->name('admin.ami.unit-audit.data');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiUnitAuditController::class, 'store'])->name('admin.ami.unit-audit.store');
            Route::put('/update', [\App\Http\Controllers\Admin\Ami\AmiUnitAuditController::class, 'update'])->name('admin.ami.unit-audit.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiUnitAuditController::class, 'delete'])->name('admin.ami.unit-audit.delete');
        });

        // Periode AMI (admin/lpm only)
        Route::prefix('periode')->middleware('role:admin,lpm')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiPeriodeController::class, 'index'])->name('admin.ami.periode.index');
            Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiPeriodeController::class, 'data'])->name('admin.ami.periode.data');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiPeriodeController::class, 'store'])->name('admin.ami.periode.store');
            Route::put('/update', [\App\Http\Controllers\Admin\Ami\AmiPeriodeController::class, 'update'])->name('admin.ami.periode.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiPeriodeController::class, 'delete'])->name('admin.ami.periode.delete');
        });

        // Target AMI
        Route::prefix('target-ami')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'index'])->name('admin.ami.target-ami.index');
            Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'data'])->name('admin.ami.target-ami.data');
            Route::get('/create', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'create'])->name('admin.ami.target-ami.create');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'store'])->name('admin.ami.target-ami.store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'show'])->name('admin.ami.target-ami.show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'edit'])->name('admin.ami.target-ami.edit');
            Route::put('/{id}/update', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'update'])->name('admin.ami.target-ami.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'delete'])->name('admin.ami.target-ami.delete');
            Route::post('/{id}/change-status', [\App\Http\Controllers\Admin\Ami\AmiTargetAmiController::class, 'changeStatus'])->name('admin.ami.target-ami.change-status');
        });

        // Indikator (Global List)
        Route::get('/indikator', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'indexSk'])->name('admin.ami.indikator.list');

        // Temuan Audit (Global List - URL: audit-proses)
        Route::get('/audit-proses', [\App\Http\Controllers\Admin\Ami\AmiTemuanAuditController::class, 'indexSk'])->name('admin.ami.temuan.list');

        // Hasil Temuan (Global List)
        Route::get('/hasil-temuan', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'indexSk'])->name('admin.ami.hasil-temuan.list');

        // Laporan Kinerja (Global List)
        Route::get('/laporan-kinerja', [\App\Http\Controllers\Admin\Ami\AmiLaporanKinerjaController::class, 'indexSk'])->name('admin.ami.laporan-kinerja.list');

        // SK Auditor
        Route::prefix('sk-auditor')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'index'])->name('admin.ami.sk-auditor.index');
            Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'data'])->name('admin.ami.sk-auditor.data');
            Route::get('/create', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'create'])->name('admin.ami.sk-auditor.create');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'store'])->name('admin.ami.sk-auditor.store');
            Route::get('/export-excel', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'exportExcel'])->name('admin.ami.sk-auditor.export-excel');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'show'])->name('admin.ami.sk-auditor.show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'edit'])->name('admin.ami.sk-auditor.edit');
            Route::put('/{id}/update', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'update'])->name('admin.ami.sk-auditor.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiSkAuditorController::class, 'delete'])->name('admin.ami.sk-auditor.delete');

            // Indikator per SK Auditor (admin/lpm only)
            Route::prefix('{skId}/indikator')->middleware('role:admin,lpm')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'index'])->name('admin.ami.indikator.index');
                Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'data'])->name('admin.ami.indikator.data');
                Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'store'])->name('admin.ami.indikator.store');
                Route::put('/update', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'update'])->name('admin.ami.indikator.update');
                Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'delete'])->name('admin.ami.indikator.delete');
                Route::get('/export-excel', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'exportExcel'])->name('admin.ami.indikator.export-excel');
                Route::post('/import-excel', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'importExcel'])->name('admin.ami.indikator.import-excel');
                Route::get('/rubrik/{id}', [\App\Http\Controllers\Admin\Ami\AmiIndikatorController::class, 'getRubrik'])->name('admin.ami.indikator.rubrik');
            });
        });

        // Evaluasi Diri
        Route::prefix('evaluasi-diri')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiEvaluasiDiriController::class, 'index'])->name('admin.ami.evaluasi-diri.index');
            Route::get('/{skId}', [\App\Http\Controllers\Admin\Ami\AmiEvaluasiDiriController::class, 'show'])->name('admin.ami.evaluasi-diri.show');
            Route::post('/save-jawaban', [\App\Http\Controllers\Admin\Ami\AmiEvaluasiDiriController::class, 'saveJawaban'])->name('admin.ami.evaluasi-diri.save-jawaban');
            Route::post('/upload-file', [\App\Http\Controllers\Admin\Ami\AmiEvaluasiDiriController::class, 'uploadFile'])->name('admin.ami.evaluasi-diri.upload-file');
            Route::delete('/delete-file', [\App\Http\Controllers\Admin\Ami\AmiEvaluasiDiriController::class, 'deleteFile'])->name('admin.ami.evaluasi-diri.delete-file');
            Route::get('/{skId}/progress', [\App\Http\Controllers\Admin\Ami\AmiEvaluasiDiriController::class, 'getProgress'])->name('admin.ami.evaluasi-diri.progress');
        });

        // Asesmen Auditor
        Route::prefix('asesmen-auditor')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiAsesmenController::class, 'index'])->name('admin.ami.asesmen.index');
            Route::get('/{skId}', [\App\Http\Controllers\Admin\Ami\AmiAsesmenController::class, 'show'])->name('admin.ami.asesmen.show');
            Route::post('/save-skor', [\App\Http\Controllers\Admin\Ami\AmiAsesmenController::class, 'saveSkor'])->name('admin.ami.asesmen.save-skor');
            Route::get('/{skId}/progress', [\App\Http\Controllers\Admin\Ami\AmiAsesmenController::class, 'getProgress'])->name('admin.ami.asesmen.progress');
            Route::post('/{skId}/finalize', [\App\Http\Controllers\Admin\Ami\AmiAsesmenController::class, 'finalize'])->name('admin.ami.asesmen.finalize');
            Route::post('/upload-file', [\App\Http\Controllers\Admin\Ami\AmiAsesmenController::class, 'uploadFile'])->name('admin.ami.asesmen.upload-file');
            Route::delete('/delete-file', [\App\Http\Controllers\Admin\Ami\AmiAsesmenController::class, 'deleteFile'])->name('admin.ami.asesmen.delete-file');
        });

        // Temuan Audit
        Route::prefix('temuan/{skId}')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiTemuanAuditController::class, 'index'])->name('admin.ami.temuan.index');
            Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiTemuanAuditController::class, 'data'])->name('admin.ami.temuan.data');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiTemuanAuditController::class, 'store'])->name('admin.ami.temuan.store');
            Route::put('/update', [\App\Http\Controllers\Admin\Ami\AmiTemuanAuditController::class, 'update'])->name('admin.ami.temuan.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiTemuanAuditController::class, 'delete'])->name('admin.ami.temuan.delete');
        });

        // Hasil Temuan
        Route::prefix('hasil-temuan/{skId}')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'index'])->name('admin.ami.hasil-temuan.index');
            Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'data'])->name('admin.ami.hasil-temuan.data');
            Route::get('/create', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'create'])->name('admin.ami.hasil-temuan.create');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'store'])->name('admin.ami.hasil-temuan.store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'show'])->name('admin.ami.hasil-temuan.show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'edit'])->name('admin.ami.hasil-temuan.edit');
            Route::put('/{id}/update', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'update'])->name('admin.ami.hasil-temuan.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiHasilTemuanController::class, 'delete'])->name('admin.ami.hasil-temuan.delete');
        });

        // Laporan Kinerja
        Route::prefix('laporan-kinerja/{skId}')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiLaporanKinerjaController::class, 'index'])->name('admin.ami.laporan-kinerja.index');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiLaporanKinerjaController::class, 'store'])->name('admin.ami.laporan-kinerja.store');
        });

        // RTM (Rapat Tinjauan Manajemen)
        Route::prefix('rtm')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'index'])->name('admin.ami.rtm.index');
            Route::get('/data', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'data'])->name('admin.ami.rtm.data');
            Route::get('/create', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'create'])->name('admin.ami.rtm.create');
            Route::post('/store', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'store'])->name('admin.ami.rtm.store');
            Route::get('/{id}', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'show'])->name('admin.ami.rtm.show');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'edit'])->name('admin.ami.rtm.edit');
            Route::put('/{id}/update', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'update'])->name('admin.ami.rtm.update');
            Route::delete('/delete', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'delete'])->name('admin.ami.rtm.delete');
            Route::post('/{id}/save-keputusan', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'saveKeputusan'])->name('admin.ami.rtm.save-keputusan');
            Route::post('/{id}/update-status-tl', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'updateStatusTl'])->name('admin.ami.rtm.update-status-tl');
            Route::post('/{id}/change-status', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'changeStatus'])->name('admin.ami.rtm.change-status');
            Route::post('/{id}/sahkan', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'sahkan'])->name('admin.ami.rtm.sahkan');
            Route::post('/{id}/tutup', [\App\Http\Controllers\Admin\Ami\AmiRtmController::class, 'tutup'])->name('admin.ami.rtm.tutup');
        });
    });
});

Route::prefix('operasi')->group(function () {
    // admin
    Route::prefix('daftar-tugas')->group(function () {
        Route::get('/', [DaftarTugasController::class, 'show'])->name('operasi.daftarTugas.show');
        Route::post('/tambah', [DaftarTugasController::class, 'tambah'])->name('operasi.daftarTugas.tambah');
        Route::post('/edit', [DaftarTugasController::class, 'edit'])->name('operasi.daftarTugas.edit');
        Route::get('/jumlah-halaman', [DaftarTugasController::class, 'jumlahHalaman'])->name('operasi.daftarTugas.jumlahHalaman');
        Route::get('/{offset}', [DaftarTugasController::class, 'daftarTugas'])->name('operasi.daftarTugas');
        Route::get('/{id}/edit/{status}', [DaftarTugasController::class, 'check'])->name('operasi.daftarTugas.check');
        Route::post('/{id}/hapus', [DaftarTugasController::class, 'hapus'])->name('operasi.daftarTugas.hapus');
    });

    Route::prefix('kalender')->group(function () {
        Route::get('/', [KalenderController::class, 'show'])->name('operasi.kalender');
        Route::post('/tambah', [KalenderController::class, 'tambah'])->name('operasi.kalender.tambah');
        Route::post('/{id}/edit', [KalenderController::class, 'edit'])->name('operasi.kalender.edit');
        Route::delete('/{id}/hapus', [KalenderController::class, 'hapus'])->name('operasi.kalender.hapus');
    });
});

// Testing routes
// Route::get('/testing/create-unit-accounts', [TestingController::class, 'createUnitAccounts'])->name('testing.create-unit-accounts');
