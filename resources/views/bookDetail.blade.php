<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .detail{
                margin-left: 3rem;
                color: white;
            }
            h1{
                margin-top: 3rem;
                margin-bottom: 3rem;
                font-size: 50px;
            }
            h3{
                margin-bottom: 3rem;
                font-size: 25px;
            }
            .btn {
                display: inline-block;
                padding: 10px 15px;
                background-color: #007bff;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                transition: 0.2s;
                margin-top: 10px;
            }

            .btn:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                <div class="detail">
                    <h1>{{$book->bookTitle}}</h1>
                    <h3>By: {{$book->author}}</h3>
                    <h3>Stock: {{$book->stock}}</h3>
                    <h3>Release Date: {{$book->releaseDate}}</h3>
                    @if ($book->stock > 0)
                    <form action="{{route('buyBook')}}" method="POST">
                        @csrf
                        <input type="hidden" name="bookId" value="{{ $book->id }}">
                        <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                        <button type="submit" class="btn btn-primary">Buy</button>
                    </form>
                    @else
                        <h3>Out of Stock</h3>
                    @endif  
                </div>
            </main>
        </div>
        
    </body>
</html>
