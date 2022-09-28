<?php

namespace BulletDigitalSolutions\Gunshot\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class My
{
    /**
     * @param $request
     * @param  Closure  $next
     * @param $entityName
     * @param $field
     * @param $param
     * @return mixed|never
     */
    public function handle($request, Closure $next, $entityName, $field = 'id', $routeParam = null, $requestParam = null)
    {
        if (! $routeParam) {
            $routeParam = Str::snake($entityName);
        }

        if (! $requestParam) {
            $requestParam = Str::camel($entityName);
        }

        $entity = $this->getRepository($entityName)->findOneBy([$field => $request->route()->parameter($routeParam)]);

        $user = $request->user();

        if (null === $entity || ! $entity->isOwnedBy($user)) {
            return abort(404);
        }

        $request->attributes->add([
            $requestParam => $entity,
        ]);

        return $next($request);
    }

    /**
     * @param $entity
     * @return string
     */
    public function getEntityNamespace($entity)
    {
        return 'App\\Entities\\'.$entity;
    }

    /**
     * @param $entity
     * @return Application|mixed
     */
    public function getEntity($entity)
    {
        return app($this->getEntityNamespace($entity));
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getRepository($entity)
    {
        return $this->getEntity($entity)->getRepository();
    }
}
