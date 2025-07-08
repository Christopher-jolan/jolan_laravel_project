@extends('layouts.app')

@section('title', 'مدیریت تیم‌های من')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-people-fill"></i> تیم‌های من
                </h4>
                <a href="{{ route('teams.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> ایجاد تیم جدید
                </a>
            </div>
        </div>

        <div class="card-body">
            @if($teams->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>نام تیم</th>
                                <th>تعداد اعضا</th>
                                <th>رزروهای فعال</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->member_count }}</td>
                                <td>{{ $team->reservations()->count() }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('teams.show', $team->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> مشاهده
                                        </a>
                                        <a href="{{ route('teams.edit', $team->id) }}" 
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i> ویرایش
                                        </a>
                                        <form action="{{ route('teams.destroy', $team->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('آیا از حذف این تیم مطمئن هستید؟')">
                                                <i class="bi bi-trash"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> هنوز هیچ تیمی ایجاد نکرده‌اید.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection