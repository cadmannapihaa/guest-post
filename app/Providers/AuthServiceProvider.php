protected $policies = [
    \App\Models\Post::class => \App\Policies\PostPolicy::class,
];


Gate::before(function ($user, $ability) {
    if ($user->hasRole('super-admin')) {
        return true;
    }
});
