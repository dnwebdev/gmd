<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>BUPSHA</title>

	<!-- Global stylesheets -->
	<link rel="shortcut icon" href="{{asset('img/klhk.png')}}" />
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('klhk-asset/dest-operator/klhk_global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap_limitless.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
	<!-- slick carousel -->
   <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/slick.css')}}" rel="stylesheet" type="text/css">
   <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/slick-theme.css')}}" rel="stylesheet" type="text/css">
   <!-- CUSTOM CSS GOMODO -->
   <link rel="stylesheet" href="{{asset('klhk-asset/dest-operator/css/back-office.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
	@yield('addtionalStyle')
	<!-- /global stylesheets -->



  <!-- /theme JS files -->
</head>
<body class="klhk">
  <!-- top bar -->
  @include('new-backoffice.top-header')
  <!-- end top bar -->
  <!-- Page content -->

  <div class="page-content">
		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
      <!-- /sidebar mobile toggler -->
      
			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user-material">
					<div class="sidebar-user-material-body" style="background: url({{asset('limitless/global_assets/images/backgrounds/user_bg3.jpg')}}) center center no-repeat;">
						<div class="card-body text-center">
							<a href="{{  url('/') }}">
								<img src="{{asset('img/klhk.png')}}" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
							</a>
							<h6 class="mb-0 text-white text-shadow-dark">{{ auth('admin')->user()->admin_name }}</h6>
{{--							<span class="font-size-sm text-white text-shadow-dark">Jakarta, Indonesia</span>--}}
						</div>
					</div>
				</div>
				<!-- /user menu -->
	
                <!-- sidebar nav -->
				@include('new-backoffice.sidebar')

			</div>
			<!-- /sidebar content -->
		</div>
		<!-- /main sidebar -->
		<!-- content-wrapper -->
		<div class="content-wrapper">
			<!-- Page header -->
			<div data-template="main_content_header"></div>

      <!-- /page header -->
      <!-- main content -->
			<div class="content pt-0">
				@yield('content')
			</div>
      <!-- end main content -->
			<!-- Footer -->
			@include('new-backoffice.footer')
      <!-- /footer -->
    </div>
    <!-- end conten-wrapper -->
  </div>
  <!-- end page-content -->
</body>
<!-- Core JS files -->
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/main/jquery.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/ui/ripple.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/js/custom-function.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/form_multiselect.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/pickers/daterangepicker.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/form_floating_labels.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/tags/tokenfield.min.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/tags/tagsinput.min.js')}}"></script>
{{--<script src="{{asset('touchspin')}}"></script>--}}
{{--<script src="{{asset('')}}"></script>--}}
{{--<script src="{{asset('')}}"></script>--}}
{{--<script src="{{asset('')}}"></script>--}}
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="{{asset('klhk-asset/dest-operator/klhk-assets/js/app.js')}}"></script>
<script src="{{asset('klhk-asset/dest-operator/js/custom-back-office.js')}}"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
{{--<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>--}}
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript">
    function loadingStart() {
        $(document).find('body').block({
            message: '<i class="icon-spinner spinner icon-2x"></i>',
            overlayCSS: {
                backgroundColor: '#1B2024',
                opacity: 0.85,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            }
        });
    }

    function loadingFinish() {
        $(document).find('body').unblock();
    }
    $(function () {
        $(document).find('[data-popup="tooltip"]').tooltip();
    });
</script>
@yield('additionalScript')
</html>
