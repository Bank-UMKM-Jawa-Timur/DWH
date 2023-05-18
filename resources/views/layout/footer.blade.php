<!--   Core JS Files   -->
<script src="{{ asset('template') }}/assets/js/core/jquery.3.2.1.min.js"></script>
<script src="{{ asset('template') }}/assets/js/core/popper.min.js"></script>
<script src="{{ asset('template') }}/assets/js/core/bootstrap.min.js"></script>
<!-- jQuery UI -->
<script src="{{ asset('template') }}/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="{{ asset('template') }}/assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
<!-- Moment JS -->
<script src="{{ asset('template') }}/assets/js/plugin/moment/moment.min.js"></script>
<!-- Bootstrap Toggle -->
<script src="{{ asset('template') }}/assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<!-- jQuery Scrollbar -->
<script src="{{ asset('template') }}/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<!-- Atlantis JS -->
<script src="{{ asset('template') }}/assets/js/atlantis.min.js"></script>
<!-- Atlantis DEMO methods, don't include it in your project! -->
<script src="{{ asset('template') }}/assets/js/setting-demo2.js"></script>
<script>
    $('#displayNotif').on('click', function() {
        var placementFrom = $('#notify_placement_from option:selected').val();
        var placementAlign = $('#notify_placement_align option:selected').val();
        var state = $('#notify_state option:selected').val();
        var style = $('#notify_style option:selected').val();
        var content = {};

        content.message = 'Turning standard Bootstrap alerts into "notify" like notifications';
        content.title = 'Bootstrap notify';
        if (style == "withicon") {
            content.icon = 'fa fa-bell';
        } else {
            content.icon = 'none';
        }
        content.url = 'index.html';
        content.target = '_blank';

        $.notify(content, {
            type: state,
            placement: {
                from: placementFrom,
                align: placementAlign
            },
            time: 1000,
        });
    });
</script>
<!-- Sweet Alert -->
<script src="{{ asset('template') }}/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
@if (session('status'))
    <script>
        swal("Berhasil!", '{{ session('status') }}', {
            icon: "success",
            timer: 3000,
            closeOnClickOutside: false
        }).then(() => {
            location.reload();
        });
        setTimeout(function() {
            location.reload();
        }, 3000);
    </script>
@endif
@if (session('error'))
    <script>
        swal("Gagal!", '{{ session('error') }}', {
            icon: "error",
            timer: 3000,
            closeOnClickOutside: false
        }).then(() => {
            location.reload();
        });
        setTimeout(function() {
            location.reload();
        }, 3000);
    </script>
@endif
<script>
    function SuccessMessage(message) {
        swal("Berhasil!", message, {
            icon: "success",
            timer: 3000,
            closeOnClickOutside: false
        }).then(() => {
            location.reload();
        });
        setTimeout(function() {
            location.reload();
        }, 3000);
    }

    function ErrorMessage(message) {
        swal("Gagal!", message, {
            icon: "error",
            timer: 3000,
            closeOnClickOutside: false
        }).then(() => {
            location.reload();
        });
        setTimeout(function() {
            location.reload();
        }, 3000);
    }
</script>
@stack('extraScript')
</body>

</html>
