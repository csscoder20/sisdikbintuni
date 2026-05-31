<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roles = \Illuminate\Support\Facades\DB::table('roles')->get();
echo "Roles:\n";
foreach($roles as $r) {
    echo $r->id . " - " . $r->name . "\n";
}

$permissions = \Illuminate\Support\Facades\DB::table('permissions')->where('name', 'like', '%delete%')->get();
echo "\nDelete Permissions:\n";
foreach($permissions as $p) {
    echo $p->id . " - " . $p->name . "\n";
}

$role_has_permissions = \Illuminate\Support\Facades\DB::table('role_has_permissions')->get();
$operatorRole = $roles->firstWhere('name', 'operator');
$adminRole = $roles->firstWhere('name', 'admin_dinas');

if ($operatorRole) {
    echo "\nOperator has delete permissions:\n";
    $op_perms = $role_has_permissions->where('role_id', $operatorRole->id)->pluck('permission_id')->toArray();
    foreach($permissions as $p) {
        if (in_array($p->id, $op_perms)) {
            echo "YES - " . $p->name . "\n";
        }
    }
}

if ($adminRole) {
    echo "\nAdmin Dinas has delete permissions:\n";
    $ad_perms = $role_has_permissions->where('role_id', $adminRole->id)->pluck('permission_id')->toArray();
    foreach($permissions as $p) {
        if (in_array($p->id, $ad_perms)) {
            echo "YES - " . $p->name . "\n";
        }
    }
}
