@extends('dashboard.master')
@section('title')
مدرسة ليرن | الصفحة الرئيسية للمستويات
@stop
@section('content')
<main class="page-content">
              
           

            <div class="row">
            @if ($errors->any())
                    <div class="alert border-0 bg-light-danger alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                      <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
                      </div>
                      <div class="ms-3">
                        <ul>
                          @foreach ($errors->all() as $e)
                            <li class="text-danger">{{ $e }}</li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                    @endif
              <div class="col-12 col-lg-12 col-xl-12 d-flex">
                <div class="card radius-10 w-100">
                  <div class="card-header bg-transparent">
                    <div class="row g-3 align-items-center">
                      <div class="col">
                        <h5 class="mb-0">اضافة المستوى الجديد</h5>
                      </div>
                      <div class="col">
                        <div class="d-flex align-items-center justify-content-end gap-3 cursor-pointer">
                          
                        </div>
                      </div>
                     </div>
                  </div>  
                  
                  <div class="card-body">
                  
                    <form id="formcreate" >
                        
                        <label class="mb-2">اسم المستوى</label>
                    <input id="name" name="name" class="form-control mb-3" type="text" placeholder="اسم المستوى" >
                    <label class="mb-2"> المرحلة</label>
                    <select id="stage" name="stage" class="form-control mb-3">
                    <option selected disabled>اختر المرحلة</option>

                        @foreach($stages as $stage)
                        <option value="{{$stage->id}}">{{$stage->name}}</option>
                        @endforeach

                    </select>
                    <button type="submit" class="btn btn-outline-success col-12">اضافة</button>
                    </form>
                  </div>
                </div>
              </div>
   
            </div><!--end row-->


          </main>

@stop
@section('js')
 

    <script>
      $('#formcreate').submit(function(e) {
    e.preventDefault();
    var name = $('#name').val();
    var stage = $('#stage').val();

    $.ajax({
        url: "{{ route('dash.grade.add') }}",
        type: "POST",
        data: {
            "name": name,
            "stage": stage,
            _token: '{{ csrf_token() }}',
        },
        success: function(res) {
            alert(res);
              $('#formcreate')[0].reset();
        },
        error: function(e) {
            if (e.responseJSON && e.responseJSON.errors) {
                let msg = '';
                for (let key in e.responseJSON.errors) {
                    msg += e.responseJSON.errors[key][0] + '\n';
                }
                alert(msg);
            } else {
                alert('حدث خطأ أثناء الإضافة');
            }
    });
});
      
      
      
      
      
      
    </script>


@stop
