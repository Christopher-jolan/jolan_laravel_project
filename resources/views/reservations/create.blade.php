@extends('layouts.app')

@section('title', 'رزرو سانس ورزشی')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-center">
                        <i class="bi bi-calendar-plus"></i> رزرو سانس ورزشی
                    </h4>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- اطلاعات سانس -->
                    <div class="alert alert-info">
                        <h5 class="text-center">سانس انتخابی</h5>
                        <p><i class="bi bi-calendar"></i> تاریخ: {{ jdate($gymSession->date)->format('Y/m/d') }}</p>
                        <p><i class="bi bi-clock"></i> زمان: {{ $gymSession->start_time }} تا {{ $gymSession->end_time }}</p>
                        <p><i class="bi bi-people"></i> ظرفیت باقیمانده: {{ $gymSession->max_capacity - $gymSession->reserved_count }}</p>
                    </div>

                    <form method="POST" action="{{ route('reservations.store', $gymSession->id) }}" id="reservationForm">
                        @csrf

                        <!-- انتخاب نوع رزرو -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">نوع رزرو:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reservation_type" id="individual" value="individual" checked>
                                    <label class="form-check-label" for="individual">
                                        <i class="bi bi-person"></i> انفرادی
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reservation_type" id="team" value="team">
                                    <label class="form-check-label" for="team">
                                        <i class="bi bi-people"></i> تیمی
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- بخش رزرو تیمی -->
                        <div id="teamSection" style="display: none;">
                            @if($userTeams->count() > 0)
                                <div class="form-group mb-3">
                                    <label for="team_id" class="form-label fw-bold">انتخاب تیم موجود:</label>
                                    <select class="form-select" id="team_id" name="team_id">
                                        @foreach($userTeams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }} ({{ $team->member_count }} عضو)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <p class="text-center text-muted mb-3">- یا -</p>
                            @endif

                            <!-- ایجاد تیم جدید -->
                            <div id="createTeamSection" class="border p-3 rounded mb-3" style="display: {{ $userTeams->count() == 0 ? 'block' : 'none' }};">
                                <h5 class="text-center mb-3"><i class="bi bi-plus-circle"></i> ایجاد تیم جدید</h5>
                                
                                <div class="form-group mb-3">
                                    <label for="team_name" class="form-label">نام تیم:</label>
                                    <input type="text" class="form-control" id="team_name" name="team_name" data-required-if-team>
                                </div>

                                <div class="members-container">
                                    <h6 class="mb-3"><i class="bi bi-people-fill"></i> اعضای تیم:</h6>
                                    
                                    <div class="member-item mb-3 border-bottom pb-3">
                                        <div class="row g-2">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="members[0][name]" placeholder="نام کامل عضو" data-required-if-team>
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control" name="members[0][student_number]" placeholder="شماره دانشجویی" data-required-if-team>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-outline-danger w-100 remove-member" disabled>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary add-member mb-3">
                                    <i class="bi bi-plus"></i> افزودن عضو جدید
                                </button>
                            </div>
                        </div>

                        <!-- توضیحات -->
                        <div class="form-group mb-4">
                            <label for="notes" class="form-label fw-bold">توضیحات (اختیاری):</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>

                        <!-- دکمه‌های اقدام -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2" id="submitBtn">
                                <i class="bi bi-check-circle"></i> ثبت نهایی رزرو
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary py-2">
                                <i class="bi bi-x-circle"></i> انصراف و بازگشت
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reservationForm');
    const reservationTypeRadios = document.querySelectorAll('input[name="reservation_type"]');
    const teamSection = document.getElementById('teamSection');
    const submitBtn = document.getElementById('submitBtn');
    
    // نمایش/مخفی کردن بخش تیم
    reservationTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            teamSection.style.display = this.value === 'team' ? 'block' : 'none';
            updateRequiredFields();
        });
    });

    // نمایش فرم ایجاد تیم جدید
    const existingTeamSelect = document.getElementById('team_id');
    if (existingTeamSelect) {
        existingTeamSelect.addEventListener('change', function() {
            document.getElementById('createTeamSection').style.display = 'none';
            updateRequiredFields();
        });

        const showCreateTeamBtn = document.createElement('button');
        showCreateTeamBtn.type = 'button';
        showCreateTeamBtn.className = 'btn btn-outline-primary mb-3 w-100';
        showCreateTeamBtn.innerHTML = '<i class="bi bi-plus-circle"></i> ایجاد تیم جدید';
        showCreateTeamBtn.addEventListener('click', function() {
            document.getElementById('createTeamSection').style.display = 'block';
            updateRequiredFields();
        });

        existingTeamSelect.parentNode.insertBefore(showCreateTeamBtn, existingTeamSelect.nextSibling);
    }

    // مدیریت اعضای تیم
    let memberCount = 1;
    const membersContainer = document.querySelector('.members-container');
    
    document.querySelector('.add-member')?.addEventListener('click', function() {
        const newMember = document.querySelector('.member-item').cloneNode(true);
        newMember.innerHTML = newMember.innerHTML.replace(/\[0\]/g, `[${memberCount}]`);
        
        const removeBtn = newMember.querySelector('.remove-member');
        removeBtn.disabled = false;
        removeBtn.addEventListener('click', function() {
            newMember.remove();
            updateRequiredFields();
        });
        
        membersContainer.appendChild(newMember);
        memberCount++;
        updateRequiredFields();
    });

    // اعتبارسنجی فرم قبل از ارسال
    form.addEventListener('submit', function(e) {
        const isTeamReservation = document.querySelector('input[name="reservation_type"]:checked').value === 'team';
        let isValid = true;

        if (isTeamReservation) {
            // اعتبارسنجی برای رزرو تیمی
            const teamId = document.getElementById('team_id');
            const createTeamSection = document.getElementById('createTeamSection');
            
            if (teamId && createTeamSection.style.display === 'none') {
                // استفاده از تیم موجود
                if (!teamId.value) {
                    alert('لطفاً یک تیم انتخاب کنید');
                    isValid = false;
                }
            } else {
                // ایجاد تیم جدید
                const teamName = document.getElementById('team_name');
                if (!teamName.value.trim()) {
                    alert('لطفاً نام تیم را وارد کنید');
                    teamName.focus();
                    isValid = false;
                }

                const memberInputs = document.querySelectorAll('[name^="members["][name$="[name]"]');
                if (memberInputs.length === 0) {
                    alert('لطفاً حداقل یک عضو به تیم اضافه کنید');
                    isValid = false;
                } else {
                    memberInputs.forEach(input => {
                        if (!input.value.trim()) {
                            alert('لطفاً نام تمام اعضا را وارد کنید');
                            input.focus();
                            isValid = false;
                            return;
                        }
                    });
                }

                const studentNumberInputs = document.querySelectorAll('[name^="members["][name$="[student_number]"]');
                studentNumberInputs.forEach(input => {
                    if (!input.value.trim()) {
                        alert('لطفاً شماره دانشجویی تمام اعضا را وارد کنید');
                        input.focus();
                        isValid = false;
                        return;
                    }
                });
            }
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // به‌روزرسانی فیلدهای required بر اساس نوع رزرو
    function updateRequiredFields() {
        const isTeamReservation = document.querySelector('input[name="reservation_type"]:checked').value === 'team';
        const teamId = document.getElementById('team_id');
        const createTeamSection = document.getElementById('createTeamSection');
        
        // تنظیم required برای فیلدهای تیم
        document.querySelectorAll('[data-required-if-team]').forEach(field => {
            field.required = isTeamReservation && 
                (!teamId || createTeamSection.style.display === 'block');
        });

        // تنظیم required برای select تیم موجود
        if (teamId) {
            teamId.required = isTeamReservation && 
                createTeamSection.style.display === 'none';
        }
    }

    // مقداردهی اولیه
    updateRequiredFields();
});
</script>

<style>
.member-item:not(:last-child) {
    margin-bottom: 15px;
}
.remove-member {
    height: 100%;
}
</style>
@endsection