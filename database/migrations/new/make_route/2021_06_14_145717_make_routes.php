<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeRoutes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("INSERT INTO `routes` (`id`, `method`, `route`, `controller_name`, `function_name`, `created_at`, `updated_at`) VALUES (NULL, 'get', 'all_routes/{id}/edit', 'RouteController', 'edit', NULL, NULL), (NULL, 'get', 'all_routes/{id}/delete', 'RouteController', 'destroy', NULL, NULL)");
        \DB::statement("INSERT INTO `routes` (`id`, `method`, `route`, `controller_name`, `created_at`, `updated_at`, `function_name`) VALUES (NULL, 'post', 'all_routes/{id}/update', 'RouteController', NULL, NULL, 'update')");
        \DB::statement("ALTER TABLE `providers` CHANGE `image` `image` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        \DB::statement("ALTER TABLE `contents` CHANGE `path` `path` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
