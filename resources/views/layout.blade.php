<html>
    <head>
        <title>CSV Uploader</title>
        <!-- CSS only -->
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

        <style>
            .card-counter{
                box-shadow: 2px 2px 10px #DADADA;
                margin: 5px;
                padding: 20px 10px;
                background-color: #fff;
                height: 100px;
                border-radius: 5px;
                transition: .3s linear all;
            }

            .card-counter:hover{
                box-shadow: 4px 4px 20px #DADADA;
                transition: .3s linear all;
            }

            .card-counter.primary{
                background-color: #007bff;
                color: #FFF;
            }

            .card-counter.danger{
                background-color: #ef5350;
                color: #FFF;
            }

            .card-counter.success{
                background-color: #66bb6a;
                color: #FFF;
            }

            .card-counter.info{
                background-color: #26c6da;
                color: #FFF;
            }

            .card-counter i{
                font-size: 5em;
                opacity: 0.2;
            }

            .card-counter .count-numbers{
                position: absolute;
                right: 35px;
                top: 20px;
                font-size: 32px;
                display: block;
            }

            .card-counter .count-name{
                position: absolute;
                right: 35px;
                top: 65px;
                font-style: italic;
                text-transform: capitalize;
                opacity: 0.5;
                display: block;
                font-size: 18px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Forbes Top</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                @if (Auth::user())
                <li class="nav-item">
                    <a class="nav-link" href="dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="csv-upload">CSV Uploader</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="csv-records">Records</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout">Logout</a>
                </li>
                @else
                <li class="nav-item">
                  <a class="nav-link" href="./">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login">Login</a>
                </li>
                @endif
              </ul>
            </div>
        </nav>

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
