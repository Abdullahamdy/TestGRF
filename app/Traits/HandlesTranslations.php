<?php

namespace App\Traits;

use App\Models\User;

trait HandlesTranslations
{
    /**
     * Handle translations for the model
     *
     * @param array $data
     * @param object $model
     * @return object
     */
    protected function handleTranslations(array &$data, $model)
    {
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        foreach ($translations as $locale => $fields) {
            if ($model instanceof User) {
                $fields['slug'] = $fields['first_name'] . '-' . $fields['last_name'];
            }
            $model->translateOrNew($locale)->fill($fields);
        }

        return $model;
    }

    /**
     * Store model with translations
     *
     * @param array $data
     * @param string $modelClass
     * @param callable|null $beforeSave
     * @param callable|null $afterSave
     * @return mixed
     */
    protected function storeWithTranslations(array $data, string $modelClass, callable $beforeSave = null, callable $afterSave = null)
    {
        try {
            $model = $modelClass::create($data);

            $this->handleTranslations($data, $model);

            if ($beforeSave && is_callable($beforeSave)) {
                $beforeSave($model, $data);
            }

            $model->save();

            if ($afterSave && is_callable($afterSave)) {
                $afterSave($model, $data);
            }

            return $model;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Update model with translations
     *
     * @param array $data
     * @param object $model
     * @param callable|null $beforeSave
     * @param callable|null $afterSave
     * @return mixed
     */
    protected function updateWithTranslations(array $data, $model, callable $beforeSave = null, callable $afterSave = null)
    {
        try {
            $this->handleTranslations($data, $model);

            if ($beforeSave && is_callable($beforeSave)) {
                $beforeSave($model, $data);
            }


            $model->update($data);

            if ($afterSave && is_callable($afterSave)) {
                $afterSave($model, $data);
            }

            return $model;
        } catch (\Exception $e) {
            throw $e; // Re-throw to let controller handle the error
        }
    }

    /**
     * Helper method to handle common operations in callbacks
     *
     * @param object $model
     * @param array $data
     * @param array $operations
     * @return void
     */
    protected function handleCommonOperations($model, array $data, array $operations)
    {
        foreach ($operations as $operation => $config) {
            switch ($operation) {
                case 'image':
                    if (isset($data[$config['field'] ?? 'image']) && method_exists($this, 'handleImage')) {
                        $model->{$config['field'] ?? 'image'} = $this->handleImage($data[$config['field'] ?? 'image']);
                    }
                    break;

                case 'role':
                    if (isset($data['role']) && !isset($data['writers']) && method_exists($model, 'assignRole')) {
                        $model->assignRole($data['role']);
                    }
                    break;

                case 'tags':
                    if (isset($data['tags']) && method_exists($model, 'syncTags')) {
                        $model->syncTags($data['tags']);
                    }
                    break;

                case 'categories':
                    if (isset($data['categories']) && method_exists($model, 'categories')) {
                        $model->categories()->sync($data['categories']);
                    }
                    break;
            }
        }
    }
}
