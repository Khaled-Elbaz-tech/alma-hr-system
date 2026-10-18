<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop task management tables (module removed - moved to a separate system).
     */
    public function up(): void
    {
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('task_subtasks');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('user_task_access');
    }

    public function down(): void
    {
        // Tables are not recreated - module was permanently removed.
    }
};
