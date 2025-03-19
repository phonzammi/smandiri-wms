<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Distributors\DistributorComponent;
use App\Livewire\Items\IncomingTransactions\IncomingTransactionComponent;
use App\Livewire\Items\IncomingTransactions\ShowIncomingTransaction;
use App\Livewire\Items\ItemComponent;
use App\Livewire\Items\OutgoingTransactions\OutgoingTransactionComponent;
use App\Livewire\Items\OutgoingTransactions\ShowOutgoingTransaction;
use App\Livewire\ItemUnits\ItemUnitComponent;
use App\Livewire\Salesman\SalesmanComponent;
use App\Livewire\Stores\StoreComponent;
use App\Models\OutgoingTransaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('dashboard');
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/sales', SalesmanComponent::class)->name('sales');
    Route::get('/toko', StoreComponent::class)->name('stores');
    Route::get('/satuan', ItemUnitComponent::class)->name('item-units');
    Route::get('/distributor', DistributorComponent::class)->name('distributors');
    Route::get('/barang', ItemComponent::class)->name('items');
    Route::prefix("/barang-masuk")->group(function () {
        Route::get('/', IncomingTransactionComponent::class)->name('incoming-transactions');
        Route::get('/{incomingTransaction:invoice_code}/detail', ShowIncomingTransaction::class)->name('incoming-transactions.show');
    });
    Route::prefix("/barang-keluar")->group(function () {
        Route::get('/', OutgoingTransactionComponent::class)->name('outgoing-transactions');
        Route::get('/{outgoingTransaction:invoice_code}/detail', ShowOutgoingTransaction::class)->name('outgoing-transactions.show');
    });
    Route::prefix("/bon")->group(function () {
        Route::get('/{outgoingTransaction:invoice_code}/cetak-ori', function (OutgoingTransaction $outgoingTransaction) {
            if ($outgoingTransaction->status->isDraft()) {
                abort(403, "Pengisian transaksi belum selesai. Bon tidak dapat dicetak !");
            }
            $outgoingTransaction->loadMissing(['store', 'salesman', 'outgoing_items' => fn($query) => $query->with('itemUnit')]);

            return view('bills.export-ori', compact('outgoingTransaction'));
        })->name('bills.export-ori');
        Route::get('/{outgoingTransaction:invoice_code}/cetak', function (OutgoingTransaction $outgoingTransaction) {
            if ($outgoingTransaction->status->isDraft()) {
                abort(403, "Pengisian transaksi belum selesai. Bon tidak dapat dicetak !");
            }
            $outgoingTransaction->loadMissing(['store', 'salesman', 'outgoing_items' => fn($query) => $query->with('itemUnit')]);

            return view('bills.export', compact('outgoingTransaction'));
        })->name('bills.export');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
