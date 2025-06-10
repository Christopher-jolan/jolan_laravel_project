@extends('layouts.app')

@section('title', 'ویرایش رزرو')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> ویرایش رزرو
                    </h4>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <h5>سانس مورد نظر:</h5>
                        <p class="mb-1"><i class="bi bi-calendar"></i> تاریخ: {{ jdate($reservation->gymSession->date)->format('Y/m/d') }}</p>
                        <p class="mb-1"><i class="bi bi-clock"></i> زمان: {{ $reservation->gymSession->start_time }} تا {{ $reservation->gymSession->end_time }}</p>
                        <p class="mb-0"><i class="bi bi-people"></i> ظرفیت باقیمانده: {{ $reservation->gymSession->max_capacity - $reservation->gymSession->reserved_count + $reservation->member_count }}</p>
                    </div>

                    <form method="POST" action="{{ route('reservations.update', $reservation->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="reservation_type" class="form-label">نوع رزرو:</label>
                            <select class="form-select" id="reservation_type" name="reservation_type" required>
                                <option value="individual" {{ $reservation->team_id ? '' : 'selected' }}>انفرادی</option>
                                <option value="team" {{ $reservation->team_id ? 'selected' : '' }}>تیمی</option>
                            </select>
                        </div>

                        <div id="team_section" style="display: {{ $reservation->team_id ? 'block' : 'none' }};">
                            @if($userTeams->count() > 0)
                                <div class="form-group mb-3">
                                    <label for="team_id" class="form-label">انتخاب تیم:</label>
                                    <select class="form-select" id="team_id" name="team_id">
                                        @foreach($userTeams as $team)
                                            <option value="{{ $team->id }}" {{ $reservation->team_id == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }} ({{ $team->member_count }} نفر)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> شما هنوز در هیچ تیمی عضو نیستید.
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <label for="member_count" class="form-label">تعداد اعضا (شامل خودتان):</label>
                                <input type="number" class="form-control" id="member_count" name="member_count" 
                                    min="2" max="{{ $reservation->gymSession->max_capacity - $reservation->gymSession->reserved_count + $reservation->member_count }}"
                                    value="{{ old('member_count', $reservation->member_count) }}">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes" class="form-label">توضیحات (اختیاری):</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $reservation->notes) }}</textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> ذخیره تغییرات
                            </button>
                            <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> انصراف
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('reservation_type').addEventListener('change', function() {
    const teamSection = document.getElementById('team_section');
    if (this.value === 'team') {
        teamSection.style.display = 'block';
        document.getElementById('member_count').setAttribute('required', '');
    } else {
        teamSection.style.display = 'none';
        document.getElementById('member_count').removeAttribute('required');
    }
});
</script>
@endsection