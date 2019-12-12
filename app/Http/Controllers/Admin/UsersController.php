<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\Auth\RegisterService;
use App\User;


class UsersController extends Controller
{
    private $service;

    public function __construct(RegisterService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::orderByDesc('id');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value)->dump();
        }

        if (!empty($value = $request->get('name'))) {
            $query->where('name', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('email'))) {
            $query->where('email', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        if (!empty($value = $request->get('role'))) {
            $query->where('role', $value);
        }

        $users = $query->paginate(20);

        $statuses = [
            User::STATUS_WAIT => 'Waiting',
            User::STATUS_ACTIVE => 'Active',
        ];

        $roles = [
            User::ROLE_USER => 'User',
            User::ROLE_ADMIN => 'Admin',
        ];

        return view('admin.users.index', compact('users', 'statuses', 'roles'));
    }

    /**`
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $validated = $request->validated();

        $user = User::new(
            $validated['name'],
            $validated['email']
        );
        return redirect()->route('admin.users.show', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = [
            User::ROLE_USER => 'User',
            User::ROLE_ADMIN => 'Admin',
        ];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('admin.users.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function verify(User $user)
    {
        $this->service->verify($user->id);

        return redirect()->route('admin.users.show', $user);
    }
}
