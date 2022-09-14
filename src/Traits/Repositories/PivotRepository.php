<?php

namespace BulletDigitalSolutions\Gunshot\Traits\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait PivotRepository
{
    /**
     * @return string
     */
    public function getParentClassName()
    {
        return basename(str_replace('\\', '/', $this->parentClass));
    }

    /**
     * @return string
     */
    public function getChildClassName()
    {
        return basename(str_replace('\\', '/', $this->childClass));
    }

    /**
     * @return string
     */
    public function getParentGetter()
    {
        return Str::camel(sprintf('get %s', $this->getParentClassName()));
    }

    /**
     * @return string
     */
    public function getChildGetter()
    {
        return Str::camel(sprintf('get %s', $this->getChildClassName()));
    }

    /**
     * @return string
     */
    public function getParentSetter()
    {
        return Str::camel(sprintf('set %s', $this->getParentClassName()));
    }

    /**
     * @return string
     */
    public function getChildSetter()
    {
        return Str::camel(sprintf('set %s', $this->getChildClassName()));
    }

    /**
     * @return string
     */
    public function getParentName()
    {
        return Str::camel($this->getParentClassName());
    }

    /**
     * @return string
     */
    public function getChildName()
    {
        return Str::camel($this->getChildClassName());
    }

    /**
     * @param $pivot
     * @param $attributes
     * @return mixed
     */
    public function savePivotAttributes($pivot, $attributes = [])
    {
        return $pivot;
    }

    /**
     * @param $parent
     * @param $child
     * @return mixed
     */
    public function attach($attachingTo, $toAttach, $pivotAttributes = [])
    {
        $isAttachingToPrimary = true;

        if ($attachingTo instanceof $this->parentClass) {
            $existing = $this->findOneBy([$this->getParentName() => $attachingTo, $this->getChildName() => $toAttach]);
        } elseif ($attachingTo instanceof $this->childClass) {
            $isAttachingToPrimary = false;
            $existing = $this->findOneBy([$this->getChildName() => $attachingTo, $this->getParentName() => $toAttach]);
        } else {
            throw new \InvalidArgumentException('$attachingTo must be an instance of '.$this->parentClass.' or '.$this->childClass);
        }

        if ($isAttachingToPrimary) {
            $pivot = $this->savePivot($existing, array_merge($pivotAttributes, [
                'parent' => $attachingTo,
                'child' => $toAttach,
            ]));
        } else {
            $pivot = $this->savePivot($existing, array_merge($pivotAttributes, [
                'parent' => $toAttach,
                'child' => $attachingTo,
            ]));
        }

        return $pivot;
    }

    /**
     * @param $parent
     * @param $child
     * @return mixed
     */
    public function attachWithPivot($parent, $child, $pivotAttributes)
    {
        return $this->attach($parent, $child, $pivotAttributes);
    }

    /**
     * @param $pivot
     * @param $pivotAttributes
     * @return mixed|null
     */
    public function savePivot($pivot = null, $pivotAttributes = [])
    {
        if (! $pivot) {
            $pivot = new $this->_entityName;
        }

        if ($parent = Arr::get($pivotAttributes, 'parent')) {
            if (! $parent instanceof $this->parentClass) {
                $parent = app($this->parentClass)->getRepository()->find($parent);
            }

            $pivot->{$this->getParentSetter()}($parent);
        }

        if ($child = Arr::get($pivotAttributes, 'child')) {
            if (! $child instanceof $this->childClass) {
                $child = app($this->childClass)->getRepository()->find($child);
            }

            $pivot->{$this->getChildSetter()}($child);
        }

        $pivot = $this->savePivotAttributes($pivot, $pivotAttributes);

        $this->_em->persist($pivot);
        $this->_em->flush();

        return $pivot;
    }

    /**
     * @param $entity1
     * @param $entity2
     * @return void
     */
    public function detach($entity1, $entity2)
    {
        $existing = $this->findByEntities($entity1, $entity2);

        foreach ($existing as $item) {
            $this->destroy($item);
        }
    }

    /**
     * @param $entity
     * @return void
     */
    public function detachByPivot($entity, array $pivotSearch = [])
    {
        $existing = $this->findByEntity($entity, $pivotSearch);

        foreach ($existing as $item) {
            $this->destroy($item);
        }
    }

    /**
     * @param $entity
     * @return mixed
     *
     * @throws \Exception
     */
    public function detachAll($entity)
    {
        $existing = collect($this->findByEntity($entity));
        $first = $existing?->first();

        foreach ($existing as $item) {
            $this->destroy($item);
        }

        return $first;
    }

    /**
     * @param $parent
     * @param $children
     * @return mixed
     */
    public function sync($attachingTo, $toAttach = [], $pivotAttributes = [])
    {
        $existing = collect($this->findByEntity($attachingTo));

        $existing = $existing->map(function ($item) {
            return $item->{$this->getChildGetter()}();
        });

        if (count($toAttach) === 0) {
            $this->detachAll($attachingTo);
            return $attachingTo;
        }

        $toAttachCollection = collect();

        foreach ($toAttach as $attach) {
            if (! $attach instanceof $this->childClass) {
                $attach = app($this->childClass)->getRepository()->find($attach);
            }
            $toAttachCollection->push($attach);
        }

        foreach ($toAttachCollection as $child) {
            if (! $existing->contains($child)) {
                $this->attach($attachingTo, $child, $pivotAttributes);
            }
        }

        foreach ($existing as $child) {
            if (! $toAttachCollection->contains($child)) {
                $this->detach($attachingTo, $child);
            }
        }

        $this->_em->refresh($attachingTo);
        return $attachingTo;
    }

    /**
     * @param $parent
     * @param $children
     * @param $pivotAttributes
     * @return mixed
     */
    public function syncWithPivot($parent, array $children, array $pivotAttributes)
    {
        $this->sync($parent, $children, $pivotAttributes);
    }

    /**
     * @param $entity1
     * @param $entity2
     * @return mixed
     *
     * @throws \Exception
     */
    public function findByEntities($entity1, $entity2, $pivotSearch = [])
    {
        if ($entity1 instanceof $this->parentClass) {
            return $this->findBy(array_merge([$this->getParentName() => $entity1, $this->getChildName() => $entity2], $pivotSearch));
        } elseif ($entity1 instanceof $this->childClass) {
            return $this->findBy(array_merge([$this->getChildName() => $entity1, $this->getParentName() => $entity2], $pivotSearch));
        } else {
            throw new \Exception('Entity must be an instance of '.$this->parentClass.' or '.$this->childClass);
        }
    }

    /**
     * @param $parent
     * @param $children
     * @return mixed
     */
    public function syncWithoutDetaching($attachingTo, $toAttach = [], $pivotAttributes = [])
    {
        $existing = collect($this->findByEntity($attachingTo));

        foreach ($toAttach as $child) {
            if (! $existing->contains($child)) {
                $this->attach($attachingTo, $child, $pivotAttributes);
            }
        }

        return $attachingTo;
    }

    /**
     * @param $parent
     * @param $child
     * @param $pivotAttributes
     * @return mixed|null
     */
    public function updateExistingPivot($entity1, $entity2, $pivotAttributes = [])
    {
        $existing = $this->findByEntities($entity1, $entity2);

        $pivot = $this->savePivot($existing, $pivotAttributes);

        $this->_em->persist($pivot);
        $this->_em->flush();

        return $pivot;
    }

    /**
     * @param $entity
     * @return mixed
     *
     * @throws \Exception
     */
    public function findByEntity($entity, $pivotSearch = [])
    {
        if ($entity instanceof $this->childClass) {
            return $this->findBy(array_merge([$this->getChildName() => $entity], $pivotSearch));
        }

        if ($entity instanceof $this->parentClass) {
            return $this->findBy(array_merge([$this->getParentName() => $entity], $pivotSearch));
        }

        throw new \Exception('Entity must be an instance of '.$this->childClass.' or '.$this->parentClass);
    }
}
