@if (session('alert'))
    <script>
        Swal.fire({
            icon: '{{ session('alert.type') }}',   // success, error, warning, info
            title: '{{ session('alert.title') }}',
            text: '{{ session('alert.message') }}',
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'text-base',
            },
        })
    </script>
@endif
