<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoicesArchiveController extends Controller
{
    public function index()
    {
        $invoices = Invoices::onlyTrashed()->get();
        return view('dashboard.invoices.archive_invoices',compact('invoices'));
    }

    public function update(Request $request)
    {
         $id = $request->invoice_id;
         $flight = Invoices::withTrashed()->where('id', $id)->restore();
         session()->flash('restore_invoice');
         return redirect('admin/invoices');
    }


    public function destroy(Request $request)
    {
         $invoices = Invoices::withTrashed()->where('id',$request->invoice_id)->first();
         $invoices->forceDelete();
         session()->flash('delete_invoice');
         return redirect('admin/archive');

    }
}
