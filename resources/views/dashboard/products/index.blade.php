@extends('dashboard.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@section('title')
    المنتجات
@stop

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                المنتجات</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')
 
{{-- show message success --}}
@if(Session::has('success'))
<div class="row mr-2 ml-2">
    <button type="text" class="btn btn-lg btn-block btn-outline-success mb-2"
        id="type-error">{{Session::get('success')}}
    </button>
</div>
@endif


<!-- row -->
<div class="row">

    <div class="col-xl-12">
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    {{-- @can('اضافة قسم') --}}
                    <button type="button"  style="margin-top: 50px" class="btn btn-primary" data-toggle="modal"
                        data-target="#AddModal">
                        اضافه منتج  
                    </button>
                    {{-- @endcan --}}
                </div>
            </div>

            {{-- Show Data --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table key-buttons text-md-nowrap" data-page-length='50'
                        style="text-align: center">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">#</th>
                                <th class="border-bottom-0">اسم المنتج</th>
                                <th class="border-bottom-0">الوصف</th>
                                <th class="border-bottom-0">القسم التابع له</th>
                                <th class="border-bottom-0">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($products))
                                @foreach ($products as $key => $product)
                                    <tr>
                                        
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $product->Product_name }}</td>
                                        <td>{{ $product->description }}</td>
                                        <td>{{ $product->category->category_name }}</td>
                                        <td>
                                            {{-- @can('تعديل قسم') --}}
                                            
                                                    <a data-toggle="modal" data-effect="effect-scale"
                                                    data-proid="{{ $product->id }}"  data-catid="{{ $product->category_id }}" data-product_name="{{ $product->Product_name }}"
                                                    data-description="{{ $product->description }}"  title="تعديل" data-target="#EditModal" class="modal-effect btn btn-sm btn-info"><i class="las la-pen"></i></a>
                                            {{-- @endcan --}}

                                            {{-- @can('حذف قسم') --}}
                                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-id="{{ $product->id }}" data-product_name="{{ $product->product_name }}"
                                                    data-toggle="modal" href="#modaldemo9" title="حذف"><i
                                                        class="las la-trash"></i></a>
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach  
                            @endif
                          
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- ./Show Data --}}
        </div>
    </div>


    {{-- Modal Add product --}}
    <div class="modal" id="AddModal" name="AddModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">اضافة منتج</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('products.store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="exampleInputEmail1">اسم المنتج</label>
                            <input type="text" class="form-control" id="product_name" name="product_name">
                            @if (Session::has('add_product') && $errors->has('product_name'))
                                <span class="text-danger">{{ $errors->first('product_name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            @if (Session::has('add_product') && $errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1"> الاقسام</label>
                            <select name="category_id" class="form-control">
                                <option value="">كل الاقسام  </option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}"> {{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @if (Session::has('add_product') && $errors->has('category_id'))
                                <span class="text-danger">{{ $errors->first('category_id') }}</span>
                            @endif
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">تاكيد</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Basic modal -->
    </div>
    {{-- ./Modal Add product --}}

   {{-- Modal Edit product --}}
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل المنتج</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    
                    @if(isset($product))
                    <form action="{{ route('products.update',$product->id)}}"  method="post" autocomplete="off">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="hidden" name="product_id" id="pro_id" value="">

                            <label for="recipient-name" class="col-form-label">اسم المنتج:</label>
                            <input class="form-control" name="product_name" id="product_name" type="text">
                            @if (Session::has('edit_product') && $errors->has('product_name'))
                                <span class="text-danger">{{ $errors->first('product_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">ملاحظات:</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                            @if (Session::has('edit_product') && $errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>الاقسام</label>
                            <select name="category_id" class="form-control">
                                <option value="">كل الاقسام</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">تاكيد</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    {{-- ./Modal Edit product --}}

    {{--  Modal Delete product --}}
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف المنتج</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="products/destroy" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <p>هل انت متاكد من عملية الحذف ؟</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="product_name" id="product_name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    {{-- ./Modal Delete product --}}

    @php
        global $value_add;
        $value_add = Session::has('add_product')? Session::has('add_product'):'null';
        global $value_edit;
        $value_edit = Session::has('edit_product')? Session::has('edit_product'):'null';
    @endphp

    <!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<!--Internal  Datatable js -->
<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
<script src="{{ URL::asset('assets/js/modal.js') }}"></script>

<script>
    
    if( {{$value_add}} != null)
        {
           $('#AddModal').modal('show');
        }   
        if( {{$value_edit}} != null)
        {
           $('#EditModal').modal('show');
        }  

         $('#EditModal').on('show.bs.modal', function (event) {
             var button = $(event.relatedTarget)
             var pro_id = button.data('proid')
             var cat_id  = button.data('catid')
            //  console.log(cat_id);
             var product_name = button.data('product_name')
             var description = button.data('description')
            
             var modal = $(this)
             modal.find('.modal-body #product_name').val(product_name);
             modal.find('.modal-body #description').val(description);
             modal.find('.modal-body #pro_id').val(pro_id);
             modal.find('.modal-body #catid').val(cat_id);
         }); 

        $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        // console.log(id);
        var product_name = button.data('product_name')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #product_name').val(product_name);
    })


        
</script>
 

@endsection
