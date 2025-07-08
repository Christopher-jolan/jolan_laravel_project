@extends('layouts.app')

@section('title', 'ایجاد تیم جدید')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>ایجاد تیم جدید</h4>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('teams.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">نام تیم</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <hr>
                <h5>اعضای تیم</h5>
                <div id="members-list">
                    @php $oldMembers = old('members', [[]]); @endphp
                    @foreach($oldMembers as $i => $member)
                    <div class="row g-2 mb-2 member-row">
                        <div class="col-md-4">
                            <input type="text" name="members[{{ $i }}][name]" class="form-control" placeholder="نام عضو" value="{{ $member['name'] ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="members[{{ $i }}][student_number]" class="form-control" placeholder="شماره دانشجویی" value="{{ $member['student_number'] ?? '' }}" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="members[{{ $i }}][phone]" class="form-control" placeholder="شماره تماس (اختیاری)" value="{{ $member['phone'] ?? '' }}">
                        </div>
                        <div class="col-md-1 d-flex align-items-center">
                            <button type="button" class="btn btn-danger btn-sm remove-member" title="حذف"><i class="bi bi-x"></i></button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-info mb-3" id="add-member"><i class="bi bi-plus"></i> افزودن عضو</button>
                <br>
                <button type="submit" class="btn btn-success">ایجاد تیم</button>
                <a href="{{ route('teams.index') }}" class="btn btn-secondary">بازگشت</a>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let memberIndex = {{ count(old('members', [[]])) }};
    document.getElementById('add-member').addEventListener('click', function() {
        const membersList = document.getElementById('members-list');
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 member-row';
        row.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="members[${memberIndex}][name]" class="form-control" placeholder="نام عضو" required>
            </div>
            <div class="col-md-4">
                <input type="text" name="members[${memberIndex}][student_number]" class="form-control" placeholder="شماره دانشجویی" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="members[${memberIndex}][phone]" class="form-control" placeholder="شماره تماس (اختیاری)">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-member" title="حذف"><i class="bi bi-x"></i></button>
            </div>
        `;
        membersList.appendChild(row);
        memberIndex++;
    });
    document.getElementById('members-list').addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const row = e.target.closest('.member-row');
            row.remove();
        }
    });
});
</script>
@endsection 