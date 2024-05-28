<!-- resources/views/errors/404.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="{{ asset('css/error.css') }}"> <!-- Link to your custom CSS file -->
</head>
<style>
    body,
    html {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f1f1f1;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }

    .container {
        text-align: center;
    }

    .error-page {
        max-width: 600px;
        padding: 40px;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .error-page h1 {
        font-size: 10rem;
        margin: 0;
        color: #ff6b6b;
        animation: bounce 1s infinite;
    }

    .error-page h2 {
        font-size: 2rem;
        color: #333;
        margin: 20px 0;
    }

    .error-page p {
        color: #666;
        margin: 20px 0;
    }

    .btn-home {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #ff6b6b;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-home:hover {
        background-color: #ff4757;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }
</style>

<body>
    <div class="container">
        <div class="error-page">
            <h1>404</h1>
            <h2>Oops! Page not found.</h2>
            <p>We can't find the page you're looking for. It might have been removed or had its name changed.</p>
            <a href="{{ url('/') }}" class="btn-home">Go to Homepage</a>
            <a href="javascript:history.back()" class="btn-home">Go Back</a> <!-- Go Back button -->
        </div>
    </div>
</body>

</html>
