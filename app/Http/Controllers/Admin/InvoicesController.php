<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin;

use App\models\Category;
use App\Models\Invoices;
use Illuminate\Http\Request;
use App\Exports\InvoicesExport;
use App\Models\InvoicesDetails;
use App\Notifications\AddInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicesAttatchment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class InvoicesController extends Controller
{

    public function index()
    {
        $invoices = invoices::all();
        return view('dashboard.invoices.index', compact('invoices'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('dashboard.invoices.add_invoice', compact('categories'));
    }

    public function store(Request $request)
    {
        try{

            DB::beginTransaction();


            $invoices = Invoices::create([
                'invoice_number' => $request->invoice_number,
                'invoice_Date' => $request->invoice_Date,
                'Due_date' => $request->Due_date,
                'product' => $request->product,
                'category_id' => $request->category_id,
                'Amount_collection' => $request->Amount_collection,
                'Amount_Commission' => $request->Amount_Commission,
                'Discount' => $request->Discount,
                'Value_tax' => $request->Value_tax,
                'Rate_tax' => $request->Rate_tax,
                'Total' => $request->Total,
                'Status' => 'غير مدفوعة',
                'Value_Status' => 2,
                'note' => $request->note,
            ]);

            // dd($invoices);
            $invoice_id = Invoices::latest()->first()->id;
            // dd($invoice_id);
            InvoicesDetails::create([
                'invoice_id' => $invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'category_id' => $request->category_id,
                'Status' => 'غير مدفوعة',
                'Value_Status' => 2,
                'note' => $request->note,
                'user' => ( auth()->guard('admin')->user()->name),
            ]);

            // dd('true');

            if ($request->hasFile('pic')) {
                $invoice_id = Invoices::latest()->first()->id;
                $image = $request->file('pic');
                $file_name = $image->getClientOriginalName();
                $invoice_number = $request->invoice_number;

                $attachments = new InvoicesAttatchment();
                $attachments->file_name = $file_name;
                $attachments->invoice_number = $invoice_number;
                $attachments->Created_by =  auth()->guard('admin')->user()->name;
                $attachments->invoice_id = $invoice_id;
                $attachments->save();

                // move pic
                $imageName = $request->pic->getClientOriginalName();
                $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
                // dd('true');
            }




            DB::commit();

            $admin = Admin::first();



            return redirect()->route('invoices.index')->with(['success' => 'تم اضافه الفاتوره بنجاح  ']);

        }catch(\Exception $ex){
            dd($ex);
            DB::rollback();
            return redirect()->route('invoices.index')->with(['error' => '  هناك خطا ما      ']);
        }
    }


    public function show(Invoices $invoices,$id)
    {

        $invoices = Invoices::where('id', $id)->first();
        return view('dashboard.invoices.status_update', compact('invoices'));
    }


    public function edit(Invoices $invoices, $id)
    {
        $invoices = Invoices::where('id', $id)->first();
        $sections = Category::all();
        return view('dashboard.invoices.edit_invoice', compact('sections', 'invoices'));
    }

    public function update(Request $request, Invoices $invoices)
    {
        $invoices = Invoices::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'category_id' => $request->category_id,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_tax' => $request->Value_tax,
            'Rate_tax' => $request->Rate_tax,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        return redirect()->route('invoices.index')->with(['success' => 'تم تعديل الفاتوره بنجاح  ']);

    }


    public function destroy($id,Request $request)
    {
        $invoices = Invoices::find($id);
        $Details = InvoicesAttatchment::where('invoice_id', $id)->first();

         $id_page =$request->id_page;

        if (!$id_page==2) {

        if (!empty($Details->invoice_number)) {

            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }

        $invoices->forceDelete();
        session()->flash('delete_invoice');
        return redirect('admin/invoices');

        }

        else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('admin/archive');
        }


    }

    public function Status_Update($id, Request $request)
    {
        $invoices = Invoices::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            InvoicesDetails::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'category_id' => $request->category_id,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (auth()->guard('admin')->user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            InvoicesDetails::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'category_id' => $request->category_id,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (auth()->guard('admin')->user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/admin/invoices');

    }

    public function getproducts($id)
    {
        return $products = DB::table("products")->where("category_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }

    public function Invoice_Paid()
    {
        $invoices = Invoices::where('Value_Status', 1)->get();
        return view('dashboard.invoices.paid_invoices',compact('invoices'));
    }
    public function Invoice_unPaid()
    {
        $invoices = Invoices::where('Value_Status',2)->get();
        return view('dashboard.invoices.unpaid_invoices',compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoices::where('Value_Status',3)->get();
        return view('dashboard.invoices.partial_invoices',compact('invoices'));
    }
    public function Print_invoice($id)
    {
        $invoices = Invoices::where('id', $id)->first();
        return view('dashboard.invoices.print_invoices',compact('invoices'));
    }

    public function export()
    {

        return Excel::download(new InvoicesExport, 'قائمه الفواتير.xlsx');
        // return (new InvoicesExport)->download('invoices.csv', \Maatwebsite\Excel\Excel::CSV);
    }

}
