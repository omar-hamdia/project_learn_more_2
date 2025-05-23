@extends('dashboard.master')
@section('title')
    مدرسة ليرن | صفحة الرئيسية للمستويات
@stop
@section('content')
   <main class="page-content">
        <!-- Modal -->
        <div class="modal fade" id="add-Modal" tabindex="-1" aria-labelledby="stagesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stagesModalLabel">المراحل الدراسية</h5>
                        <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                   
                        <div class="modal-body">
                            <div class="container">
                                <form action="{{ route('dash.section.add') }}" method="POST" id="add-form">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" >
                                    <div class="mb-4">
                                    <label>عدد الشعب المرغب بها:</label>
                                    <input class="form-control" name="count_section" >
                                    </div>
                                    <button class="btn btn-outline-success col-12" type="submit">اضافة</button>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary col-12" data-bs-dismiss="modal">إغلاق</button>
                        </div>
                   
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12 col-lg-12 col-xl-12 d-flex">
                <div class="card radius-10 w-100">
                    <div class="card-header bg-transparent">
                        <div class="row g-3 align-items-center">
                            <div class="col">
                                <h5 class="mb-0">جميع الشعب</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary col-12" data-bs-toggle="modal" data-bs-target="#add-Modal">
                            اضافة الشعب
                        </button>
                        <div class="table-responsive mt-3">
                            <table id="datatable" class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>الحالة</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@stop

@section('js')
<!-- ✅ رابط أيقونات Bootstrap إذا لم تكن موجودة -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

 <script>
$(document).ready(function() {
  var table = $('#datatable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: '{{ route('dash.section.getdata') }}',
    },
    columns:[
      { name: 'DT_RowIndex', data: 'DT_RowIndex', orderable: false, searchable: false },
      { name: 'name', data: 'name', title: 'الاسم', orderable: true, searchable: true },
      { name: 'status', data: 'status', title: 'الحالة', orderable: true, searchable: true },
      { name: 'action', data: 'action', title: 'العمليات', orderable: false, searchable: false },
    ],
    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
    }
  });
});

  // تفعيل زر الحفظ عند الضغط على زر "اضافة"
  $(document).on('click', '.btn-add-section', function(e) {
    e.preventDefault();
    var button = $(this);
    var gradetag = button.data('grade');
    $('#gradetag').val(gradetag);
  });

  // إضافة شعبة جديدة
  
 $(document).on('submit', '#add-form', function(e) {
    e.preventDefault();
    var url = $(this).attr('action');
    var type = $(this).attr('method');

    // alert('omar');
    data = new FormData(this);
   $.ajax({
      url: url,
      type: type,
      processData: false,
      contentType: false,
      data: data,
      success: function(res) {
        // console.log(res.message);
        $('#add-Modal').modal('hide');
        $('#add-form').trigger('reset');
        toastr.success(res.success);
        table.draw();
      },
    });
});
$(document).ready(function() {
           $(document).on('change', '.active-section-sw', function(e) {
    var id = $(this).data('id');
    var status = $(this).data('status');
    $.ajax({
        url: "{{ route('dash.section.changestatus') }}",
        type: "post",
        data:{
            'id': id ,
            'status': status ,
            '_token': "{{ csrf_token() }}" ,
        },
        success: function(res) {
            toastr.success(res.success)
            table.draw();
        },
    });
});
        });
    
    

 






//   $('.master-checkbox').on('change', function() {
//     var target = $(this).data('target');
//     var checked = $(this).prop('checked');
//     if(!checked){
//     $(target).find('input[type=checkbox]').prop('disabled', true);
//     }else{
//       $(target).find('input[type=checkbox]').prop('disabled', false);
//     }
//   });

//   // التفويض الصحيح للشيك بوكسات الصفوف
//   $(document).on('change', '.grade-checkbox', function() {
//     var checkbox = $(this);
//     var status = checkbox.is(':checked') ? 1 : 0;
//     var stage = checkbox.data('stage');
//     var tag = checkbox.data('grade');
//     var name = checkbox.data('name');

//     $.ajax({
//       url: "{{ route('dash.grade.add') }}",
//       type: "POST",
//       data: {
//         'stage': stage,
//         'tag': tag,
//         'name': name,
//         'status': status,
//         "_token": "{{ csrf_token() }}",
//       },
//       success: function(res) {
//         // console.log(res.message);
//         toastr.success(res.success);
//         table.draw();
//       },
//       error: function(res) {
//         alert('حدث مشكلة بالكود');
//       },
//     });
//   });

//   // تفعيل الشيك بوكسات النشطة عند التحميل
//    $.ajax({
//     url:"{{ route('dash.grade.getactive') }}",
//     type:"GET",
//     success: function(res){
//       var activeTags = res.tags.map(Number);
//       $('input[type=checkbox]').not('.master-checkbox').each(function(){
//         var checkbox = $(this);
//         var datagrade = checkbox.data('grade');
//         if(typeof datagrade !== 'undefined' && activeTags.includes(Number(datagrade))){
//              checkbox.prop('checked', true);
//              checkbox.prop('disabled', false);
//         }
//       });
//     },
//   });
  
// });
// ///////////////////////////////////////////////////////////////
// $.ajax({
//     url:"{{ route('dash.grade.getactive.stage') }}",
//     type:"GET",
//     success: function(res){
//       var activeTags = res.tags;
//       $('.master-checkbox').each(function(){
//         var checkbox = $(this);
//         // alert(activeTags);
//         var datastag = checkbox.data('tag');
//         if( activeTags.includes(datastag)){
//              checkbox.prop('checked', true);
//              checkbox.prop('disabled', false);
//         }else{
//           checkbox.prop('checked', false);
//            var target = $(this).data('target');
//            var checked = $(this).prop('checked');
//             $(target).find('input[type=checkbox]').prop('disabled', true);
//         }
       
//     });
//     }, 
          
//       });
    
//     $(document).on('change', '.master-checkbox', function() {
//     var checkbox = $(this);
//     var tag = checkbox.data('tag'); // id الشعبة الصحيح
//     var status = checkbox.is(':checked') ? 1 : 0;

//     $.ajax({
//       url: "{{ route('dash.grade.changemaster') }}",
//       type: "POST",
//       data: {
//         'tag': tag,
//          'status': status,
//         "_token": "{{ csrf_token() }}",
//       },
//       success: function(res) {
//         // console.log(res.message);
//         toastr.success(res.success);

//         table.draw();
//       },
     
//     }); 
// });

// $(document).on('click','.btn-add-section', function(e){
//   e.preventDefault();
//   var button = $(this);
//   var gradetag = button.data('grade');
//   // alert(gradetag);
//   $('#gradetag').val(gradetag);

// })
  
//   $(document).on('change', '.section-checkbox', function() {
//     var checkbox = $(this);
//     var section = checkbox.data('section'); // id الشعبة الصحيح
//     var gradetag = $('#gradetag').val();    // tag الصف الصحيح
//     var status = checkbox.is(':checked') ? 1 : 0;

//     $.ajax({
//       url: "{{ route('dash.grade.addsection') }}",
//       type: "POST",
//       data: {
//         'section': section,
//         'gradetag': gradetag,
//         'status': status,
//         "_token": "{{ csrf_token() }}",
//       },
//       success: function(res) {
//         // console.log(res.message);
//         toastr.success(res.success);

//         table.draw();
//       },
//       error: function(res) {
//         alert('حدث خطأ أثناء تحديث الشعبة');
//       }
//     }); 
// });

// $(document).ready(function() {
// $(document).on('click','.btn-add-section', function(e){
//     e.preventDefault();
//     var button = $(this);
//     var gradeId = button.data('grade-id'); // tag الصف

//     // جلب الشعب المفعلة لهذا الصف
//     $.ajax({
//         url:"{{ route('dash.grade.getactive.section') }}",
//         type:"GET",
//         data: {
//             'gradeId': gradeId // أرسل tag وليس id
//         },
//         success: function(res){
//             var activeSection = res.names.map(Number);
//             $('.section-checkbox').not('.master-checkbox').each(function(){
//                 var checkbox = $(this);
//                 var datasection = checkbox.data('section');
//                 if(activeSection.includes(datasection)){
//                     checkbox.prop('checked', true);
//                     checkbox.prop('disabled', false);
//                 }else{
//                     checkbox.prop('checked', false);
//                 }
//             });
//         },
//     });
// });
// });

 


</script> 

@stop
