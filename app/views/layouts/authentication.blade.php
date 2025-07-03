{{-- Default Layout --}}
<!DOCTYPE html>
<html lang="en">
@include('partials/head')
<body class="bg-gray-200">
	{{-- Main Container --}}
	<main class="main-content  mt-0">
		{{-- Main row --}}
		<div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1747637311179-582542a26e81?q=80&w=1950&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA');">
			<span class="mask bg-gradient-dark opacity-6"></span>
			<div class="container my-auto">
				<div class="row">
					<div class="col-lg-4 col-md-8 col-12 mx-auto">
						<div class="card z-index-0 fadeIn3 fadeInBottom">
							<div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
								<div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
									<h4 class="text-white font-weight-bolder text-center mt-2 mb-0">@yield('title')</h4>
									<div class="row mt-3">
										<div class="col-2 text-center ms-auto">
											<a class="btn btn-link px-3" href="javascript:;">
												<i class="fa-brands fa-square-facebook text-white text-lg"></i>
											</a>
										</div>
										<div class="col-2 text-center px-1">
											<a class="btn btn-link px-3" href="javascript:;">
												<i class="fa-brands fa-github text-white text-lg"></i>
											</a>
										</div>
										<div class="col-2 text-center me-auto">
											<a class="btn btn-link px-3" href="javascript:;">
												<i class="fa-brands fa-google text-white text-lg"></i>
											</a>
										</div>
									</div>
								</div>
							</div>
							@yield('content')
						</div>
					</div>
				</div>
			</div>
			<footer class="footer position-absolute bottom-2 py-2 w-100">
				<div class="container">
					<div class="row align-items-center justify-content-lg-between">
						<div class="col-12 col-md-6 my-auto">
							<div class="copyright text-center text-sm text-white text-lg-start">
								© <script>
									document.write(new Date().getFullYear())
								</script>,
								made with <i class="fa fa-heart" aria-hidden="true"></i> by
								<a href="https://www.pasindudissan.xyz" class="font-weight-bold text-white" target="_blank">Pasindu Dissanayaka</a>
								for Aspirations I-Lab.
							</div>
						</div>
						<div class="col-12 col-md-6">
							<ul class="nav nav-footer justify-content-center justify-content-lg-end">
								<li class="nav-item">
									<a href="https://www.pasindudissan.xyz" class="nav-link text-white" target="_blank">Pasindu Dissanayaka</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
		{{-- end main row --}}
	</main>
	{{-- end main container --}}
	@include('partials/footer')
</body>
</html>