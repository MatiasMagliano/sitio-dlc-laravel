@if (session('success'))
    <script>
        Swal.fire({
        icon: 'success',
        title: 'INFORMACION',
        html: '{!! session('success') !!}',
        showConfirmButton: false,
        timer: 3000
        })
    </script>
@endif

@if (session('warning'))
    <script>
        Swal.fire({
        icon: 'warning',
        title: 'ADVERTENCIA',
        html: '{!! session('warning') !!}',
        showConfirmButton: true,
        })
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
        icon: 'error',
        title: 'ERROR',
        html: '{!! session('error') !!}',
        showConfirmButton: true,
        })
    </script>
@endif
