<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.accounts') }}">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teller.dashboard') }}">Dashboard</a>
                    </li>

                    @auth
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </a>
                        </form>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
    <script>
        // AJAX request for deposit
        $('#depositForm').click(function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du formulaire
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route("teller.deposit") }}',
                data: formData,
                success: function(response) {
                    alert(response.success);
                    $('#depositModal').modal('hide');
                },
                error: function(response) {
                    alert(response.responseJSON.error);
                }
            });
        });

        // AJAX request for withdrawal
        $('#withdrawForm').click(function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du formulaire
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '{{ route("teller.withdraw") }}',
                data: formData,
                success: function(response) {
                    alert(response.success);
                    $('#withdrawModal').modal('hide');
                },
                error: function(response) {
                    alert(response.responseJSON.error);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>

</html>
