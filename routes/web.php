<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\BonLivraisonController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\RhController;
use App\Http\Controllers\RemboursementController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HomeContentController;
use App\Http\Controllers\DevisProductController;
use App\Http\Controllers\FactureProductController;
use App\Http\Controllers\CongeController;
// routes/web.php

Route::get('/clients/search', [ClientController::class,'search'])
     ->name('clients.search');


Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('/register/step1', [RegisteredUserController::class, 'step1']);
    Route::post('/register/step2', [RegisteredUserController::class, 'step2']);
    Route::post('/register/step3', [RegisteredUserController::class, 'step3']);
    Route::post('/register/finish', [RegisteredUserController::class,'finish'])->name('register.finish.post');
});


Route::get('/', function () {
    return view('home');
});






// Customer dashboard
Route::get('/customer/dashboard', function () {
    return view('customer.dashboard');
})->middleware(['auth'])->name('customer.dashboard');

// Admin dashboard and related routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/voir_users/{id}', [AdminController::class, 'show']);
    Route::get('/stats', [AdminController::class, 'stats'])->name('admin.stats');
    Route::get('/users/filter', [AdminController::class, 'filterUsers'])->name('admin.users.filter');
    Route::post('/users/bulk-action', [AdminController::class, 'bulkAction'])->name('admin.users.bulk-action');

    Route::get('/activities', [AdminController::class, 'activities'])->name('admin.activities');
    Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::post('/messages/{id}/read', [AdminController::class, 'markMessageAsRead'])->name('admin.messages.read');
    Route::post('/customers/{id}/toggle-status', [AdminController::class, 'toggleStatus']);
    Route::delete('/customers/{id}/delete', [AdminController::class, 'destroy']);
    Route::post('/profile/update', [AdminController::class, 'updateProfile']);
    
    // Home content editor routes
    Route::get('/home-content', [HomeContentController::class, 'getContent'])->name('admin.home-content.get');
    Route::post('/home-content', [HomeContentController::class, 'updateContent'])->name('admin.home-content.update');
    Route::post('/home-content/upload-image', [HomeContentController::class, 'uploadImage'])->name('admin.home-content.upload-image');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update', [CustomerController::class, 'updateProfile'])->name('profile.update.ajax');
    
    // New settings routes
    Route::post('/profile/change-password', [CustomerController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/update-notifications', [CustomerController::class, 'updateNotifications'])->name('profile.update-notifications');
    Route::post('/profile/update-preferences', [CustomerController::class, 'updatePreferences'])->name('profile.update-preferences');
    Route::delete('/profile/delete', [CustomerController::class, 'deleteAccount'])->name('profile.delete-account');
});
Route::get('/dashboard', function () {
    return redirect()->route('customer.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware(['auth'])->group(function () {
    // Client routes
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
    // Facture routes
    Route::delete('/factures/{facture}', [FactureController::class, 'destroy'])->name('factures.destroy');
    // Bon de livraison routes
    Route::delete('/bon_livraison/{bonLivraison}', [BonLivraisonController::class, 'destroy'])->name('bon_livraison.destroy');
    Route::get('/bon_livraison/{bonLivraison}/print', [BonLivraisonController::class, 'print'])->name('bon_livraison.print');
    // Component loading routes
    Route::get('/dashboard/component/{view}', function($view, Request $request) {
        // Split the view path into segments
        $segments = explode('/', $view);
        
        // Determine which controller to use based on the first segment
        $firstSegment = $segments[0];
        
        switch($firstSegment) {
            case 'fournisseurs':
                return app(FournisseurController::class)->loadComponent($view, $request);
            case 'bon_livraison':
                return app(BonLivraisonController::class)->loadComponent($view, $request);
            case 'stock':
                return app(StockController::class)->loadComponent($view, $request);
            case 'factures':
                return app(FactureController::class)->loadComponent($view, $request);
            case 'devis':
                return app(DevisController::class)->loadComponent($view, $request);
            case 'rh':
                if (str_contains($view, 'conges')) {
                    return app(CongeController::class)->loadComponent($view, $request);
                }
                return app(RhController::class)->loadComponent($view, $request);
            case 'profil_customer':
            case 'parametres':
            case 'edit_profil_customer':
                return app(CustomerController::class)->loadComponent($view, $request);
            case 'clients':
            default:
                return app(CustomerController::class)->loadComponent($view, $request);
        }
    })->where('view', '.*')->name('load.component');
    
    // Client routes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])
     ->name('clients.destroy');

    
    // Supplier routes
    Route::get('/fournisseurs', [FournisseurController::class, 'index'])->name('fournisseurs.index');
    Route::post('/fournisseurs', [FournisseurController::class, 'store'])->name('fournisseurs.store');
    Route::get('/fournisseurs/export', [FournisseurController::class, 'export'])->name('fournisseurs.export');
    Route::get('/fournisseur/{id}/details', [FournisseurController::class, 'details'])
        ->name('fournisseur.details');
    Route::get('/fournisseur/{id}/voir', [FournisseurController::class, 'voir'])
        ->name('fournisseur.voir');
    Route::put('/fournisseur/{id}', [FournisseurController::class, 'update'])
        ->name('fournisseur.update');

    // Add BonLivraison routes
    Route::prefix('bonlivraison')->group(function () {
        Route::get('/', [BonLivraisonController::class, 'index'])->name('bonlivraison.index');
        Route::post('/', [BonLivraisonController::class, 'store'])->name('bonlivraison.store');
        Route::get('/export', [BonLivraisonController::class, 'export'])->name('bonlivraison.export');
        Route::get('/{bonLivraison}', [BonLivraisonController::class, 'show'])->name('bonlivraison.show');
        Route::get('/{bonLivraison}/print', [BonLivraisonController::class, 'print'])->name('bonlivraison.print');
        Route::put('/{bonLivraison}', [BonLivraisonController::class, 'update'])->name('bonlivraison.update');
        Route::delete('/{bonLivraison}', [BonLivraisonController::class, 'destroy'])->name('bonlivraison.destroy');
    });

    // Add alias route for bons_livraison.export
    Route::get('/bons_livraison/export', [BonLivraisonController::class, 'export'])->name('bons_livraison.export');

    // Stock Management Routes
    Route::prefix('stock')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('stock.index');
        Route::get('/export', [StockController::class, 'export'])->name('stock.export');
    });
    

    // Product filter routes
Route::post('/products/filter', [StockController::class, 'filterProducts'])
->name('products.filter')
->middleware('web');

Route::get('/products/search', [StockController::class, 'searchProducts'])
->name('products.search')
->middleware('web');
    //article service route
Route::post('/stock/produits', [StockController::class, 'storeProduct'])
     ->name('products.store')
     ->middleware('web');
Route::get('/stock/products', [StockController::class, 'indexProduct'])
     ->name('products.index')
     ->middleware('web');
    // Category  Routes

Route::get('/stock/categories', [StockController::class, 'indexCategory'])
     ->name('categories.index')
     ->middleware('web');


Route::post('/stock/categories', [StockController::class, 'storeCategory'])
     ->name('categories.store')
     ->middleware('web');

Route::get('/stock/categories/{id}/edit', [StockController::class, 'editCategory'])
     ->name('categories.edit')
     ->middleware('web');

Route::put('/stock/categories/{id}', [StockController::class, 'updateCategory'])
     ->name('categories.update')
     ->middleware('web');

Route::delete('/stock/categories/{id}', [StockController::class, 'deleteCategory'])
     ->name('categories.destroy')
     ->middleware('web');

// Product management routes
Route::get('/products/{id}', [StockController::class, 'getProduct'])
     ->name('products.get')
     ->middleware('web');

Route::post('/products/{id}', [StockController::class, 'updateProduct'])
     ->name('products.update')
     ->middleware('web');

Route::delete('/products/{id}', [StockController::class, 'deleteProduct'])
     ->name('products.delete')
     ->middleware('web');

    // Reimbursement routes
    Route::get('/historique-remboursements', [RemboursementController::class, 'index'])->name('remboursements.index');
    Route::get('/remboursements/{remboursement}', [RemboursementController::class, 'show'])->name('remboursements.show');
    Route::post('/remboursements/filter', [RemboursementController::class, 'filter'])->name('remboursements.filter');

    // HR routes
    Route::prefix('rh')->group(function () {
        Route::post('/salaries', [RhController::class, 'store'])->name('rh.salaries.store');
        Route::put('/salaries/{employee}', [RhController::class, 'update'])->name('rh.salaries.update');
        Route::delete('/salaries/{employee}', [RhController::class, 'destroy'])->name('rh.salaries.destroy');
        Route::get('/salaries/search', [RhController::class, 'search'])->name('rh.salaries.search');
        Route::get('/salaries/export', [RhController::class, 'export'])->name('rh.salaries.export');
        Route::get('/component/{view}', [RhController::class, 'loadComponent'])->name('rh.component');
        Route::get('/nouveau-conge', [RhController::class, 'nouveauConge'])->name('rh.nouveau_conge');
    });
});



// Facture routes
Route::prefix('factures')->group(function () {
    Route::get('/', [FactureController::class, 'index'])->name('factures.index');
    Route::post('/', [FactureController::class, 'store'])->name('factures.store');
    Route::get('/export', [FactureController::class, 'export'])->name('factures.export');
    Route::get('/{facture}', [FactureController::class, 'show'])->name('factures.show');
    Route::put('/{facture}', [FactureController::class, 'update'])->name('factures.update');
    Route::delete('/{facture}', [FactureController::class, 'destroy'])->name('factures.destroy');
    Route::get('/{facture}/print', [FactureController::class, 'print'])->name('factures.print');
    Route::get('/component/{view}', [FactureController::class, 'loadComponent'])->name('factures.component');
});

// Add Facture Product routes
Route::prefix('facture-products')->group(function () {
    Route::post('/{facture}/products', [FactureProductController::class, 'store'])->name('facture-products.store');
    Route::put('/{facture}/products/{product}', [FactureProductController::class, 'update'])->name('facture-products.update');
    Route::delete('/{facture}/products/{product}', [FactureProductController::class, 'destroy'])->name('facture-products.destroy');
});

// Devis routes
Route::prefix('devis')->group(function () {
    Route::get('/', [DevisController::class, 'index'])->name('devis.index');
    Route::post('/', [DevisController::class, 'store'])->name('devis.store');
    Route::get('/export', [DevisController::class, 'export'])->name('devis.export');
    Route::get('/{devis}', [DevisController::class, 'show'])->name('devis.show');
    Route::put('/{devis}/edit', [DevisController::class, 'edit'])->name('devis.edit');
    Route::put('/{devis}', [DevisController::class, 'update'])->name('devis.update');
    Route::delete('/{devis}', [DevisController::class, 'destroy'])->name('devis.destroy');
    Route::post('/{devis}/convert', [DevisController::class, 'convertToFacture'])->name('devis.convert');
    Route::get('/{devis}/print', [DevisController::class, 'print'])->name('devis.print');
    Route::get('/component/{view}', [DevisController::class, 'loadComponent'])->name('devis.component');
});

// Devis Product Routes
Route::post('/devis-products', [DevisProductController::class, 'store']);
Route::put('/devis-products/{id}', [DevisProductController::class, 'update']);
Route::delete('/devis-products/{id}', [DevisProductController::class, 'destroy']);

// Contact Message Routes
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/admin/messages', [ContactMessageController::class, 'index'])->name('admin.messages.index');
Route::post('/admin/messages/{id}/read', [ContactMessageController::class, 'markAsRead'])->name('admin.messages.read');

// Conge routes
Route::prefix('conges')->group(function () {
    Route::get('/', [CongeController::class, 'index'])->name('conges.index');
    Route::post('/', [CongeController::class, 'store'])->name('conges.store');
    Route::get('/{conge}', [CongeController::class, 'show'])->name('conges.show');
    Route::put('/{conge}/update-status', [CongeController::class, 'updateStatus'])->name('conges.update-status');
    Route::delete('/{conge}', [CongeController::class, 'destroy'])->name('conges.destroy');
    Route::get('/export', [CongeController::class, 'export'])->name('conges.export');
    Route::get('/calendar', [CongeController::class, 'calendar'])->name('conges.calendar');
    Route::post('/filter', [CongeController::class, 'filter'])->name('conges.filter');
});

require __DIR__.'/auth.php';
