@if (Session()->has('success') || Session()->has('error'))
    <script>
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000, // Thay timer ở đây nếu cần
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });

            const message = "{{ Session()->get('success') ?: Session()->get('error') }}";
            const icon = "{{ Session()->has('success') ? 'success' : 'error' }}";

            Toast.fire({
                icon: icon,
                title: `<p>${message}</p>`,
            });
    </script>
@endif
