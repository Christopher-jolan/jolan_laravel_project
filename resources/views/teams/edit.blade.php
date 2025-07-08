@extends('layouts.app')

@section('title', 'ویرایش تیم')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-people-fill"></i> ویرایش تیم: {{ $team->name }}
                    </h4>
                    <a href="{{ route('teams.index') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left"></i> بازگشت
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teams.update', $team->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-secondary text-white">
                                        <h5 class="mb-0">اطلاعات اصلی تیم</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">نام تیم</label>
                                            <input type="text" class="form-control" id="name" name="name" 
                                                   value="{{ old('name', $team->name) }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="leader_id" class="form-label">رهبر تیم</label>
                                            <select class="form-select" id="leader_id" name="leader_id" required>
                                                @foreach($team->members as $member)
                                                    <option value="{{ $member->user_id ?? $member->id }}" 
                                                            {{ ($member->role === 'leader') ? 'selected' : '' }}>
                                                        {{ $member->name }}
                                                        @if($member->role === 'leader')
                                                            (رهبر فعلی)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-secondary text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">اعضای تیم</h5>
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                                <i class="bi bi-plus"></i> افزودن عضو
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>نام</th>
                                                        <th>شماره دانشجویی</th>
                                                        <th>نقش</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($team->members as $member)
                                                    <tr>
                                                        <td>{{ $member->name }}</td>
                                                        <td>{{ $member->student_number }}</td>
                                                        <td>
                                                            @if($member->role === 'leader')
                                                                <span class="badge bg-primary">رهبر</span>
                                                            @else
                                                                <span class="badge bg-secondary">عضو</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($member->role !== 'leader')
                                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                        onclick="confirmDeleteMember({{ $member->id }})">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-circle"></i> ذخیره تغییرات
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="confirmDeleteTeam({{ $team->id }})">
                                <i class="bi bi-trash"></i> حذف تیم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal افزودن عضو جدید -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">افزودن عضو جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('team-members.store') }}" method="POST">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_member_name" class="form-label">نام کامل</label>
                        <input type="text" class="form-control" id="new_member_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_member_student_number" class="form-label">شماره دانشجویی</label>
                        <input type="text" class="form-control" id="new_member_student_number" name="student_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_member_phone" class="form-label">تلفن (اختیاری)</label>
                        <input type="text" class="form-control" id="new_member_phone" name="phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">افزودن عضو</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- فرم حذف مخفی -->
<form id="deleteTeamForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="deleteMemberForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDeleteTeam(teamId) {
    if (confirm('آیا از حذف این تیم مطمئن هستید؟ این عمل غیرقابل بازگشت است!')) {
        const form = document.getElementById('deleteTeamForm');
        form.action = `/teams/${teamId}`;
        form.submit();
    }
}

function confirmDeleteMember(memberId) {
    if (confirm('آیا از حذف این عضو از تیم مطمئن هستید؟')) {
        const form = document.getElementById('deleteMemberForm');
        form.action = `/team-members/${memberId}`;
        form.submit();
    }
}
</script>
@endsection