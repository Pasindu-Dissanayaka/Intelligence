  <!--   Core JS Files   -->
  <script src="{{ assets('js/core/popper.min.js') }}"></script>
  <script src="{{ assets('js/core/bootstrap.min.js') }}"></script>
  <script src="{{ assets('js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ assets('js/plugins/smooth-scrollbar.min.js') }}"></script>
  @yield('custom_js')
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>