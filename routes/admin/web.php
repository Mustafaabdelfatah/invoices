<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin'], function () {
    Config::set('auth.defines','admin');

    Route::get('login','AdminAuth@login')->name('get.admin.login');
    Route::post('login','AdminAuth@dologin')->name('admin.login');


    Route::group(['middleware' => 'admin:admin'],function(){
        Route::get('/','DashboardController@index')->name('admin.dashboard');
        Route::any('logout','AdminAuth@logout');

        ######################### Begin Invoices Route ########################
        Route::resource('invoices', 'InvoicesController');
        ######################### End Invoices Route ########################

          ######################### Begin InvoicesDetails Route ########################

        Route::get('/InvoicesDetails/{id}', 'InvoicesDetailsController@show');

        Route::get('download/{invoice_number}/{file_name}', 'InvoicesDetailsController@get_file');

        Route::get('View_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@view_file');

        Route::post('delete_file', 'InvoicesDetailsController@destroy')->name('delete_file');


        ######################### End InvoicesDetails Route ########################

        // InvoiceAttachments
        Route::resource('invoiceAttachments', 'InvoiceAttachmentsController');
        // ./ InvoiceAttachments


        ######################### Begin Category Route ########################
        Route::resource('categories', 'CategoryController', [
        'only' => ['index', 'update', 'store' , 'destroy'] ]);
        ######################### End Category Route ########################

        ######################### Begin Product Route ########################
        Route::resource('products', 'ProductController', [
        'only' => ['index', 'update', 'store' , 'destroy'] ]);

        Route::get('/product/{id}', 'InvoicesController@getproducts');
        ######################### End Product Route ########################

        ######################### status ########################
        Route::get('/Status_show/{id}', 'InvoicesController@show')->name('Status_show');
        Route::post('/Status_Update/{id}', 'InvoicesController@Status_Update')->name('Status_Update');
        ######################### ./status ########################


        ######################### paid invoices ########################

        Route::get('Invoice_Paid','InvoicesController@Invoice_Paid');
        Route::get('Invoice_UnPaid','InvoicesController@Invoice_UnPaid');
        Route::get('Invoice_Partial','InvoicesController@Invoice_Partial');

        ######################### ./paid invoices ########################

        ######################### /Archive invoices ########################
        Route::resource('archive', 'InvoicesArchiveController');
        ######################### ./Archive invoices ########################

        Route::get('Print_invoice/{id}','InvoicesController@Print_invoice');

        Route::get('/InvoicesDetails/{id}', 'InvoicesDetailsController@edit');

        Route::get('export_invoices', 'InvoicesController@export');


    });



    // lang

    Route::get('lang/{lang}', function ($lang) {
        session()->has('lang')?session()->forget('lang'):'';
        $lang == 'ar'?session()->put('lang', 'ar'):session()->put('lang', 'en');
        return back();
    });
});




