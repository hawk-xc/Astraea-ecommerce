@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}")
    </script>
@endif

@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}")
    </script>
@endif

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif
