@if (session('success'))
    <script>
        Swal.fire({
        icon: 'success',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2500
        })
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
        title: 'Error',
        icon: 'error',
        text: '{{ session('error') }}',
        showConfirmButton: true,
        })
    </script>
@endif
