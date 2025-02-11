@extends('layouts.admin')
@section('page-title', 'Assign Permissions')
@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Assign Permissions to Roles</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="role_select">Select Role</label>
            <select id="role_select" class="form-control">
                <option value="">-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>

        <form id="permission_form" action="{{ route('permissions.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="role_id" id="selected_role">

            <div id="permissions_container" class="mt-3"></div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary" id="save_permissions" disabled>Update Permissions</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("role_select");
    const permissionsContainer = document.getElementById("permissions_container");
    const saveButton = document.getElementById("save_permissions");

    roleSelect.addEventListener("change", function () {
        let roleId = this.value;
        document.getElementById("selected_role").value = roleId;
        
        if (roleId) {
            fetch("{{ url('/admin/permissions/') }}/" + roleId)
                .then(response => response.json())
                .then(data => {
                    permissionsContainer.innerHTML = ''; 

                    if (data.permissions.length === 0) {
                        permissionsContainer.innerHTML = '<p class="text-danger">No permissions found for this role.</p>';
                        return;
                    }

                    let groupedPermissions = {};
                    data.permissions.forEach(permission => {
                        let group = permission.group || 'General';
                        if (!groupedPermissions[group]) {
                            groupedPermissions[group] = [];
                        }
                        groupedPermissions[group].push(permission);
                    });

                    let allPermissionsHTML = `
                        <div class="form-check">
                            <input type="checkbox" id="select_all" class="form-check-input">
                            <label for="select_all" class="form-check-label fw-bold">All</label>
                        </div>
                        <hr>
                    `;
                    permissionsContainer.innerHTML = allPermissionsHTML;

                    for (let group in groupedPermissions) {
                        let groupHtml = `
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input group-checkbox" data-group="${group}" id="group_${group}">
                                    <label class="form-check-label fw-bold" for="group_${group}"><strong>${group}</strong></label>
                                </div>
                                <div class="row ms-3">
                        `;
                        groupedPermissions[group].forEach(permission => {
                            groupHtml += `
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="${permission.id}" class="form-check-input permission-checkbox group_${group}"
                                            ${permission.assigned ? 'checked' : ''}>
                                        <label class="form-check-label">${permission.name}</label>
                                    </div>
                                </div>`;
                        });
                        groupHtml += `</div></div>`;
                        permissionsContainer.innerHTML += groupHtml;
                    }

                    saveButton.disabled = false;

                    document.getElementById("select_all").addEventListener("change", function () {
                        let allChecked = this.checked;
                        document.querySelectorAll(".permission-checkbox, .group-checkbox").forEach(cb => cb.checked = allChecked);
                    });

                    document.querySelectorAll(".group-checkbox").forEach(groupCheckbox => {
                        groupCheckbox.addEventListener("change", function () {
                            let group = this.getAttribute("data-group");
                            let checkboxes = document.querySelectorAll(`.group_${group}`);
                            checkboxes.forEach(cb => cb.checked = this.checked);
                        });
                    });
                })
                .catch(error => console.error("Error fetching permissions:", error));
        } else {
            permissionsContainer.innerHTML = "";
            saveButton.disabled = true;
        }
    });
});
</script>

@endsection
