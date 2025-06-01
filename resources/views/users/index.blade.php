@extends('layouts.admin')

@section('title', __('User List'))
@section('content-header', __('User List'))
@section('content-actions')
    <a class="btn btn-primary btn-new-user">
        {{ __('Add User') }}</a>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('User ID') }}</th>
                        <th>{{ __('First Name') }}</th>
                        <th>{{ __('Last Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->getRoleNames() as $role)
                                    {{ $role }}
                                @endforeach
                            </td>
                            <td>
                                <a class="btn btn-primary btn-edit" data-id="{{ $user->id }}"
                                    data-first_name="{{ $user->first_name }} " data-last_name="{{ $user->last_name }}"
                                    data-email="{{ $user->email }}" data-role="{{ $user->getRoleNames()->first() }}"><i
                                        class="fas fa-edit"></i></a>
                                <button class="btn btn-danger btn-delete" data-url="{{ route('users.destroy', $user) }}"><i
                                        class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $users->render() }}
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h2>All Roles and Permissions</h2>
            @foreach ($roles as $role)
                <div class="mb-4 border p-3 rounded shadow">
                    <h4>{{ Str::title($role->name) }}</h4>

                    <form class="permission-form" data-role-id="{{ $role->id }}">
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        @foreach ($permissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input permission-checkbox" type="checkbox"
                                    data-role-id="{{ $role->id }}" data-permission-id="{{ $permission->id }}"
                                    id="role-{{ $role->id }}-perm-{{ $permission->id }}"
                                    {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                <label class="form-check-label" for="role-{{ $role->id }}-perm-{{ $permission->id }}">
                                    {{ Str::title($permission->name) }}
                                </label>
                            </div>
                        @endforeach
                    </form>
                </div>
            @endforeach

        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="module">
        $(document).ready(function() {
            $(document).on('click', '.btn-delete', function() {
                var $this = $(this);
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: '{{ __('Are you sure? ') }}', // Wrap in quotes
                    text: '{{ __('Delete') }}', // Wrap in quotes
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('Delete ') }}', // Wrap in quotes
                    cancelButtonText: '{{ __('No') }}', // Wrap in quotes
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.post($this.data('url'), {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}' // Wrap in quotes
                        }, function(res) {
                            $this.closest('tr').fadeOut(500, function() {
                                $(this).remove();
                            });
                        });
                    }
                });
            });

            $(document).on('click', '.btn-new-user', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "New User",
                    html: `
                    <input id=swal-first_name class="swal2-input" placeholder="First Name">
                    <input id=swal-last_name class="swal2-input" placeholder="Last Name">
                    <input type="email" id=swal-email class="swal2-input" placeholder="Email">
                    `,
                    confirmButtonText: 'Add User',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    focusConfirm: false,
                    preConfirm: () => {
                        const FirstName = document.getElementById("swal-first_name").value;
                        const LastName = document.getElementById("swal-last_name").value;
                        const Email = document.getElementById("swal-email").value;
                        if (!FirstName || !LastName || !Email) {
                            Swal.showValidationMessage('All fields are required');
                            return false;
                        }
                        return {
                            first_name: FirstName,
                            last_name: LastName,
                            email: Email
                        };
                    }

                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send data via AJAX to your Laravel route
                        fetch(`/admin/users`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(result.value)
                            }).then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Updated!',
                                            'User added successfully', 'success')
                                        .then(() => location
                                            .reload()
                                        );
                                } else {
                                    Swal.fire('Error', data.message ||
                                        'Something went wrong', 'error');
                                }
                            }).catch(error => {
                                if (error.errors) {
                                    // Laravel validation errors (422)
                                    let messages = Object.values(error.errors).flat();
                                    Swal.fire('Validation Error',
                                        "Error occurred! Check values provided", 'error');
                                } else {
                                    // Other errors (500, network issues, etc.)
                                    Swal.fire('Error', 'Error occurred! Check values provided',
                                        'error');
                                }
                            });
                    }
                });

            })
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-edit').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const userId = this.dataset.id;
                    const firstName = this.dataset.first_name;
                    const lastName = this.dataset.last_name;
                    const email = this.dataset.email;
                    const role = this.dataset.role;

                    Swal.fire({
                        title: 'Edit User',
                        html: `<input id="swal-first_name" class="swal2-input" placeholder="First Name" value="${firstName}">
                         <input id="swal-last_name" class="swal2-input" placeholder="Last Name" value="${lastName}">
                         <input id="swal-email" class="swal2-input" placeholder="Email" value="${email}">
                         <input id="swal-role" class="swal2-input" placeholder="Role" value="${role}">`,
                        confirmButtonText: 'Update',
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        focusConfirm: false,
                        preConfirm: () => {
                            const firstName = document.getElementById('swal-first_name')
                                .value;
                            const lastName = document.getElementById('swal-last_name')
                                .value;
                            const email = document.getElementById('swal-email').value;
                            const role = document.getElementById('swal-role').value;

                            if (!firstName || !lastName || !email || !role) {
                                Swal.showValidationMessage('All fields are required');
                                return false;
                            }

                            return {
                                first_name: firstName,
                                last_name: lastName,
                                email: email,
                                role: role
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send data via AJAX to your Laravel route
                            fetch(`/admin/users/${userId}`, {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify(result.value)
                                }).then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Updated!',
                                                'User updated successfully', 'success')
                                            .then(() => location
                                                .reload()
                                            ); // reload page to reflect changes
                                    } else {
                                        Swal.fire('Error', data.message ||
                                            'Something went wrong', 'error');
                                    }
                                }).catch(error => {
                                    if (error.errors) {
                                        // Laravel validation errors (422)
                                        let messages = Object.values(error.errors)
                                            .flat();
                                        Swal.fire('Validation Error',
                                            "Error occurred! Check values provided",
                                            'error');
                                    } else {
                                        // Other errors (500, network issues, etc.)
                                        Swal.fire('Error',
                                            'Error occurred! Check values provided',
                                            'error');
                                    }
                                });
                        }
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const roleId = this.dataset.roleId;
                    const permissionId = this.dataset.permissionId;
                    const checked = this.checked;

                    fetch(`/admin/roles/${roleId}/permissions/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify({
                                permission_id: permissionId,
                                attach: checked
                            })
                        }).then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert('Permission updated.');
                            } else {
                                alert(data.message || 'Something went wrong.');
                            }
                        }).catch(err => {
                            alert('Server error');
                            console.error(err);
                        });
                });
            });
        });
    </script>


@endsection
