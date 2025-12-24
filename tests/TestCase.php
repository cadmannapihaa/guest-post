use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

protected function seedPermissions(): void
{
    Permission::create(['name' => 'edit own post']);
    Permission::create(['name' => 'edit any post']);
    Permission::create(['name' => 'grant post edit access']);

    Role::create(['name' => 'author'])
        ->givePermissionTo('edit own post');

    Role::create(['name' => 'editor'])
        ->givePermissionTo(['edit own post', 'edit any post']);

    Role::create(['name' => 'admin'])
        ->givePermissionTo(Permission::all());
}
