@extends('layouts.app')

@section('title', 'جزئیات رزرو')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-info-circle"></i> جزئیات رزرو
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>اطلاعات سانس:</h5>
                            <p><i class="bi bi-calendar"></i> تاریخ: {{ jdate($reservation->gymSession->date)->format('Y/m/d') }}</p>
                            <p><i class="bi bi-clock"></i> زمان: {{ $reservation->gymSession->start_time }} تا {{ $reservation->gymSession->end_time }}</p>
                            <p><i class="bi bi-people"></i> ظرفیت سانس: {{ $reservation->gymSession->max_capacity }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>اطلاعات رزرو:</h5>
                            <p>
                                <i class="bi bi-person"></i> رزروکننده: 
                                {{ $reservation->user->name }}
                            </p>
                            <p>
                                <i class="bi bi-info-circle"></i> وضعیت: 
                                @if($reservation->status == 'pending')
                                    <span class="badge bg-warning">در انتظار تایید</span>
                                @elseif($reservation->status == 'confirmed')
                                    <span class="badge bg-success">تایید شده</span>
                                @elseif($reservation->status == 'cancelled')
                                    <span class="badge bg-secondary">لغو شده</span>
                                @endif
                            </p>
                            <p><i class="bi bi-people"></i> تعداد اعضا: {{ $reservation->member_count }}</p>
                        </div>
                    </div>

                    @if($reservation->team)
                        <div class="card mt-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-people-fill"></i> مدیریت تیم
                                    <a href="{{ route('teams.show', $reservation->team->id) }}" 
                                       class="btn btn-sm btn-outline-light float-left">
                                        <i class="bi bi-arrow-left"></i> مشاهده کامل تیم
                                    </a>
                                </h5>
                            </div>
                            <div class="card-body">
                                <h6>نام تیم: {{ $reservation->team->name }}</h6>
                                <p>تعداد اعضا: {{ $reservation->team->member_count }}</p>
                                
                                <h6 class="mt-4">اعضای تیم:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>نام</th>
                                                <th>شماره دانشجویی</th>
                                                <th>نقش</th>
                                                <th>عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reservation->team->members as $member)
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
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <!-- دکمه افزودن عضو جدید -->
                                <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                    <i class="bi bi-plus"></i> افزودن عضو جدید
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($reservation->notes)
                        <div class="alert alert-light mt-4">
                            <h5><i class="bi bi-card-text"></i> توضیحات:</h5>
                            <p>{{ $reservation->notes }}</p>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-right"></i> بازگشت به لیست
                        </a>

                        <div class="d-flex gap-2">
                            @if(in_array($reservation->status, ['pending', 'confirmed']))
                                <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> ویرایش
                                </a>
                                
                                <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('آیا از لغو این رزرو مطمئن هستید؟')">
                                        <i class="bi bi-x-circle"></i> لغو رزرو
                                    </button>
                                </form>
                            @endif
                            
                            @if($reservation->status == 'cancelled')
                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('آیا از حذف این رزرو مطمئن هستید؟')">
                                        <i class="bi bi-trash"></i> حذف رزرو
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal افزودن عضو جدید -->
@if($reservation->team)
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">افزودن عضو جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('team-members.store') }}" method="POST">
                @csrf
                <input type="hidden" name="team_id" value="{{ $reservation->team->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">نام کامل</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_number" class="form-label">شماره دانشجویی</label>
                        <input type="text" class="form-control" id="student_number" name="student_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">تلفن (اختیاری)</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection