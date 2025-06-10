@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4"><i class="bi bi-speedometer2"></i> پنل مدیریت</h1>

    <!-- بخش رزروهای در انتظار تأیید -->
    <div class="card mb-4">
        <div class="card-header bg-warning">
            <h3 class="mb-0"><i class="bi bi-hourglass"></i> رزروهای در انتظار تأیید</h3>
        </div>
        <div class="card-body">
            @if($pendingReservations->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>کاربر</th>
                                <th>تاریخ سانس</th>
                                <th>ساعت</th>
                                <th>اعضا</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingReservations as $reservation)
                            <tr>
                                <td>{{ $reservation->user->name }}</td>
                                <td>{{ $reservation->gymSession->date }}</td>
                                <td>{{ $reservation->gymSession->start_time }} - {{ $reservation->gymSession->end_time }}</td>
                                <td>{{ $reservation->member_count }} نفر</td>
                                <td>
                                    <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-check"></i> تأیید
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reservations.reject', $reservation->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x"></i> رد
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">هیچ رزروی در انتظار تأیید وجود ندارد</div>
            @endif
        </div>
    </div>

    <!-- بخش مدیریت سانس‌ها -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="bi bi-calendar-plus"></i> مدیریت سانس‌ها</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sessions.add') }}" method="POST">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">تاریخ</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ساعت شروع</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ساعت پایان</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">ظرفیت</label>
                        <input type="number" name="max_capacity" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mt-4 bg-light p-3 rounded ">
                            <input type="checkbox" name="repeat_weekly" id="repeat_weekly" class="form-check-input" value="1">
                            <label for="repeat_weekly" class="form-check-label">تکرار هفتگی</label>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus"></i> افزودن سانس
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>ساعت</th>
                            <th>ظرفیت</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                        <tr>
                            <td>{{ $session->date }}</td>
                            <td>{{ $session->start_time }} - {{ $session->end_time }}</td>
                            <td>{{ $session->reserved_count }}/{{ $session->max_capacity }}</td>
                            <td>
                                @if($session->status === 'available')
                                    <span class="badge bg-success">موجود</span>
                                @elseif($session->status === 'full')
                                    <span class="badge bg-danger">تکمیل</span>
                                @else
                                    <span class="badge bg-warning">رزرو شده</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.sessions.delete', $session->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا مطمئن هستید؟')">
                                        <i class="bi bi-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- بخش اطلاعیه‌ها -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0"><i class="bi bi-megaphone"></i> مدیریت اطلاعیه‌ها</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.announcements.add') }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label class="form-label">عنوان</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">متن اطلاعیه</label>
                    <textarea name="content" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus"></i> افزودن اطلاعیه
                </button>
            </form>

            <h4 class="mb-3">لیست اطلاعیه‌ها</h4>
            @foreach($announcements as $announcement)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5>{{ $announcement->title }}</h5>
                        <form action="{{ route('admin.announcements.delete', $announcement->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا مطمئن هستید؟')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    <p>{{ $announcement->content }}</p>
                    <small class="text-muted">
                        ایجاد شده در: {{ $announcement->created_at }}
                    </small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection