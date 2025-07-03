<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Product Inventory System</title>
    <!-- Twitter Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #f5f5f5;
            color: #777;
            text-align: center;
            border-top: 1px solid #e5e5e5;
        }
        .edit-form {
            display: none;
        }
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255,255,255,0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
    </style>
</head>
<body>
    <div class="loading">
        <div class="spinner">
            <div class="text-center">
                <i class="glyphicon glyphicon-refresh spinning" style="font-size: 40px;"></i>
                <p>Processing...</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Product Inventory System</h1>
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Product Inventory System</p>
            <p>Developed by: Tanaka Gwese</p>
            <p>Email: tanakaevansgwese@gmail.com</p>
            <p>Phone: +263 784866533</p>
        </div>
    </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Twitter Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    @yield('scripts')
</body>
</html>
