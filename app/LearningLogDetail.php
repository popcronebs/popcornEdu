<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class LearningLogDetail extends Model
{
    //create table learning_log_detail (
    // id int auto_increment primary key,
    // student_seq int,
    // log_seq int,
    // log_type varchar(50),
    // log_content varchar(2000),
    // created_at datetime,
    // updated_at datetime,
    // foreign key (student_seq) references students(id),
    // foreign key (log_seq) references learning_logs(id),
    // index (log_type)
}
