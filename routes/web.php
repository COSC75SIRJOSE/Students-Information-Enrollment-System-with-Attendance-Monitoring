<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AcademicTermController;

Route::redirect('/', '/students');

Route::resource('academic-years', AcademicYearController::class)
    ->except(['show']);

Route::resource('academic-terms', AcademicTermController::class)
    ->except(['show']);

// Student management routes
Route::resource('students', StudentController::class)
    ->except(['show']);

// Attendance monitoring routes
Route::get('/attendance', [AttendanceController::class, 'index'])
    ->name('attendance.index');

Route::post('/attendance', [AttendanceController::class, 'store'])
    ->name('attendance.store');

// Attendance reports
Route::get('/attendance/reports', [AttendanceController::class, 'reports'])
    ->name('attendance.reports');

Route::get(
    '/attendance/reports/export',
    [AttendanceController::class, 'exportCsv']
)->name('attendance.reports.export');
