<!-- jQuery 3 -->
<script src="{{asset('inhouse/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('inhouse/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
{{-- <script src="{{asset('inhouse/bower_components/chart.js/Chart.js')}}"></script> --}}
<!-- iCheck -->
<script src="https://adminlte.io/themes/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<!-- SlimScroll -->
<script src="{{asset('inhouse/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('inhouse/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('inhouse/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('inhouse/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- CK Editor -->
{{-- <script src="{{asset('inhouse/bower_components/ckeditor/ckeditor.js')}}"></script> --}}
<!-- Select2 -->
<script src="{{asset('inhouse/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('inhouse/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{asset('inhouse/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('inhouse/dist/js/demo.js')}}"></script>

{{-- myPlugins JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha512-YUkaLm+KJ5lQXDBdqBqk7EVhJAdxRnVdT2vtCzwPHSweCzyMgYV/tgGF4/dCyqtCC2eCphz0lRQgatGVdfR0ww==" crossorigin="anonymous"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
{{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/0.7.8/pace.min.js" integrity="sha512-t3TewtT7K7yfZo5EbAuiM01BMqlU2+JFbKirm0qCZMhywEbHZWWcPiOq+srWn8PdJ+afwX9am5iqnHmfV9+ITA==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.23/sweetalert2.min.js" integrity="sha512-eJK7xM/jkT80Ixs4NJuFhaqb/DfpGFP9j/GkZGzlQyn6nZmJPSXkWsLvRTcR4HBBe7bUlqwyWFpb0pJ44GyP/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{asset('js/custom.js')}}"></script>
@if(!Route::is('admin.login') && !Route::is('admin.password.request'))
<script src="//code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
@endif

<script>
    $(document).ready(function () {
        $('.sidebar-menu').tree();

        // $('#datepicker').datepicker({
        //     autoclose: true
        // })
    })

    $(function () {
        if($('#ck-editor').text()){
            CKEDITOR.replace('ck-editor');
        }
    });


        $('#mobileverifymodal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#mobileverifymodal').modal({
            backdrop: 'static',
            keyboard: false
        });

        $('#mobileverifyform').validate({
            rules: {
                otp: {
                    required: true,
                    number: true,
                    minlength: 6,
                    maxlength: 6,
                },
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function() {
                var form = $('#mobileverifyform');

                Pace.track(function(){
                    form.ajaxSubmit({
                        dataType:'json',
                        beforeSubmit:function(){
                            form.find('button[type="submit"]').button('loading');
                        },
                        success:function(data){
                            notify(data.status, 'success');
                            location.reload();
                        },
                        error: function(errors) {
                            form.find('button[type="submit"]').button('reset');
                            showErrors(errors, form);
                        }
                    });
                });
            }
        });

        $('#resendmverifyotp').on('click', function(){
            Pace.track(function(){
                $.ajax({
                    url: "{{--  {{route('dashboard.profile')}}  --}}",
                    method: "POST",
                    data: {'_token':'{{csrf_token()}}','type':'verifymobile','otp':'send'},
                    success: function(data){
                        resendotptimer(120, 'resendmverifyotp');
                    }, error: function(errors){
                        showErrors(errors);
                    }
                });
            });
        });

        function resendotptimer(remaining, buttonid) {
            $('#'+buttonid).attr('disabled', 'true');

            var m = Math.floor(remaining / 60);
            var s = remaining % 60;

            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;

            document.getElementById(buttonid).innerHTML = '<i class="fa fa-clock-o"></i>&nbsp;&nbsp;' + m + ':' + s;
            remaining -= 1;

            if(remaining >= 0) {
                setTimeout(function() {
                    resendotptimer(remaining, buttonid);
                }, 1000);
                return;
            }

            document.getElementById(buttonid).innerHTML = '<i class="fa fa-repeat"></i>&nbsp;&nbsp;Resend OTP';
            $('#'+buttonid).removeAttr('disabled');
        }


    $('#searchform').on('submit', function(){
        $('#my-datatable').dataTable().api().ajax.reload();
    });
    // $('.numberonly').keypress(function (e) {    
    //     var charCode = (e.which) ? e.which : event.keyCode
    //     if (String.fromCharCode(charCode).match(/[^0-9]/g))    
    //         return false;
    // });
    jQuery(".numberOnly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl+A, Command+A
        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    jQuery('.alphaonly').bind('keyup blur',function(){ 
        var node = jQuery(this);
        node.val(node.val().replace(/[^a-z A-Z]/g,'') ); }
    );
</script>
