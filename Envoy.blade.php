@setup
    $productionPath = '/home/marucn1q/subdomains/tado-ciq-bridge';
    $productionServer = 'marucn1q@mghollander.nl:4000';
@endsetup

@servers(['local' => '127.0.0.1', 'production' => $productionServer])

@story('deploy')
    sync-files
    deploy-actions
@endstory

@task('sync-files', ['on' => 'local'])
    rsync . {{ $productionServer }}:{{ $productionPath }} -avlz --delete-after --exclude-from='.rsync-exclude'
@endtask

@task('deploy-actions', ['on' => 'production'])
    cd {{ $production_path }}

    php artisan down --render="errors::maintenance"

    {{-- Laravel deployment optimizations (https://laravel.com/docs/8.x/deployment#optimization) --}}
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    php artisan migrate --force

    php artisan up
@endtask
