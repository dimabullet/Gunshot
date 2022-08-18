<?php

namespace BulletDigitalSolutions\Gunshot\Traits\Repositories;

use Illuminate\Support\Arr;

trait PivotRepository
{
    /**
     * @param $parent
     * @param $child
     * @return mixed
     */
    public function attach($attachingTo, $toAttach, $pivotAttributes = [])
    {
        $isAttachingToPrimary = true;

        if ($attachingTo instanceof $this->parentClass) {
            $existing = $this->findOneBy([$this->parentName => $attachingTo, $this->childName => $toAttach]);
        } elseif ($attachingTo instanceof $this->childClass) {
            $isAttachingToPrimary = false;
            $existing = $this->findOneBy([$this->childName => $attachingTo, $this->parentName => $toAttach]);
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
            $pivot->{$this->parentSetter}($parent);
        }

        if ($child = Arr::get($pivotAttributes, 'child')) {
            $pivot->{$this->childSetter}($child);
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
        $existing = $this->findByEntity($entity);
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
        $existing = $this->findByEntities($attachingTo, $toAttach);

        foreach ($toAttach as $child) {
            if (! $existing->contains($child)) {
                $this->attach($attachingTo, $child, $pivotAttributes);
            }
        }

        foreach ($existing as $child) {
            if (! $toAttach->contains($child)) {
                $this->detach($attachingTo, $child);
            }
        }

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
        if ($entity1 instanceof $this->parentClass && $entity2 instanceof $this->childClass) {
            return $this->findBy(array_merge([$this->parentName => $entity1, $this->childName => $entity2], $pivotSearch));
        } elseif ($entity1 instanceof $this->childClass && $entity2 instanceof $this->parentClass) {
            return $this->findBy(array_merge([$this->childName => $entity1, $this->parentName => $entity2], $pivotSearch));
        } else {
//            dd($entity1, $entity2, $pivotSearch);
            throw new \Exception('Entity must be an instance of '.$this->childClass.' or '.$this->parentClass);
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
            return $this->findBy(array_merge([$this->childName => $entity], $pivotSearch));
        }

        if ($entity instanceof $this->parentClass) {
            return $this->findBy(array_merge([$this->parentName => $entity], $pivotSearch));
        }

        throw new \Exception('Entity must be an instance of '.$this->childClass.' or '.$this->parentClass);
    }
}
