<?php

namespace App\Http\Controllers\Admin;

use App\models\InvoicesDetails;

use App\models\invoices;
use App\Models\InvoicesAttatchment;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class InvoicesDetailsController extends Controller
{

    public function show($id)
    {
        $invoices = Invoices::where('id',$id)->first();
        $details  = InvoicesDetails::where('invoice_id',$id)->get();
        $attachments  = InvoicesAttatchment::where('invoice_id',$id)->get();

        return view('dashboard.invoices.details_invoice',compact('invoices','details','attachments'));
    }


    public function destroy(Request $request)
    {
        $invoices = InvoicesAttatchment::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

     public function get_file($invoice_number,$file_name)

    {
        $contents= Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
        return response()->download( $contents);
    }



    public function view_file($invoice_number,$file_name)
    {
        $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
        return response()->file($files);
    }

    public function edit($id)

    {
        $invoices = Invoices::where('id',$id)->first();
        $details  = Invoices_Details::where('id_Invoice',$id)->get();
        $attachments  = Invoice_attachments::where('invoice_id',$id)->get();

        return view('invoices.details_invoice',compact('invoices','details','attachments'));
    }

}
