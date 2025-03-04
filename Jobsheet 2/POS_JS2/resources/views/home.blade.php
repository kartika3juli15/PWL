<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #555;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin: 10px 0;
        }
        a {
            text-decoration: none;
            background: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            transition: 0.3s;
        }
        a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Home Page of POS</h1>
        <h2>Product Categories</h2>
        <ul>
            <li><a href="{{ route('category.food-beverage') }}">Food & Beverages</a></li>
            <li><a href="{{ route('category.beauty-health') }}">Beauty & Health</a></li>
            <li><a href="{{ route('category.home-care') }}">Home Care</a></li>
            <li><a href="{{ route('category.baby-kid') }}">Baby & Kids</a></li>
        </ul>
    </div>
</body>
</html>