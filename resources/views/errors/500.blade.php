<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable - yandrien.my.id</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
            background-color: #ffffff; 
            color: #1a202c; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0; 
        }
        .content { text-align: center; padding: 20px; }
        h1 { font-size: 2.5rem; margin-bottom: 10px; color: #2d3748; }
        p { font-size: 1.1rem; color: #718096; margin-bottom: 30px; }
        .back-home {
            text-decoration: none;
            color: #3182ce;
            font-weight: 600;
            border: 1px solid #3182ce;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .back-home:hover {
            background-color: #3182ce;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Something went wrong</h1>
        <p>We are currently experiencing technical difficulties. Please try again later.</p>
        <a href="{{ url('/') }}" class="back-home">Back to Home</a>
    </div>
</body>
</html>