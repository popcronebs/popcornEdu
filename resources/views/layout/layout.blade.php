@php
//세션의 login_type 이 admin이면
if(session()->get('login_type') == 'admin'){
    $layout = 'layout.admin_layout';
}else if(session()->get('login_type') == 'teacher'){
    $layout = 'layout.teacher_layout';
}else if(session()->get('login_type') == 'student'){
    $layout = 'layout.student_layout';
}else if(session()->get('login_type') == 'parent'){
    $layout = 'layout.parent_layout';
}
else{
    $layout = 'layout.teacher_layout';
}
@endphp

@include($layout)
