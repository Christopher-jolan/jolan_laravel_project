@extends('layouts.app')

@section('title', 'صفحه اصلی - سیستم نوبت‌دهی سالن ورزشی')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-4">
                <h2 class="text-center mb-4" style="color: var(--primary-color);">
                    <i class="bi bi-calendar-event"></i> سانس‌های ورزشی
                </h2>
                
                @foreach ($gymSessions as $session)
                    @php
                        $totalReserved = 0;
                        $reservationOwner = null;
                        
                        if($session->reservations->count() > 0) {
                            foreach($session->reservations as $reservation) {
                                $totalReserved += $reservation->member_count;
                                $reservationOwner = $reservation->user_id;
                            }
                        }
                        
                        $isFull = $totalReserved >= $session->max_capacity;
                        $remainingCapacity = $session->max_capacity - $totalReserved;
                    @endphp

                    <div class="card mb-4 {{ $isFull ? 'bg-light' : '' }}">
                        <div class="card-header" style="background: linear-gradient(to right, var(--primary-color), var(--secondary-color)); color: white;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    {{ jdate($session->date)->format('Y/m/d') }} - 
                                    {{ $session->start_time }} تا {{ $session->end_time }}
                                </h5>
                                <span class="badge {{ $isFull ? 'bg-secondary' : 'bg-success' }}">
                                    {{ $isFull ? 'تکمیل ظرفیت' : 'ظرفیت موجود' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="session-details mb-3">
                                <p class="mb-1">
                                    <i class="bi bi-people-fill"></i> 
                                    ظرفیت: {{ $totalReserved }}/{{ $session->max_capacity }}
                                    @if(!$isFull)
                                        ({{ $remainingCapacity }} ظرفیت خالی)
                                    @endif
                                </p>
                                
                                @if($session->reservations->count() > 0)
                                    <p class="mb-1">
                                        <i class="bi bi-person-badge"></i>
                                        رزروکننده: {{ $session->reservations->first()->user->name }}
                                    </p>
                                    <p class="mb-1">
                                        <i class="bi bi-people"></i>
                                        تعداد اعضا: {{ $session->reservations->first()->member_count }}
                                    </p>
                                @endif
                            </div>
                            
                            @auth
                                @if($session->status !== 'full')
                                    @if($session->reservations->count() > 0)
                                        @if($session->reservations->first()->user_id == auth()->id())
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('reservations.show', $session->reservations->first()->id) }}" class="btn btn-info">
                                                    <i class="bi bi-people"></i> مدیریت رزرو
                                                </a>
                                                @if($session->reservations->first()->team)
                                                    <a href="{{ route('teams.show', $session->reservations->first()->team->id) }}" class="btn btn-secondary">
                                                        <i class="bi bi-people-fill"></i> مدیریت تیم
                                                    </a>
                                                @endif
                                            </div>
                                        @else
                                            <a href="{{ route('join-requests.create', $session->reservations->first()->id) }}" class="btn btn-success">
                                                <i class="bi bi-plus-circle"></i> الحاق به تیم
                                            </a>
                                        @endif
                                    @else
                                        <a href="{{ route('reservations.create', $session->id) }}" class="btn btn-primary">
                                            <i class="bi bi-calendar-plus"></i> رزرو این سانس
                                        </a>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header" style="background: linear-gradient(to right, var(--primary-color), var(--secondary-color)); color: white;">
                    <h5 class="mb-0">
                        <i class="bi bi-megaphone"></i> اطلاعیه‌ها
                    </h5>
                </div>
                
                <div class="card-body">
                    @foreach($activeAnnouncements as $announcement)
                    <div class="announcement mb-3 p-3 rounded" style="background-color: var(--accent-color);">
                        <h6><i class="bi bi-exclamation-triangle"></i> {{ $announcement->title }}</h6>
                        <p>{{ $announcement->content }}</p>
                        <small class="text-muted">
                            تا {{ jdate($announcement->end_time)->format('Y/m/d H:i') }}
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection