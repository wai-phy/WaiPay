<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
</head>
<body>
    <h1>Hello User Home Page</h1>
    
    <form action="{{route('logout')}}" method="post">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>