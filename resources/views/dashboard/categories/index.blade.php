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
    الاقسام
@stop

@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الاعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                الاقسام</span>
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


 
@if(Session::has('errors'))
<div class="row mr-2 ml-2">
    <button type="text" class="btn btn-lg btn-block btn-outline-danger mb-2"
        id="type-error">{{Session::get('errors')}}
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
                        اضافه قسم  
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
                                <th class="border-bottom-0">اسم القسم</th>
                                <th class="border-bottom-0">الوصف</th>
                                <th class="border-bottom-0">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            
                            @foreach ($categories as $key => $category)
                                
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->description }}</td>
                                    
                                    <td>
                                        {{-- @can('تعديل قسم') --}}
                                           

                                                <a data-toggle="modal" data-effect="effect-scale"
                                                data-catid="{{ $category->id }}" data-category_name="{{ $category->category_name }}"
                                                data-description="{{ $category->description }}"  title="تعديل" data-target="#EditModal" class="modal-effect btn btn-sm btn-info"><i class="las la-pen"></i></a>
                                        {{-- @endcan --}}

                                        {{-- @can('حذف قسم') --}}
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-id="{{ $category->id }}" data-category_name="{{ $category->category_name }}"
                                                data-toggle="modal" href="#modaldemo9" title="حذف"><i
                                                    class="las la-trash"></i></a>
                                        {{-- @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- ./Show Data --}}
        </div>
    </div>


    {{-- Modal Add Category --}}
    <div class="modal" id="AddModal" name="AddModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">اضافة قسم</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('categories.store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="exampleInputEmail1">اسم القسم</label>
                            <input type="text" class="form-control" id="category_name" name="category_name">
                            @if (Session::has('add_category') && $errors->has('category_name'))
                                <span class="text-danger">{{ $errors->first('category_name') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">ملاحظات</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            @if (Session::has('add_category') && $errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
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
    {{-- ./Modal Add Category --}}

   {{-- Modal Edit Category --}}
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">تعديل القسم</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    
                    @if(isset($category))
                    <form action="{{ route('categories.update',$category->id)}}"  method="post" autocomplete="off">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="hidden" name="category_id" id="cat_id" value="">

                            <label for="recipient-name" class="col-form-label">اسم القسم:</label>
                            <input class="form-control" name="category_name" id="category_name" type="text">
                            @if (Session::has('edit_category') && $errors->has('category_name'))
                                <span class="text-danger">{{ $errors->first('category_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">ملاحظات:</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                            @if (Session::has('edit_category') && $errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
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
    {{-- ./Modal Edit Category --}}

    {{--  Modal Delete Category --}}
  
    <div class="modal" id="modaldemo9">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">حذف القسم</h6><button aria-label="Close" class="close" data-dismiss="modal"
                        type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                @if(Session::has('delparent'))
                {{-- <div class="row mr-2 ml-2">
                    <button type="text" class="btn btn-lg btn-block btn-outline-success mb-2"id="type-error">
                        {{Session::get('delparent')}}
                    </button>
                </div> --}}
                @endif

                <form action="categories/destroy" method="post">
                    {{ method_field('delete') }}
                    {{ csrf_field() }}

                 
                    <div class="modal-body">
                        <p>هل انت متاكد من عملية الحذف ؟</p><br>
                        <input type="hidden" name="id" id="id" value="">
                        <input class="form-control" name="category_name" id="category_name" type="text" readonly>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                    </div>
                </form>
        </div>
    </div>
    {{-- ./Modal Delete Category --}}

    @php
        global $value_add;
        $value_add = Session::has('add_category')? Session::has('add_category'):'null';
        global $value_edit;
        $value_edit = Session::has('edit_category')? Session::has('edit_category'):'null';
        global $value_del;
        $value_del = Session::has('delparent')? Session::has('delparent'):'null';

        // dd($value_edit)
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
        if( {{$value_del}} != null)
        {
           $('#modaldemo9').modal('show');
        }  

         $('#EditModal').on('show.bs.modal', function (event) {
             var button = $(event.relatedTarget)
             var cat_id = button.data('catid')
            //  console.log(cat_id);
             var category_name = button.data('category_name')
             var description = button.data('description')
            
             var modal = $(this)
             modal.find('.modal-body #category_name').val(category_name);
             modal.find('.modal-body #description').val(description);
             modal.find('.modal-body #cat_id').val(cat_id);
         }); 

        $('#modaldemo9').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget)
        var id = button.data('id')
        console.log(id);
        var category_name = button.data('category_name')
        var modal = $(this)
        modal.find('.modal-body #id').val(id);
        modal.find('.modal-body #category_name').val(category_name);
    })


        
</script>
 

@endsection
