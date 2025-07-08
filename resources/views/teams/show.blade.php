@extends('layouts.app')

@section('title', 'جزئیات تیم')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="bi bi-people-fill"></i> {{ $team->name }}
                </h4>
                <a href="{{ route('teams.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-right"></i> بازگشت
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>اطلاعات تیم</h5>
                    <p><i class="bi bi-person-badge"></i> رهبر تیم: {{ $team->leader->name }}</p>
                    <p><i class="bi bi-people"></i> تعداد اعضا: {{ $team->member_count }}</p>
                </div>
                <div class="col-md-6">
                    <h5>رزروهای تیم</h5>
                    <p><i class="bi bi-calendar-check"></i> تعداد رزروها: {{ $team->reservations()->count() }}</p>
                </div>
            </div>
            @if($team->reservations->count() > 0)
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> رزروهای این تیم</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>تاریخ</th>
                                        <th>زمان</th>
                                        <th>وضعیت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($team->reservations as $reservation)
                                    <tr>
                                        <td>{{ jdate($reservation->gymSession->date)->format('Y/m/d') }}</td>
                                        <td>{{ $reservation->gymSession->start_time }} - {{ $reservation->gymSession->end_time }}</td>
                                        <td>
                                            @if($reservation->status == 'pending')
                                                <span class="badge bg-warning">در انتظار تایید</span>
                                            @elseif($reservation->status == 'confirmed')
                                                <span class="badge bg-success">تایید شده</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('reservations.show', $reservation->id) }}" 
                                            class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> مشاهده
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <h5 class="border-bottom pb-2">لیست اعضا</h5>
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
                                    <span class="badge bg-primary">رهبر تیم</span>
                                @else
                                    <span class="badge bg-secondary">عضو</span>
                                @endif
                            </td>
                            <td>
                                @if($member->role !== 'leader')
                                <form action="{{ route('team-members.destroy', $member->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('آیا از حذف این عضو مطمئن هستید؟')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
                                </form>
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
@endsection