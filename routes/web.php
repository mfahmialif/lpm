<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PlayController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AddActivityController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProdiController;
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

Route::prefix('accreditation-certificate')->group(function(){
    Route::get('/', [AccreditationDocumentController::class, 'index'])->name('accreditation-certificate.index'); // List
});

Route::prefix('addactivity/{code}')->group(function () {
    Route::get('/', [AddActivityController::class, 'index'])->name('addactivity.index');
    Route::post('/store', [AddActivityController::class, 'store'])->name('addactivity.store');
    Route::post('/storeDokumen', [AddActivityController::class, 'storeDokumen'])->name('addactivity.storeDokumen');
    Route::post('/destroyDokumen', [AddActivityController::class, 'destroyDokumen'])->name('addactivity.destroyDokumen');
    Route::get('/getDataDokumen', [AddActivityController::class, 'getDataDokumen'])->name('addactivity.getDataDokumen');
});

Route::prefix('play')->group(function () {
    Route::get('/', [PlayController::class, 'index'])->name('play.index');
    Route::get('/getLevel', [PlayController::class, 'getLevel'])->name('play.getLevel');
    Route::post('/saveScore', [PlayController::class, 'saveScore'])->name('play.saveScore');
    Route::post('/saveScoreFreestyle', [PlayController::class, 'saveScoreFreestyle'])->name('play.saveScoreFreestyle');
});

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

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
