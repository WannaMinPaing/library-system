<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\CreateUserRequest;
use App\Http\Requests\Api\User\DetailUserRequest;
use App\Http\Requests\Api\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ResponseHelper;

    public function index(Request $request)
    {
        $userQuery = User::where(function ($query) use ($request) {

            $request->has('name')
                && $query->where('name', 'like', '%'.$request->name.'%');

            $request->has('phone_number')
                && $query->where('phone_number', 'like', '%'.$request->phone_number.'%');

        });

        $result = $userQuery->paginate(20);

        return $this->responseSucceed([
            'users' => UserResource::collection($result),
            'next' => $result->nextPageUrl() ?? null,
        ]);
    }

    public function create(CreateUserRequest $request)
    {
        $user = User::create($request->all());

        return $this->responseSucceed([
            'user' => new UserResource($user),
        ]);
    }

    public function update(UpdateUserRequest $request)
    {

        $old_user = User::find($request->id);
        $old_user->name = $request->name ?? $old_user->name;
        $old_user->phone_number = $request->phone_number ?? $old_user->phone_number;
        $old_user->address = $request->address ?? $old_user->address;
        $old_user->is_freeze = $request->is_freeze ?? $old_user->is_freeze;
        $old_user->save();

        return $this->responseSucceed([
            'user' => new UserResource($old_user),
        ]);
    }

    public function detail(DetailUserRequest $request)
    {
        $user = User::find($request->id);

        return $this->responseSucceed([
            'user' => new UserResource($user),
        ]);
    }
}
