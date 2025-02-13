<div class="profile-wrap d-flex flex-wrap">
    <div class="profile-img">
        @if(session()->get('login_type') == 'student' && !empty($state->getStudent()->profile_img_path))
            @php
                $profile_img_path = asset('storage/uploads/user_profile/student/'.$state->getStudent()->profile_img_path);
            @endphp
            <img src="{{ $profile_img_path }}" alt="" onerror="this.src='/images/svg/profile_emtiy_avata.svg'" style="width:56px;height:56px">
        @elseif(session()->get('login_type') == 'teacher' && !empty($teacher->profile_img_path))
            <img src="{{ asset('storage/uploads/user_profile/teacher/'.$teacher->profile_img_path) }}" alt="" style="width:56px;height:56px">
        @elseif(session()->get('login_type') == 'parent' && !empty($parent->profile_img_path))
            <img src="{{ asset('storage/uploads/user_profile/parent/'.$parent->profile_img_path) }}" alt="">
        @else
            <img src="/images/svg/profile_emtiy_avata.svg" alt="">
        @endif
    </div>
    <div class="profile-info d-flex flex-column justify-content-end">
        <div class="profile-wrap-name text-start">
            <span class="profile-label">
                @if(session()->get('login_type') == 'student')
                    {{ session()->get('student_name') }}
                @elseif(session()->get('login_type') == 'parent')
                    <span data-top-profile="student_name"> {{$sel_student->student_name}} </span> <span class="grade-number text-sb-16px" data-top-profile="grade_name">{{$sel_student->grade_name}}</span>
                @elseif(session()->get('login_type') == 'teacher')
                    {{ session()->get('teach_name') }}
                @endif
            </span>
        </div>
        <div class="profile-grade d-flex flex-wrap align-items-center justify-content-between">
            <div class="grade-wrap">
                <span class="grade-number">

                    @if(session()->get('login_type') == 'student')
                        {{ $state->getStudent()->grade_name }} {{ $state->getStudent()->class_name }}반
                    @elseif(session()->get('login_type') == 'parent')
                        학부모
                    @elseif(session()->get('login_type') == 'teacher')
                        선생님
                    @endif
                </span>
            </div>
            <div class="grade-letter">
                <a href="/teacher/messenger">
                    <img src="/images/svg/letter.webp?1" alt="" width="32">
                </a>
            </div>
        </div>
    </div>
    <div class="profile-info-gnb" onclick="parentLayoutProfileOpen(this);">
        <img src="/images/svg/btn_arrow_down.svg" alt="">
    </div>
</div>
