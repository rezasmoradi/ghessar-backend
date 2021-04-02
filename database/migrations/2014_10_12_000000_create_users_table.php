<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('type', User::USER_TYPES)->default(User::USER_TYPE_USER);
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('username', 255)->unique();
            $table->string('password', 200)->nullable();
            $table->string('mobile', 13)->nullable()->unique();
            $table->string('email', 255)->nullable()->unique();
            $table->string('verification_code', 6)->nullable();
            $table->timestamp('code_expired_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('bio', 100)->nullable();
            $table->string('country', 50);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
