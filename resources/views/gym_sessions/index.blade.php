@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">لیست سانس‌های ورزشی</h4>
                </div>

                <div class="card-body">
                    @foreach($gymSessions as $session)
                        @if($session->status !== 'expired')
                            <div class="session-card mb-4 p-3 border rounded 
                                {{ $session->reservations->count() >= $session->max_capacity ? 'bg-light text-muted' : 'bg-white' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">
                                        {{ jdate($session->date)->format('Y/m/d') }} - 
                                        {{ $session->start_time }} تا {{ $session->end_time }}
                                    </h5>
                                    <span class="badge 
                                        {{ $session->reservations->count() >= $session->max_capacity ? 'bg-secondary' : 'bg-success' }}">
                                        {{ $session->reservations->count() >= $session->max_capacity ? 'تکمیل ظرفیت' : 'ظرفیت موجود' }}
                                    </span>
                                </div>

                                <div class="session-details">
                                    <p class="mb-1">
                                        <i class="bi bi-people-fill"></i> 
                                        ظرفیت: {{ $session->reservations->count() }}/{{ $session->max_capacity }}
                                    </p>
                                    
                                    @if($session->reservations->count() > 0)
                                        <p class="mb-1">
                                            <i class="bi bi-person-badge"></i>
                                            رزروکننده: {{ $session->reservations->first()->user->name }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="bi bi-people"></i>
                                            تعداد اعضا: {{ $session->reservations->first()->team->member_count ?? 1 }}
                                        </p>
                                    @endif
                                </div>

                                @auth
                                    @if($session->reservations->count() < $session->max_capacity)
                                        <a href="{{ route('sessions.show', $session->id) }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-circle"></i> درخواست الحاق
                                        </a>
                                    @else
                                        <button class="btn btn-secondary mt-2" disabled>
                                            <i class="bi bi-lock"></i> سانس تکمیل است
                                        </button>
                                    @endif
                                @else
                                    <div class="alert alert-info mt-2">
                                        برای درخواست الحاق باید <a href="{{ route('login') }}">وارد</a> شوید.
                                    </div>
                                @endauth
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection