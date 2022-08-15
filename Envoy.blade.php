@servers(['staging' => 'root@128.199.174.46','production'=>'root@159.89.208.15'])

@task('deploy-staging', ['on' => 'staging'])
cd /opt/lampstack-7.1.12-0/apps/memoria/htdocs

@if ($branch)
    git pull origin {{ $branch }}
    cd /opt/lampstack-7.1.12-0/apps/memoria/htdocs/ && /opt/lampstack-7.1.12-0/php/bin/composer.phar install
@endif
/opt/lampstack-7.1.12-0/php/bin/php artisan migrate
@endtask

{{--@task('deploy-production', ['on' => 'production'])--}}
{{--cd /opt/lampstack-7.1.12-0/apps/memoria/htdocs--}}

{{--@if ($branch)--}}
{{--    git pull origin {{ $branch }}--}}
{{--    cd /opt/lampstack-7.1.12-0/apps/memoria/htdocs/ && /opt/lampstack-7.1.12-0/php/bin/php /root/composer.phar update--}}
{{--@endif--}}
{{--@endtask--}}