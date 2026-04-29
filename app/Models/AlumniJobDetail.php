<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniJobDetail extends Model
{
    protected $table = 'alumni_job_details';
    protected $primaryKey = 'alumni_job_id';

    protected $fillable = [
        'alumni_post_id',
        'alumni_company_name',
        'alumni_job_position',
        'alumni_job_location',
        'alumni_job_type',
        'alumni_job_salary_min',
        'alumni_job_salary_max',
        'alumni_job_deadline',
        'alumni_job_requirements',
        'alumni_job_benefits'
    ];

    // Cast untuk memastikan format data yang benar
    protected $casts = [
        'alumni_job_salary_min' => 'decimal:2',
        'alumni_job_salary_max' => 'decimal:2',
        'alumni_job_deadline' => 'date',
    ];

    public function post()
    {
        return $this->belongsTo(AlumniPost::class, 'alumni_post_id', 'alumni_post_id');
    }
} 