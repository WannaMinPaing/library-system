<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Book\CreateBookRequest;
use App\Http\Requests\Api\Book\DeleteBookRequest;
use App\Http\Requests\Api\Book\DetailBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;

class BookController extends Controller
{
    use ResponseHelper;

    public function index(Request $request)
    {
        $bookQuery = Book::where(function ($query) use ($request) {

            $request->has('name')
                && $query->where('name', 'like', '%'.$request->name.'%');

            $request->has('author')
                && $query->where('author', 'like', '%'.$request->author.'%');

        });

        $result = $bookQuery->paginate(20);

        return $this->responseSucceed([
            'books' => BookResource::collection($result),
            'next' => $result->nextPageUrl() ?? null,
        ]);
    }

    public function create(CreateBookRequest $request)
    {
        $exit = Book::whereName($request->name)->whereAuthor($request->author)->first();

        if ($exit) {
            return $this->responseFailed('Book is already exit', 403);
        }

        $book = Book::create($request->all());

        return $this->responseSucceed([
            'book' => new BookResource($book),
        ]);
    }

    public function update(CreateBookRequest $request)
    {
        $old_book = Book::find($request->id);

        if (! $old_book) {
            return $this->responseFailed('Invalid ID', 403);
        }

        $exit = Book::whereName($request->name)->whereAuthor($request->author)->first();

        if ($exit) {
            return $this->responseFailed('Book is already exit', 403);
        }

        $old_book->name = $request->name ?? $old_book->name;
        $old_book->author = $request->author ?? $old_book->author;
        $old_book->total = $request->total ?? $old_book->total;
        $old_book->left = $request->left ?? $old_book->left;
        $old_book->save();

        return $this->responseSucceed([
            'book' => new BookResource($old_book),
        ]);
    }

    public function delete(DeleteBookRequest $request)
    {
        $book = Book::find($request->id);

        if (! $book) {
            return $this->responseFailed('Invalid ID', 403);
        }

        $book->delete();

        return $this->responseSucceed([], message : 'Successfully Deleted');
    }

    public function detail(DetailBookRequest $request)
    {
        $book = Book::find($request->id);

        if (! $book) {
            return $this->responseFailed('Invalid ID', 403);
        }

        return $this->responseSucceed([
            'book' => new BookResource($book),
        ]);
    }
}
