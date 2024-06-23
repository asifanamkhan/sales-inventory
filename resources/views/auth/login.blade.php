<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: "Comic Sans MS", cursive;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #fafafa;
            /* Warna latar belakang abu-abu muda */
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 400px;
        }

        .card {
            width: 100%;
            background-color: #f8f8f8;
            /* Warna card */
            padding: 20px;
            border-radius: 12px;
            /* Border radius card */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 10px;
            margin-bottom: 12px;
            border: 2px solid #ddd;
            /* Border color input */
            border-radius: 8px;
            /* Border radius input */
            transition: border-color 0.3s ease-in-out;
            outline: none;
            color: #333;
            background-color: #f4f4f4;
            /* Warna input */
        }

        input:focus {
            border-color: #3C50E0;
            /* Warna input saat focus */
        }

        button {
            background-color: #3C50E0;
            /* Warna button */
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 8px;
            /* Border radius button */
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #1121a0;
            /* Warna button saat hover */
        }
    </style>
</head>

<body style="background-image: url('{{ asset('public/img/login-bg.jpg') }}')">
    <div class="container">
        <div class="card" style="border: 2px solid ">
            <h4 style="text-align: center; font-size:2rem; margin-top:0">Sales Inventory</h4>
            <h2>Login</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="text" id="username" name="email" placeholder="email" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>

            </form>
            <form>

            </form>
        </div>
    </div>
</body>

</html>
