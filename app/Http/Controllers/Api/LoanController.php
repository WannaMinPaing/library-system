<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Loan\CreateLoanRequest;
use App\Http\Requests\Api\Loan\UpdateLoanRequest;
use App\Http\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    use ResponseHelper;

    public function index(Request $request)
    {

        $loanQuery = Loan::where(function ($query) use ($request) {

            $request->has(key: 'user_name')
                && $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->user_name.'%');
                });

            $request->has(key: 'book_name')
                && $query->whereHas('book', function ($query) use ($request) {
                    $query->where('name', 'like', '%'.$request->book_name.'%');
                });

            $request->has(key: 'author_name')
                && $query->whereHas('book', function ($query) use ($request) {
                    $query->where('author', 'like', '%'.$request->author_name.'%');
                });

            $request->has(key: 'loan_date')
                && $query->whereDate('loan_date', $request->loan_date);

            $request->has(key: 'return_date')
                && $query->whereDate('return_date', $request->return_date);

        });

        $result = $loanQuery->paginate(20);

        return $this->responseSucceed([
            'books' => LoanResource::collection($result),
            'next' => $result->nextPageUrl() ?? null,
        ]);
    }

    public function create(CreateLoanRequest $request)
    {
        $book = Book::find($request->book_id);
        $user = User::find($request->user_id);

        if ($book->left == 0) {
            return $this->responseFailed('This book is currently unavailable', 403);
        }

        if ($user->is_freeze) {
            return $this->responseFailed('This user is currently freezed', 403);
        }

        DB::transaction(function () use ($request, $book) {

            Loan::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'loan_date' => now(),
            ]);

            $book->left = --$book->left;
            $book->save();

        });

        return $this->responseSucceed([], message : 'Successfully');
    }

    public function update(UpdateLoanRequest $request)
    {
        $old_loan = Loan::find($request->id);
        $book = Book::find($old_loan->book_id);

        DB::transaction(function () use ($request, $old_loan, $book) {

            $old_loan->user_id = $request->user_id ?? $old_loan->user_id;
            $old_loan->book_id = $request->book_id ?? $old_loan->book_id;
            $old_loan->loan_date = $request->loan_date ?? $old_loan->loan_date;

            if (! $old_loan->return_date && $request->return_date) {
                $book->left = ++$book->left;
                $book->save();
            }

            $old_loan->return_date = $request->return_date ?? $old_loan->return_date;
            $old_loan->save();
        });

        return $this->responseSucceed([], message : 'Successfully updated');
    }
}
