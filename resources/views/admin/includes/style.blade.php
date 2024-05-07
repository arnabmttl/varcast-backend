<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('inhouse/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('inhouse/bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('inhouse/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('inhouse/bower_components/select2/dist/css/select2.min.css')}}">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('inhouse/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">

<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{asset('inhouse/dist/css/skins/_all-skins.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('inhouse/dist/css/AdminLTE.min.css')}}">
<!-- iCheck -->
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/plugins/iCheck/square/blue.css">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js "doesn't" work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

{{-- myPlugin CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css" integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pace/0.7.8/themes/black/pace-theme-flash.min.css" integrity="sha512-0c1cb0LYXVvb9L459008ryNuWW7NuZEFY0ns6fAOfpJhHnTX7Db2vbSrjaLgvUpcl+atb3hkawh2s+eEE3KaLQ==" crossorigin="anonymous" />

<link rel="stylesheet" href="{{asset('css/custom.css')}}"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.23/sweetalert2.min.css" integrity="sha512-Yn5Z4XxNnXXE8Y+h/H1fwG/2qax2MxG9GeUOWL6CYDCSp4rTFwUpOZ1PS6JOuZaPBawASndfrlWYx8RGKgILhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .resendotp{
        width: fit-content;
        color: #ffffff !important;
        border-radius: 0px;
        pointer-events: all;
    } 
    .datepicker{
        z-index: 9999 !important;
    } 
    hr.short{
        margin-top: 10px;
        margin-bottom: 10px;
    } 
    a.label.my-label {
        pointer-events: none;
        text-align: left;
        font-weight: bold;
    } 
    tbody td{
        vertical-align: middle !important;
    }
    .swal2-popup {
        font-size: 1.6rem !important;
        font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
    }
    .pace-inactive{
        position: fixed !important;
        height: 100%;
        width: 100%;
        z-index: 10000;
        background: #ffffff50 !important;
    }
    .pace-active{
        position: fixed !important;
        height: 100%;
        width: 100%;
        z-index: 10000;
        background: #ffffff50 !important;
    }
    .pace .pace-progress{
        height: 5px;
    }
    .pace .pace-activity{
        top: 50%;
        right: 46%;
        width: 60px;
        height: 60px;
        border: solid 6px transparent;
        border-top-color: #3c8dbc;
        border-left-color: #3c8dbc;
        border-radius: 30px;
    }
    .select2-container .select2-selection--single {
        height: 34px !important;
    }
    .select2-container .select2-selection--multiple {
        min-height: 34px !important;
    }
</style>
