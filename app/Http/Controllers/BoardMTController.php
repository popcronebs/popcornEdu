<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class BoardMTController extends Controller
{
    //공지사항 게시판
    public function notice()
    {
        $login_type = session()->get('login_type');
        $board_top_codes = $this->boardTopCode('notice');
        return view('admin.admin_board_notice', [
            'board_top_codes' => $board_top_codes,
            'login_type' => $login_type
        ]);
    }

    //자주 묻는 질문 게시판
    public function faq()
    {
        $login_type = session()->get('login_type');
        $board_top_codes = $this->boardTopCode('faq');
        return view('admin.admin_board_faq', [
            'board_top_codes' => $board_top_codes,
            'login_type' => $login_type
        ]);
    }

    //시스템 / 사용자 문의 게시판
    public function qna()
    {
        $login_type = session()->get('login_type');
        $board_top_codes = $this->boardTopCode('qna');
        return view('admin.admin_board_qna', [
            'board_top_codes' => $board_top_codes,
            'login_type' => $login_type
        ]);
    }

    //이벤트 게시판
    public function event()
    {
        $login_type = session()->get('login_type');
        $board_top_codes = $this->boardTopCode('event');
        return view('admin.admin_board_event', [
            'board_top_codes' => $board_top_codes,
            'login_type' => $login_type
        ]);
    }

    //응원 메시지
    public function support()
    {
        $login_type = session()->get('login_type');
        $board_top_codes = $this->boardTopCode('support');
        return view('admin.admin_board_support', [
            'board_top_codes' => $board_top_codes,
            'login_type' => $login_type
        ]);
    }

    // 학습 Q&A
    public function sdqna()
    {
        $login_type = session()->get('login_type');
        $board_top_codes = $this->boardTopCode('sdqna');
        return view('admin.admin_board_sdqna', [
            'board_top_codes' => $board_top_codes,
            'login_type' => $login_type
        ]);
    }

    // 학습자료 learning
    public function learning()
    {
        //상단 탭 분류
        $login_type = session()->get('login_type');
        $board_top_codes = $this->boardTopCode('learning');
        $main_code = $_COOKIE['main_code'];
        $codes_all = \App\Code::where('main_code', $main_code)->get();

        //과목 분류
        $subject_codes = $codes_all->where('code_category', 'subject')->where('code_step', '!=', 0);
        //학년 분류
        $grade_codes = $codes_all->where('code_category', 'grade')->where('code_step', '!=', 0);
        //학기 분류
        $semester_codes = $codes_all->where('code_category', 'semester')->where('code_step', '!=', 0);
        //대단원 분류
        $major_unit = $codes_all->where('function_code', 'major_unit')->where('code_step', '!=', 0)->pluck('id');
        $major_unit_codes = $codes_all->whereIn('code_pt', $major_unit);
        //중단원 분류
        $medium_unit = $codes_all->where('function_code', 'medium_unit')->where('code_step', '!=', 0)->pluck('id');
        $medium_unit_codes = $codes_all->whereIn('code_pt', $medium_unit);
        //유저 그룹
        $user_groups = \App\UserGroup::where('main_code', $main_code)->get();

        return view('admin.admin_board_learning', ['board_top_codes' => $board_top_codes,
                                                    'codes_all' => $codes_all,
                                                    'subject_codes' => $subject_codes,
                                                    'grade_codes' => $grade_codes,
                                                    'semester_codes' => $semester_codes,
                                                    'major_unit_codes' => $major_unit_codes,
                                                    'medium_unit_codes' => $medium_unit_codes,
                                                    'user_groups' => $user_groups,
                                                    'login_type' => $login_type
                                                    ]);
    }

    // board_top_code 함수로 가져오기
    private function boardTopCode($function_code)
    {
        $main_code = $_COOKIE['main_code'];
        $boardIds = \App\Code::where('code_category', 'board')
            ->where('function_code', $function_code)
            ->where('main_code', $main_code)
            ->pluck('id');
        $board_top_codes = \App\Code::whereIn('code_pt', $boardIds)->get();
        return $board_top_codes;
    }

    //게시판 가져오기.boardSelect
    public function boardSelect(Request $request)
    {
        $category = $request->input('category');
        $board_seq = $request->input('board_seq');
        $board_name = $request->input('board_name');
        $board_page_max = $request->input('board_page_max');
        $team_code = $request->input('team_code');
        $search_word = $request->input('search_word');
        $search_type = $request->input('search_type');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $comment_type = $request->input('comment_type');
        $is_view = $request->input('is_view');
        $is_content = $request->input('is_content');

        $page = $request->input('page');
        $page = $page ?? 1;

        //게시판 테이블에서 기본 : board_name으로 검색
        $board = \App\Board::where('board_name', $board_name);
        // ->where('board_name', '!=', 'faq');

        //WHERE
        //category 가 있으면 : category로 검색
        if ($category && strlen($category)) {
            $board = $board->where('category', $category);
        }
        //게시판 seq가 있으면 : seq로 검색
        if ($board_seq && strlen($board_seq)) {
            $board = $board->where('boards.id', $board_seq);
            if($is_view == '1'){
                $update_board = \App\Board::where('id', $board_seq)->first();
                // views 가져와서 1증가 후 저장
                $views = $update_board->views;
                $update_board->views = $views + 1;
                $update_board->save();
            }

        }
        //팀코드가 있으면 : 팀코드로 검색
        if ($team_code && strlen($team_code)) {
            $board = $board->where('writer_seq', $team_code);
        }
        //검색어가 있으면
        if ($search_word && strlen($search_word)) {
            //$search_type == '' 제목, 내용으로 검색
            if($search_type == '' || $search_type == 'title')
                $board = $board->where(function ($query) use ($search_word) {
                    $query->where('title', 'like', '%' . $search_word . '%')
                        ->orWhere('content', 'like', '%' . $search_word . '%');
                });
            // 과목
            else if($search_type == 'subject')
                $board = $board->where('subject.code_name', 'like', '%' . $search_word . '%');
            // 학년
            else if($search_type == 'grade')
                $board = $board->where('grade.code_name', 'like', '%' . $search_word . '%');
            // 학기
            else if($search_type == 'semester')
                $board = $board->where('semester.code_name', 'like', '%' . $search_word . '%');
            // 대단원
            else if($search_type == 'major_unit')
                $board = $board->where('major_unit.code_name', 'like', '%' . $search_word . '%');
            // 중단원
            else if($search_type == 'medium_unit')
                $board = $board->where('medium_unit.code_name', 'like', '%' . $search_word . '%');
            else if($search_type == 'writer_name'){
                $board = $board->where('boards.writer_name', 'like', '%' . $search_word . '%');
            }
        }
        // 응원메시지면서 날짜가 있으면 : 날짜로 검색
        if($board_name == 'support' && $start_date && $end_date){
            $board = $board->where('start_date', '<=', $end_date)->where('end_date', '>=', $start_date);
        }
        // Q&A / 응원메시지 일경우 comment_type에 따라 검색 ( 답변대기, 답변완료)
        if(($board_name == 'qna'|| $board_name == 'sdqna') && strlen($comment_type) > 0){
            if($comment_type == 'wait'){
                $board = $board->where(function ($query) {
                $query->whereNull('comment_wr_seq')
                      ->orWhere('comment_wr_seq', '=', '');
                });
            }
            else if($comment_type == 'complete'){
                //comment<>'' , comment_wr_seq <> ''
                $board = $board->where('comment_wr_seq', '<>', '')->where('comment', '<>', '');

            }
        }

        //board_seq 가 없으면 페이지변수에 따른 보드 리스트 페이징
        //SELECT
        $board =
            $board->select(
                'boards.id',
                'boards.board_idx',
                'boards.board_name',
                'boards.board_type',
                'boards.category',
                'boards.title',
                'boards.writer_name',
                'boards.writer_seq',
                'boards.writer_type',
                'boards.views',
                DB::raw("   case
                                when ifnull(boards.writer_email, '') = ''
                                then
                                    case
                                        when ifnull(boards.writer_type, '') = 'parent'
                                        then pt.parent_email
                                        when ifnull(boards.writer_type, '') = 'student'
                                        then st.student_email
                                    end
                                else boards.writer_email
                            end as writer_email"
                ),
                'boards.created_at',
                'boards.is_teacher',
                'boards.is_parent',
                'boards.is_student',
                'boards.is_teacher2',
                'boards.is_important',
                'boards.is_region',
                'boards.is_team',
                'bdupfile_path',
                'boards.start_date',
                'boards.end_date',
                'boards.comment_wr_seq',
                // DB::raw("length(ifnull(boards.comment, '')) as comment_length"),
                'boards.region_name',
                'boards.region_seq',
                'boards.team_code',
                'boards.school_name',
                'boards.grade',
                'boards.is_use',
                'boards.student_seq',
                'boards.student_name',
                'pt.parent_email as writer_email',
                DB::raw("   case
                                when ifnull(boards.writer_phone, '') = ''
                                then
                                    case
                                        when ifnull(boards.writer_type, '') = 'parent'
                                        then pt.parent_phone
                                        when ifnull(boards.writer_type, '') = 'student'
                                        then st.student_phone
                                    end
                                else boards.writer_phone
                            end as writer_phone"
                ),
                'boards.subject',
                'boards.open_size',
                'boards.semester',
                'boards.major_unit',
                'boards.medium_unit'
            );


        //ADD SELECT

        // 카테고리 이름 가져오기.
        $board = $board->addSelect('category.code_name as category_name');

        // board_seq가 있으면 select에 conent를 추가한다. /
        // [ content와 comment를 따로 추가해서 가져오는 이유는 게시판일경우
        // 내용의 글이 많아지면 불러오는데 시간이 많이 소요될수 있기 때문 ]
        //( 수정할때는 단 하나의 정보만 정보를 불러오기 위해서)
        if ($board_seq && strlen($board_seq) > 0) {
            $board = $board->addSelect('content');
            $board = $board->addSelect('comment');
        }
        //응원메시지일경우 리스트에서 content 불러온다.
        // 추후 응원일경우 글자수 제한을 둘수 있음.
        if($board_name =='support'){
            $board = $board->addSelect('content');
        }
        //학습자료일경우 code_seq를 붙여준다.
        if($board_name == 'learning'){
            $board = $board->addSelect('subject.code_name as subject_name');
            $board = $board->addSelect('grade.code_name as grade_name');
            $board = $board->addSelect('semester.code_name as semester_name');
            $board = $board->addSelect('major_unit.code_name as major_unit_name');
            $board = $board->addSelect('medium_unit.code_name as medium_unit_name');
        }
        //$is_content == Y 이면 content를 불러온다.
        if($is_content == 'Y'){
            $board = $board->addSelect('content');
        }

        //JOIN
        // 첨부파일
        $board =  $board
        ->leftJoin('board_uploadfiles', function($join){
            $join->on('boards.id', '=', 'board_uploadfiles.board_seq')
            ->where('board_uploadfiles.bdupfile_type', '=', 'thumbnail');
        });
        // 학부모 전화번호
        $board =  $board
        ->leftJoin('parents as pt', function ($join) {
            $join->on('pt.id', '=', 'boards.writer_seq')
                ->where('boards.writer_type', '=', 'parent');
        });
        // 학생 이메일
        $board =  $board
        ->leftJoin('students as st', function ($join) {
            $join->on('st.id', '=', 'boards.writer_seq')
                ->where('boards.writer_type', '=', 'student');
        });
        //category
        $board = $board
        ->leftJoin('codes as category', function ($join) {
            $join->on('category.id', '=', 'boards.category');
        });
        // 학습자료 일경우
        if($board_name == 'learning'){
            //과목
            $board =  $board
            ->leftJoin('codes as subject', function ($join) {
                $join->on('subject.id', '=', 'boards.subject');
            });
            //학년
            $board =  $board
            ->leftJoin('codes as grade', function ($join) {
                $join->on('grade.id', '=', 'boards.grade');
            });
            //학기
            $board =  $board
            ->leftJoin('codes as semester', function ($join) {
                $join->on('semester.id', '=', 'boards.semester');
            });
            //대단원
            $board =  $board
            ->leftJoin('codes as major_unit', function ($join) {
                $join->on('major_unit.id', '=', 'boards.major_unit');
            });
            //중단원
            $board =  $board
            ->leftJoin('codes as medium_unit', function ($join) {
                $join->on('medium_unit.id', '=', 'boards.medium_unit');
            });
        }

        //ORDER BY
        $board =  $board
        // order by if(is_important = 'Y', '1', '') asc
        ->orderBy(DB::raw("if(boards.is_important = 'Y', 0, 1)"), 'asc')
        ->orderBy('boards.id', 'desc');

        //SQL
        // $reulstSql = $board->toSql();
        $reulstSql = '';

        //PAGING
        $board = $board->paginate($board_page_max, ['*'], 'page', $page);

        //결과
        $resultCode = 'success';
        $result = array(
            'sql' => $reulstSql,
            'board' => $board,
            'resultCode' => $resultCode
        );
        //board_seq(수정 및 보기)가 있으면 첨부파일리스트(bdupfilelist)를 불러온다.
        if ($board_seq && strlen($board_seq) > 0) {
            $board_groups = \App\BoardGroup::where('board_seq', $board_seq)->get();
            $result['board_groups'] = $board_groups;

            $bdupfile = $this->bdupfilelist($request);
            $result['bdupfile'] = $bdupfile;
        }
        return response()->json($result, 200);
    }

    //게시판 글쓰기
    public function boardInsert(Request $request)
    {
        $board_seq = $request->input('board_seq');
        $board_name = $request->input('board_name');
        $category = $request->input('category');
        $title = $request->input('title');
        $content = $request->input('content');
        $writer_seq = $request->input('writer_seq');
        $writer_type = $request->input('writer_type');
        $writer_name = $request->input('writer_name');
        $board_idx = $request->input('board_idx');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $is_teacher = $request->input('is_teacher');
        $is_parent = $request->input('is_parent');
        $is_student = $request->input('is_student');
        $is_teacher2 = $request->input('is_teacher2');
        $is_important = $request->input('is_important');
        $is_region = $request->input('is_region');
        $is_team = $request->input('is_team');
        $board_uploadfiles_seqs = $request->input('board_uploadfiles_seqs');
        $board_thumbnails_seqs = $request->input('board_thumbnails_seqs');

        $writer_phone = $request->input('writer_phone');
        $region_name = $request->input('region_name');
        $region_seq = $request->input('region_seq');
        $team_code = $request->input('team_code');
        $school_name = $request->input('school_name');
        $grade = $request->input('grade');
        $student_seq = $request->input('student_seq');
        $student_name = $request->input('student_name');
        $is_use = $request->input('is_use');

        $subject_code = $request->input('subject_code');
        $grade_code = $request->input('grade_code');
        $semester_code = $request->input('semester_code');
        $major_unit = $request->input('major_unit');
        $medium_unit = $request->input('medium_unit');
        $group_seqs = $request->input('group_seqs');
        $open_size = $request->input('open_size');



        //$board_seq가 있으면 수정, 없으면 저장
        if ($board_seq && strlen($board_seq) > 0) {
            $board = \App\Board::find($board_seq);
        } else {
            $board = new \App\Board;
        }
        //게시판 테이블에 저장
        //시스템 사용문의 쪽에서 들어오면 관리자쪽에서 답변을 달아야 하기 때문에 comment에 저장한다.
        //관리자 작성자Seq도 답변작성자Seq로 저장한다.
        if ($board_name == 'qna' || $board_name == 'sdqna') {
            $board->comment = $content;
            //session에서 관리자 seq를 가져온다.
            $board->comment_wr_seq = session()->get('teach_seq');
        }
        else {
            $board->board_name = $board_name;
            $board->category = $category;
            $board->title = $title;
            $board->content = $content;
            $board->writer_seq = $writer_seq;
            $board->writer_name = $writer_name;
            $board->writer_type = $writer_type;
            $board->board_idx = $board_idx;
            $board->start_date = $start_date;
            $board->end_date = $end_date;
            $board->is_teacher = $is_teacher;
            $board->is_parent = $is_parent;
            $board->is_student = $is_student;
            $board->writer_phone = $writer_phone;
            $board->region_name = $region_name;
            $board->region_seq = $region_seq;
            $board->team_code = $team_code;
            $board->school_name = $school_name;
            $board->grade = $grade;
            $board->student_seq = $student_seq;
            $board->student_name = $student_name;
            $board->is_use = $is_use;

            $board->is_teacher2 = $is_teacher2;
            $board->is_important = $is_important;
            $board->is_region = $is_region;
            $board->is_team = $is_team;

            $board->subject = $subject_code;
            if(strlen($grade_code) > 0) $board->grade = $grade_code;
            $board->semester = $semester_code;
            $board->major_unit = $major_unit;
            $board->medium_unit = $medium_unit;
            $board->open_size = $open_size;
        }
        $board->save();
        $board_seq = $board->id;

        //$group_seqs(유저권한) 있을시 board_groups 테이블에 저장
        if(strlen($group_seqs) > 0){
            $group_seqs_arr = explode(",", $group_seqs);
            // 우선 해당 게시판의 board_group 테이블을 모두 삭제한다.
            $board_group = \App\BoardGroup::where('board_seq', $board_seq)->delete();
            //foreach로 순회 공백아니면 저장
            foreach ($group_seqs_arr as $group_seq) {
                if(strlen($group_seq) > 0){
                    $board_group = new \App\BoardGroup;
                    $board_group->board_seq = $board_seq;
                    $board_group->group_seq = $group_seq;
                    $board_group->save();
                }
            }
        }


        //첨부파일 썸네일 보드와 연결 작업.

        // $board_uploadfiles_seqs 를 구분자 | 로 나눠서 배열로 만든다.
        $board_uploadfiles_seqs_arr = explode("|", $board_uploadfiles_seqs);
        //공백이 아닐때만 실행
        if ($board_uploadfiles_seqs != "") {
            //배열을 순회하면서 board_uploadfiles 테이블의 seq를 찾아서 board_seq를 업데이트한다.
            foreach ($board_uploadfiles_seqs_arr as $upfiles_seq) {
                $bdupfile = \App\BoardUploadfile::find($upfiles_seq);
                $bdupfile->board_seq = $board_seq;
                $bdupfile->save();
            }
        }
        // $board_thumbnails_seqs 를 구분자 | 로 나눠서 배열로 만든다.
        $board_thumbnails_seqs_arr = explode("|", $board_thumbnails_seqs);
        //공백이 아닐때만 실행
        if ($board_thumbnails_seqs != "") {
            //배열을 순회하면서 board_thumbnails 테이블의 seq를 찾아서 board_seq를 업데이트한다.
            foreach ($board_thumbnails_seqs_arr as $thumbnails_seq) {
                $bdthumbnail = \App\BoardUploadfile::find($thumbnails_seq);
                $bdthumbnail->board_seq = $board_seq;
                $bdthumbnail->bdupfile_type = 'thumbnail';
                $bdthumbnail->save();
            }
        }


        //테이블 저장이 모두 성공하면 success
        if ($board) {
            return response()->json(['resultCode' => 'success'], 200);
        } else {
            return response()->json(['resultCode' => 'fail'], 400);
        }
    }

    //게시판 첨부파일 업로드
    public function fileupload(Request $request)
    {
        //파일의 유형을 구분한다.
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $fileName . '_' . time() . '.' . $extension;
            $fileNameToStore = str_replace('.php', '', $fileNameToStore);
            $file->storeAs('public/uploads/boardfiles', $fileNameToStore);
            // 저장한 파일 불러올때 : asset('storage/uploads/boardfiles/'.$fileNameToStore)

            //board_uploadfiles 테이블에 저장
            $bdupfile_path = "uploads/boardfiles/" . $fileNameToStore;
            $bdupfile = new \App\BoardUploadfile;
            $bdupfile->bdupfile_path = $bdupfile_path;
            $bdupfile->save();
            $bdupfile_seq = $bdupfile->id;

            return response()->json(['resultCode' => 'success', 'bdupfile_seq' => $bdupfile_seq, 'bdupfile_path' => $bdupfile_path], 200);
        } else {
            return response()->json(['resultCode' => 'fail'], 400);
        }
    }

    //게시판 첨부파일 삭제
    public function filedelete(Request $request)
    {
        $bdupfile_seq = $request->input('bdupfile_seq');
        $bdupfile_path = $request->input('bdupfile_path');
        //seq 와 path로 찾아서 있으면 삭제진행
        $bdupfile = \App\BoardUploadfile::where('id', $bdupfile_seq)->where('bdupfile_path', $bdupfile_path)->first();
        if ($bdupfile) {
            $bdupfile->delete();
            File::delete(storage_path('app/public/' . $bdupfile_path));
        }
        return response()->json(['resultCode' => 'success'], 200);
    }

    //게시판 첨부파일 SELECT
    public function bdupfilelist(Request $request){
        $board_seq = $request->input('board_seq');
        $team_code = $request->input('team_code');

        $bdupfile = \App\BoardUploadfile::where('board_seq', $board_seq)->get();
        //id 를 as bdupfile_seq로 바꿔준다.
        $bdupfile = $bdupfile->map(function ($item, $key) {
            $item['bdupfile_seq'] = $item['id'];
            return $item;
        });


        return $bdupfile;
    }

    //게시판 삭제
    public function boarddelete(Request $request)
    {
        $board_seq = $request->input('board_seq');
        $board = \App\Board::find($board_seq);

        // 관리자 아닌 경우 타입별로 체크해서 본이니 아니면 삭제안되게
        if(session()->get('login_type') == 'teacher'){
            $teach_seq = session()->get('teach_seq');
            if($board->writer_type != 'teacher' || $board->writer_seq != $teach_seq){
                return response()->json(['resultCode' => 'fail'], 400);
            }
        }
        else if(session()->get('login_type') == 'parent'){
            $parent_seq = session()->get('parent_seq');
            if($board->writer_type != 'parent' || $board->writer_seq != $parent_seq){
                return response()->json(['resultCode' => 'fail'], 400);
            }
        }
        else if(session()->get('login_type') == 'student'){
            $student_seq = session()->get('student_seq');
            if($board->writer_type != 'student' || $board->writer_seq != $student_seq){
                return response()->json(['resultCode' => 'fail'], 400);
            }
        }
        //게시판 첨부파일 가져와서 파일 삭제
        //그후 게시판 첨부파일 DB삭제
        $bdupfile = \App\BoardUploadfile::where('board_seq', $board_seq)->get();
        foreach ($bdupfile as $file) {
            File::delete(storage_path('app/public/' . $file->bdupfile_path));
            $file->delete();
        }

        $board->delete();

        //게시판 첨부파일 삭제

        return response()->json(['resultCode' => 'success'], 200);
    }

    // 학부모 찾기
    public function parentSelect(Request $request)
    {
        $parent_name = $request->input('search_str');
        $parents = \App\ParentTb::where('parent_name', 'like', '%' . $parent_name . '%')->get();

        $result = array();
        $result['resultCode'] = 'success';
        $result['parents'] = $parents;
        return response()->json($result, 200);
    }

    // 학생 찾기
    public function studentSelect(Request $request)
    {
        $parent_seq = $request->input('parent_seq');
        //region_name, team_name 가져오기
        $students = \App\Student::where('parent_seq', $parent_seq)
            ->leftJoin('teams', 'students.team_code', '=', 'teams.team_code')
            ->leftJoin('regions', 'teams.region_seq', '=', 'regions.id')
            ->select('students.*', 'regions.region_name', 'teams.team_name', 'regions.id')
            ->get();


        $result = array();
        $result['resultCode'] = 'success';
        $result['students'] = $students;
        return response()->json($result, 200);
    }

    // 게시판 선택 복수 삭제(응원메시지)
    public function boardSelDelete(Request $request){
        $board_seqs = $request->input('board_seqs');
        $board_seqs_arr = explode(",", $board_seqs);

        //공백이 아닐때만 실행
        if ($board_seqs != "") {
            //whereIn으로 삭제 처리.
            $board = \App\Board::whereIn('id', $board_seqs_arr)->delete();
        }
        return response()->json(['resultCode' => 'success'], 200);
    }
}
