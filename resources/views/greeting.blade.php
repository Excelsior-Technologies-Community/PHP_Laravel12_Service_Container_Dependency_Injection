<!DOCTYPE html>
<html>

<head>

    <title>
        Laravel 12 Dependency Injection Demo
    </title>


    <style>
        body {

            font-family: Arial;
            padding: 40px;
            background: #f5f5f5;

        }


        .box {

            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ddd;

        }


        a {

            display: inline-block;
            padding: 10px 15px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;

        }


        .section {

            margin-top: 25px;

        }
    </style>

</head>


<body>


    <div class="box">


        <h2>
            Service Container & Dependency Injection
        </h2>


        <h3>
            Current Greeting Type:
            {{ ucfirst($type) }}
        </h3>


        <h3>
            Current Theme:
            {{ ucfirst($theme) }}
        </h3>


        <p style="white-space: pre-line;">
            {{ $message }}
        </p>

        <hr>

        <div class="section">

            <h3>
                Try Different Greeting Services
            </h3>


            <a href="/greet?type=english&theme={{ $theme }}">
                English
            </a>


            <a href="/greet?type=formal&theme={{ $theme }}">
                Formal
            </a>


            <a href="/greet?type=motivational&theme={{ $theme }}">
                Motivational
            </a>

        </div>


        <div class="section">

            <h3>
                Try Different Themes
            </h3>


            <a href="/greet?type={{ $type }}&theme=professional">
                Professional
            </a>


            <a href="/greet?type={{ $type }}&theme=fun">
                Fun 🚀
            </a>

        </div>


    </div>


</body>

</html>