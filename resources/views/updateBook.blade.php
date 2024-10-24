<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <h1>Update Book</h1>
    <form method="POST" class="m-5" action="{{route('updateBook', ['id' => $book->id])}}">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="book-title" class="form-label">Book Title</label>
            <input type="text" class="form-control" id="book-title" name="bookTitle" value="{{$book->bookTitle}}">
        </div>
        <div class="mb-3">
            <label for="publisher-name" class="form-label">Publisher</label>
            <select class="form-select" id="floatingSelect" aria-label="Publisher" name="publisherId">
                <option selected>{{$book->publisher->publisherName}}</option>
                @foreach ($publisher as $publisher)
                  <option value="{{$publisher->id}}">{{$publisher->publisherName}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="{{$book->author}}">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{$book->stock}}">
        </div>
        <div class="mb-3">
          <label for="book-description" class="form-label">Book Description</label>
          <input type="text" class="form-control" id="book-description" name="bookDescription" value="{{$book->bookDescription}}">
        </div>
        <div class="mb-3">
            <label for="release-date" class="form-label">Release Date</label>
            <input type="date" class="form-control" id="release-date" name="releaseDate" value="{{$book->releaseDate}}">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>