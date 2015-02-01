# php-meat

WIP Queue abstraction library

# Usefull links
https://registry.hub.docker.com/u/pataquets/gearmand/
https://registry.hub.docker.com/u/kdihalas/beanstalkd/
https://registry.hub.docker.com/u/platformer/resque-1-x-stable/

# Thought examples

```php
<?php

require 'vendor/autoload.php';

$queue = new Queue(new Backend\Iron());

$queue->prepare(new FetchAchievementsJob())->then(new JobCollectionJob([
    FetchDescriptionJob::class,
    FetchIconJob::class,
    FetchTitleJob::class,
]))->enqueue();

$queue->prepare(new FetchPackagesJob())->
    conditional(new MatchStringCondition('package.vendor', 'WyriHaximus'))->
        then(new JobCollectionJob([
             StorePackageJob::class,
             AnaliseDependenciesJob::class,
             UpdateProjectJob::class,
         ]))->
        else(StorePackageJob::class)->
    end()->
    enqueue();
```
