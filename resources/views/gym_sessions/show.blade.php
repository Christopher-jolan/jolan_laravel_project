@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">درخواست الحاق به سانس</h4>
                </div>

                <div class="card-body">
                    <div class="session-info mb-4 p-3 bg-light rounded">
                        <h5 class="mb-3">
                            {{ jdate($gymSession->date)->format('Y/m/d') }} - 
                            {{ $gymSession->start_time }} تا {{ $gymSession->end_time }}
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="bi bi-people-fill"></i> ظرفیت: 
                                    {{ $gymSession->reservations->count() }}/{{ $gymSession->max_capacity }}
                                </p>
                                <p><i class="bi bi-person-badge"></i> رزروکننده: 
                                    {{ $gymSession->reservations->first()->user->name }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="bi bi-people"></i> تعداد اعضا: 
                                    {{ $gymSession->reservations->first()->team->member_count }}
                                </p>
                                <p><i class="bi bi-clock-history"></i> زمان باقیمانده: 
                                    {{ $gymSession->end_time->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('join-requests.store') }}">
                        @csrf
                        <input type="hidden" name="gym_session_id" value="{{ $gymSession->id }}">
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">پیام به رزروکننده:</label>
                            <textarea class="form-control" id="message" name="message" rows="3"
                                placeholder="لطفاً دلیل درخواست الحاق خود را توضیح دهید"></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-send-check"></i> ارسال درخواست الحاق
                            </button>
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> بازگشت
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection