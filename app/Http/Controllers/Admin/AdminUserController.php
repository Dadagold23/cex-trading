<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {
    }

    public function index(): View
    {
        $admins = User::role(['admin', 'super_admin'])
            ->latest()
            ->paginate(20);

        return view('admin.admin-users.index', compact('admins'));
    }

    public function create(): View
    {
        $roles = Role::whereIn('name', ['admin', 'super_admin'])->get();

        return view('admin.admin-users.create', compact('roles'));
    }

    public function store(AdminUserRequest $request): RedirectResponse
    {
        $admin = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'email_verified_at' => now(),
        ]);

        $admin->syncRoles([$request->role]);

        $this->auditService->log(
            auth()->user(),
            'create_admin_user',
            'admin_user',
            $admin->id,
            null,
            ['email' => $admin->email, 'role' => $request->role],
            request()
        );

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user created successfully.');
    }

    public function edit(User $adminUser): View
    {
        abort_unless($adminUser->hasAnyRole(['admin', 'super_admin']), 404);

        $roles = Role::whereIn('name', ['admin', 'super_admin'])->get();

        return view('admin.admin-users.edit', compact('adminUser', 'roles'));
    }

    public function update(AdminUserRequest $request, User $adminUser): RedirectResponse
    {
        abort_unless($adminUser->hasAnyRole(['admin', 'super_admin']), 404);

        $oldValues = $adminUser->only(['name', 'email', 'role', 'status']);

        $adminUser->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $adminUser->password,
        ]);

        $adminUser->syncRoles([$request->role]);

        $this->auditService->log(
            auth()->user(),
            'update_admin_user',
            'admin_user',
            $adminUser->id,
            $oldValues,
            $adminUser->only(['name', 'email', 'role', 'status']),
            request()
        );

        return redirect()
            ->route('admin.admin-users.index')
            ->with('success', 'Admin user updated successfully.');
    }
}
