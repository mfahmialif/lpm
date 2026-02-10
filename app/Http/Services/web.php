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
use App\Http\Controllers\Admin\AmiPeriodController;
use App\Http\Controllers\Admin\AmiCategoryController;
use App\Http\Controllers\Admin\AmiTargetController;
use App\Http\Controllers\Admin\AmiAuditorDecreeController;
use App\Http\Controllers\Admin\AmiAssignmentLetterController;
use App\Http\Controllers\Admin\AmiPerformanceReportController;
use App\Http\Controllers\Admin\AmiSelfEvaluationController;
use App\Http\Controllers\Admin\AmiSelfAssessmentController;
use App\Http\Controllers\Admin\AmiAuditorAssessmentController;
use App\Http\Controllers\Admin\AmiAuditFindingController;
use App\Http\Controllers\Admin\AmiFindingResultController;
use App\Http\Controllers\Admin\AmiRtmController;
use App\Http\Controllers\Admin\AmiOfficialReportController;
use App\Http\Controllers\Admin\AmiFinalResultController;
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
use App\Http\Controllers\Admin\DocumentSotkUiiDalwaController;
use App\Http\Controllers\Admin\DocumentKurikulumProdiController;
use App\Http\Controllers\Admin\UnitDokumentController;
use App\Http\Controllers\Admin\DokumentImplementasiSpmiPddiktiController;


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
Route::post('/set-mode', [RootController::class, 'setAmiModeUser'])->name('root.set-mode');
Route::get('/about', [AboutController::class, 'index'])->name('about.index');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::get('/detail/{slug}', [NewsController::class, 'detail'])->name('news.detail');
    Route::post('/detail/{news}/storeComment', [NewsController::class, 'storeComment'])->name('news.storeComment');
});

Route::prefix('accreditation')->group(function () {
    Route::get('/', [HomeAccreditationController::class, 'index'])->name('accreditation.index');
    Route::get('/detail/{accreditation}', [HomeAccreditationController::class, 'detail'])->name('accreditation.detail');
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
    Route::get('/data/keputusan-rektor', [App\Http\Controllers\InstitutionDocumentController::class, 'dataKeputusanRektor'])->name('institution-document.data.keputusan-rektor');
    Route::get('/data/spmi', [App\Http\Controllers\InstitutionDocumentController::class, 'dataSpmi'])->name('institution-document.data.spmi');
    Route::get('/data/siklus-ppepp', [App\Http\Controllers\InstitutionDocumentController::class, 'dataSiklusPpepp'])->name('institution-document.data.siklus-ppepp');
    Route::get('/data/statuta', [App\Http\Controllers\InstitutionDocumentController::class, 'dataStatuta'])->name('institution-document.data.statuta');
    Route::get('/data/renstra', [App\Http\Controllers\InstitutionDocumentController::class, 'dataRenstra'])->name('institution-document.data.renstra');
    Route::get('/data/renop', [App\Http\Controllers\InstitutionDocumentController::class, 'dataRenop'])->name('institution-document.data.renop');
    Route::get('/data/sotk', [App\Http\Controllers\InstitutionDocumentController::class, 'dataSotk'])->name('institution-document.data.sotk');
    Route::get('/data/kurikulum-prodi', [App\Http\Controllers\InstitutionDocumentController::class, 'dataKurikulumProdi'])->name('institution-document.data.kurikulum-prodi');
    Route::get('/data/laporan-benchmarking', [App\Http\Controllers\InstitutionDocumentController::class, 'dataLaporanBenchmarking'])->name('institution-document.data.laporan-benchmarking');
    Route::get('/data/laporan-evaluasi-ppepp', [App\Http\Controllers\InstitutionDocumentController::class, 'dataLaporanEvaluasiPpepp'])->name('institution-document.data.laporan-evaluasi-ppepp');

    // API Routes untuk External API (Buku, Jurnal, Prosiding, Artikel)
    Route::get('/data/buku', [App\Http\Controllers\InstitutionDocumentController::class, 'dataBuku'])->name('institution-document.data.buku');
    Route::get('/data/jurnal', [App\Http\Controllers\InstitutionDocumentController::class, 'dataJurnal'])->name('institution-document.data.jurnal');
    Route::get('/data/prosiding', [App\Http\Controllers\InstitutionDocumentController::class, 'dataProsiding'])->name('institution-document.data.prosiding');
    Route::get('/data/artikel', [App\Http\Controllers\InstitutionDocumentController::class, 'dataArtikel'])->name('institution-document.data.artikel');
    Route::get('/data/rps', [App\Http\Controllers\InstitutionDocumentController::class, 'dataRps'])->name('institution-document.data.rps');

    // Download Route (tanpa storage:link)
    Route::get('/download/{path}', [App\Http\Controllers\InstitutionDocumentController::class, 'download'])->where('path', '.*')->name('institution-document.download');
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

    Route::prefix('ami-period')->middleware('role:admin')->group(function () {
        Route::get('/', [AmiPeriodController::class, 'index'])->name('admin.ami-period.index');
        Route::get('/data', [AmiPeriodController::class, 'data'])->name('admin.ami-period.data');
        Route::post('/store', [AmiPeriodController::class, 'store'])->name('admin.ami-period.store');
        Route::put('/update', [AmiPeriodController::class, 'update'])->name('admin.ami-period.update');
        Route::delete('/delete', [AmiPeriodController::class, 'delete'])->name('admin.ami-period.delete');
    });

    Route::prefix('ami-category')->middleware('role:admin')->group(function () {
        Route::get('/', [AmiCategoryController::class, 'index'])->name('admin.ami-category.index');
        Route::get('/data', [AmiCategoryController::class, 'data'])->name('admin.ami-category.data');
        Route::post('/store', [AmiCategoryController::class, 'store'])->name('admin.ami-category.store');
        Route::put('/update', [AmiCategoryController::class, 'update'])->name('admin.ami-category.update');
        Route::delete('/delete', [AmiCategoryController::class, 'delete'])->name('admin.ami-category.delete');
    });

    Route::prefix('ami-target')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiTargetController::class, 'index'])->name('admin.ami-target.index');
        Route::get('/data', [AmiTargetController::class, 'data'])->name('admin.ami-target.data');
        Route::get('/add', [AmiTargetController::class, 'add'])->name('admin.ami-target.add');
        Route::post('/store', [AmiTargetController::class, 'store'])->name('admin.ami-target.store');
        Route::get('/edit/{amiTarget}', [AmiTargetController::class, 'edit'])->name('admin.ami-target.edit');
        Route::put('/update/{amiTarget}', [AmiTargetController::class, 'update'])->name('admin.ami-target.update');
        Route::delete('/delete', [AmiTargetController::class, 'delete'])->name('admin.ami-target.delete');
    });

    Route::prefix('ami-auditor-decree')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiAuditorDecreeController::class, 'index'])->name('admin.ami-auditor-decree.index');
        Route::get('/data', [AmiAuditorDecreeController::class, 'data'])->name('admin.ami-auditor-decree.data');
        Route::get('/add', [AmiAuditorDecreeController::class, 'add'])->name('admin.ami-auditor-decree.add');
        Route::post('/store', [AmiAuditorDecreeController::class, 'store'])->name('admin.ami-auditor-decree.store');
        Route::get('/edit/{amiAuditorDecree}', [AmiAuditorDecreeController::class, 'edit'])->name('admin.ami-auditor-decree.edit');
        Route::put('/update/{amiAuditorDecree}', [AmiAuditorDecreeController::class, 'update'])->name('admin.ami-auditor-decree.update');
        Route::delete('/delete', [AmiAuditorDecreeController::class, 'delete'])->name('admin.ami-auditor-decree.delete');
    });

    Route::prefix('ami-assignment-letter')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiAssignmentLetterController::class, 'index'])->name('admin.ami-assignment-letter.index');
        Route::get('/data', [AmiAssignmentLetterController::class, 'data'])->name('admin.ami-assignment-letter.data');
        Route::get('/add', [AmiAssignmentLetterController::class, 'add'])->name('admin.ami-assignment-letter.add');
        Route::post('/store', [AmiAssignmentLetterController::class, 'store'])->name('admin.ami-assignment-letter.store');
        Route::get('/edit/{amiAssignmentLetter}', [AmiAssignmentLetterController::class, 'edit'])->name('admin.ami-assignment-letter.edit');
        Route::put('/update/{amiAssignmentLetter}', [AmiAssignmentLetterController::class, 'update'])->name('admin.ami-assignment-letter.update');
        Route::delete('/delete', [AmiAssignmentLetterController::class, 'delete'])->name('admin.ami-assignment-letter.delete');
    });

    Route::prefix('ami-performance-report')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiPerformanceReportController::class, 'index'])->name('admin.ami-performance-report.index');
        Route::get('/data', [AmiPerformanceReportController::class, 'data'])->name('admin.ami-performance-report.data');
        Route::get('/add', [AmiPerformanceReportController::class, 'add'])->name('admin.ami-performance-report.add');
        Route::post('/store', [AmiPerformanceReportController::class, 'store'])->name('admin.ami-performance-report.store');
        Route::get('/edit/{amiPerformanceReport}', [AmiPerformanceReportController::class, 'edit'])->name('admin.ami-performance-report.edit');
        Route::put('/update/{amiPerformanceReport}', [AmiPerformanceReportController::class, 'update'])->name('admin.ami-performance-report.update');
        Route::delete('/delete', [AmiPerformanceReportController::class, 'delete'])->name('admin.ami-performance-report.delete');
    });

    Route::prefix('ami-self-evaluation')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiSelfEvaluationController::class, 'index'])->name('admin.ami-self-evaluation.index');
        Route::get('/data', [AmiSelfEvaluationController::class, 'data'])->name('admin.ami-self-evaluation.data');
        Route::get('/add', [AmiSelfEvaluationController::class, 'add'])->name('admin.ami-self-evaluation.add');
        Route::post('/store', [AmiSelfEvaluationController::class, 'store'])->name('admin.ami-self-evaluation.store');
        Route::get('/edit/{amiSelfEvaluation}', [AmiSelfEvaluationController::class, 'edit'])->name('admin.ami-self-evaluation.edit');
        Route::put('/update/{amiSelfEvaluation}', [AmiSelfEvaluationController::class, 'update'])->name('admin.ami-self-evaluation.update');
        Route::delete('/delete', [AmiSelfEvaluationController::class, 'delete'])->name('admin.ami-self-evaluation.delete');
    });

    Route::prefix('ami-self-assessment')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiSelfAssessmentController::class, 'index'])->name('admin.ami-self-assessment.index');
        Route::get('/data', [AmiSelfAssessmentController::class, 'data'])->name('admin.ami-self-assessment.data');
        Route::get('/add', [AmiSelfAssessmentController::class, 'add'])->name('admin.ami-self-assessment.add');
        Route::post('/store', [AmiSelfAssessmentController::class, 'store'])->name('admin.ami-self-assessment.store');
        Route::get('/edit/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'edit'])->name('admin.ami-self-assessment.edit');
        Route::put('/update/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'update'])->name('admin.ami-self-assessment.update');
        Route::delete('/delete', [AmiSelfAssessmentController::class, 'delete'])->name('admin.ami-self-assessment.delete');
        Route::get('/response/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'response'])->name('admin.ami-self-assessment.response');
        Route::post('/response/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'storeResponse'])->name('admin.ami-self-assessment.storeResponse');
        Route::get('/response/{amiSelfAssessment}/data', [AmiSelfAssessmentController::class, 'getResponses'])->name('admin.ami-self-assessment.getResponses');
        Route::put('/response/message/{responseId}', [AmiSelfAssessmentController::class, 'updateResponse'])->name('admin.ami-self-assessment.updateResponse');
        Route::delete('/response/message/{responseId}', [AmiSelfAssessmentController::class, 'deleteResponse'])->name('admin.ami-self-assessment.deleteResponse');
        Route::get('/indikator/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'indikator'])->name('admin.ami-self-assessment.indikator');
        Route::post('/indikator/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'storeIndicator'])->name('admin.ami-self-assessment.storeIndicator');
        Route::post('/indikator/score/{indicatorId}', [AmiSelfAssessmentController::class, 'storeScore'])->name('admin.ami-self-assessment.storeScore');
        Route::delete('/indikator/score/{scoreId}', [AmiSelfAssessmentController::class, 'deleteScore'])->name('admin.ami-self-assessment.deleteScore');
        Route::put('/indikator/score/{scoreId}', [AmiSelfAssessmentController::class, 'updateScore'])->name('admin.ami-self-assessment.updateScore');
        Route::get('/isi-indikator/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'isiIndikator'])->name('admin.ami-self-assessment.isiIndikator');
        Route::post('/isi-indikator/{amiSelfAssessment}', [AmiSelfAssessmentController::class, 'storeIsiIndikator'])->name('admin.ami-self-assessment.storeIsiIndikator');
    });

    Route::prefix('ami-auditor-assessment')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiAuditorAssessmentController::class, 'index'])->name('admin.ami-auditor-assessment.index');
        Route::get('/data', [AmiAuditorAssessmentController::class, 'data'])->name('admin.ami-auditor-assessment.data');
        Route::get('/add', [AmiAuditorAssessmentController::class, 'add'])->name('admin.ami-auditor-assessment.add');
        Route::post('/store', [AmiAuditorAssessmentController::class, 'store'])->name('admin.ami-auditor-assessment.store');
        Route::get('/edit/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'edit'])->name('admin.ami-auditor-assessment.edit');
        Route::put('/update/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'update'])->name('admin.ami-auditor-assessment.update');
        Route::delete('/delete', [AmiAuditorAssessmentController::class, 'delete'])->name('admin.ami-auditor-assessment.delete');
        Route::get('/response/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'response'])->name('admin.ami-auditor-assessment.response');
        Route::post('/response/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'storeResponse'])->name('admin.ami-auditor-assessment.storeResponse');
        Route::get('/response/{amiAuditorAssessment}/data', [AmiAuditorAssessmentController::class, 'getResponses'])->name('admin.ami-auditor-assessment.getResponses');
        Route::put('/response/message/{responseId}', [AmiAuditorAssessmentController::class, 'updateResponse'])->name('admin.ami-auditor-assessment.updateResponse');
        Route::delete('/response/message/{responseId}', [AmiAuditorAssessmentController::class, 'deleteResponse'])->name('admin.ami-auditor-assessment.deleteResponse');
        Route::get('/indikator/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'indikator'])->name('admin.ami-auditor-assessment.indikator');
        Route::post('/indikator/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'storeIndicator'])->name('admin.ami-auditor-assessment.storeIndicator');
        Route::post('/indikator/score/{indicatorId}', [AmiAuditorAssessmentController::class, 'storeScore'])->name('admin.ami-auditor-assessment.storeScore');
        Route::delete('/indikator/score/{scoreId}', [AmiAuditorAssessmentController::class, 'deleteScore'])->name('admin.ami-auditor-assessment.deleteScore');
        Route::put('/indikator/score/{scoreId}', [AmiAuditorAssessmentController::class, 'updateScore'])->name('admin.ami-auditor-assessment.updateScore');
        Route::get('/isi-indikator/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'isiIndikator'])->name('admin.ami-auditor-assessment.isiIndikator');
        Route::post('/isi-indikator/{amiAuditorAssessment}', [AmiAuditorAssessmentController::class, 'storeIsiIndikator'])->name('admin.ami-auditor-assessment.storeIsiIndikator');
    });

    Route::prefix('ami-audit-finding')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiAuditFindingController::class, 'index'])->name('admin.ami-audit-finding.index');
        Route::get('/data', [AmiAuditFindingController::class, 'data'])->name('admin.ami-audit-finding.data');
        Route::get('/add', [AmiAuditFindingController::class, 'add'])->name('admin.ami-audit-finding.add');
        Route::post('/store', [AmiAuditFindingController::class, 'store'])->name('admin.ami-audit-finding.store');
        Route::get('/edit/{amiAuditFinding}', [AmiAuditFindingController::class, 'edit'])->name('admin.ami-audit-finding.edit');
        Route::put('/update/{amiAuditFinding}', [AmiAuditFindingController::class, 'update'])->name('admin.ami-audit-finding.update');
        Route::delete('/delete', [AmiAuditFindingController::class, 'delete'])->name('admin.ami-audit-finding.delete');
    });

    Route::prefix('ami-finding-result')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiFindingResultController::class, 'index'])->name('admin.ami-finding-result.index');
        Route::get('/data', [AmiFindingResultController::class, 'data'])->name('admin.ami-finding-result.data');
        Route::get('/add', [AmiFindingResultController::class, 'add'])->name('admin.ami-finding-result.add');
        Route::post('/store', [AmiFindingResultController::class, 'store'])->name('admin.ami-finding-result.store');
        Route::get('/edit/{amiFindingResult}', [AmiFindingResultController::class, 'edit'])->name('admin.ami-finding-result.edit');
        Route::put('/update/{amiFindingResult}', [AmiFindingResultController::class, 'update'])->name('admin.ami-finding-result.update');
        Route::delete('/delete', [AmiFindingResultController::class, 'delete'])->name('admin.ami-finding-result.delete');
    });

    Route::prefix('ami-rtm')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiRtmController::class, 'index'])->name('admin.ami-rtm.index');
        Route::get('/data', [AmiRtmController::class, 'data'])->name('admin.ami-rtm.data');
        Route::get('/add', [AmiRtmController::class, 'add'])->name('admin.ami-rtm.add');
        Route::post('/store', [AmiRtmController::class, 'store'])->name('admin.ami-rtm.store');
        Route::get('/edit/{amiRtm}', [AmiRtmController::class, 'edit'])->name('admin.ami-rtm.edit');
        Route::put('/update/{amiRtm}', [AmiRtmController::class, 'update'])->name('admin.ami-rtm.update');
        Route::delete('/delete', [AmiRtmController::class, 'delete'])->name('admin.ami-rtm.delete');
    });

    Route::prefix('ami-official-report')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiOfficialReportController::class, 'index'])->name('admin.ami-official-report.index');
        Route::get('/data', [AmiOfficialReportController::class, 'data'])->name('admin.ami-official-report.data');
        Route::get('/add', [AmiOfficialReportController::class, 'add'])->name('admin.ami-official-report.add');
        Route::post('/store', [AmiOfficialReportController::class, 'store'])->name('admin.ami-official-report.store');
        Route::get('/edit/{amiOfficialReport}', [AmiOfficialReportController::class, 'edit'])->name('admin.ami-official-report.edit');
        Route::put('/update/{amiOfficialReport}', [AmiOfficialReportController::class, 'update'])->name('admin.ami-official-report.update');
        Route::delete('/delete', [AmiOfficialReportController::class, 'delete'])->name('admin.ami-official-report.delete');
    });

    Route::prefix('ami-final-result')->middleware('role:admin,unit')->group(function () {
        Route::get('/', [AmiFinalResultController::class, 'index'])->name('admin.ami-final-result.index');
        Route::get('/data', [AmiFinalResultController::class, 'data'])->name('admin.ami-final-result.data');
        Route::get('/add', [AmiFinalResultController::class, 'add'])->name('admin.ami-final-result.add');
        Route::post('/store', [AmiFinalResultController::class, 'store'])->name('admin.ami-final-result.store');
        Route::get('/edit/{amiFinalResult}', [AmiFinalResultController::class, 'edit'])->name('admin.ami-final-result.edit');
        Route::put('/update/{amiFinalResult}', [AmiFinalResultController::class, 'update'])->name('admin.ami-final-result.update');
        Route::delete('/delete', [AmiFinalResultController::class, 'delete'])->name('admin.ami-final-result.delete');
    });


    Route::prefix('user')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
        Route::get('/data', [UserController::class, 'data'])->name('admin.user.data');
        Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
        Route::put('/update', [UserController::class, 'update'])->name('admin.user.update');
        Route::delete('/delete', [UserController::class, 'delete'])->name('admin.user.delete');
    });
    // Profil
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('admin.profile.index');
        Route::put('/update', [ProfileController::class, 'update'])->name('admin.profile.update');
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
