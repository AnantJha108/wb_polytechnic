<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('college_id');
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');

            // Basic Profile
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('father_first_name');
            $table->string('father_middle_name')->nullable();
            $table->string('father_last_name');
            $table->string('employee_id')->unique();
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('religion');
            $table->string('caste');
            $table->string('aadhaar_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('epic_no')->nullable();
            $table->enum('pwd_status', ['yes', 'no'])->default('no');
            $table->enum('medical_leave_three_months', ['yes', 'no'])->default('no');

            // Contact — Permanent Address
            $table->string('house_no')->nullable();
            $table->string('street_village')->nullable();
            $table->string('post_office')->nullable();
            $table->string('state')->default('WEST BENGAL');
            $table->string('district')->nullable();
            $table->string('sub_division')->nullable();
            $table->string('block_municipality')->nullable();
            $table->string('police_station')->nullable();
            $table->string('pin')->nullable();
            $table->enum('present_same_as_permanent', ['yes', 'no'])->default('yes');
            $table->string('mobile_no')->nullable();
            $table->string('email')->nullable();

            // Employment
            $table->date('date_of_initial_joining');
            $table->date('date_of_retirement')->nullable();
            $table->enum('whether_confirmed', ['yes', 'no'])->default('no');
            $table->enum('disciplinary_proceeding', ['yes', 'no'])->default('no');
            $table->enum('higher_study_qip', ['yes', 'no'])->default('no');
            $table->enum('higher_study_non_qip', ['yes', 'no'])->default('no');
            $table->enum('higher_study_modular', ['yes', 'no'])->default('no');
            $table->enum('higher_study_part_time', ['yes', 'no'])->default('no');
            $table->enum('prayee_for_transfer', ['yes', 'no'])->default('no');

            // Spouse
            $table->enum('spouse_govt_sector', ['yes', 'no'])->default('no');

            // Working arrangement
            $table->enum('working_arrangement', ['yes', 'no'])->default('no');
            $table->string('discipline_trade')->nullable();
            $table->string('employment_status')->nullable();
            $table->string('categories_of_service')->nullable();
            $table->date('wa_from_date')->nullable();
            $table->date('wa_to_date')->nullable();

            // Photo
            $table->string('photo_path')->nullable();

            // Approval workflow
            $table->enum('status', [
                'draft', 'forwarded_to_principal', 'forwarded_to_director',
                'approved', 'rejected', 'reverted'
            ])->default('draft');
            $table->text('reject_reason')->nullable();
            $table->text('revert_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};