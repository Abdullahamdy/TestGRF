<?php

namespace App\Traits;

use App\Models\News;
use App\Models\Tag;
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
            if ($model instanceof Tag) {
                $fields['slug'] = $fields['name'];
            }
            if ($model instanceof News) {
                $fields['slug'] = $fields['title'];
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
           unset($data['main_image'], $data['meta_image'], $data['file'],$data['image']);

            $model->update($data);

            if ($afterSave && is_callable($afterSave)) {
                $afterSave($model, $data);
            }

            return $model;
        } catch (\Exception $e) {
            throw $e; // Re-throw to let controller handle the error
        }
    }


    protected function handleCommonOperations($model, array $data, array $operations)
    {
        foreach ($operations as $operation => $config) {
            switch ($operation) {
                case 'image':
                case 'main_image':
                case 'meta_image':
                case 'featured_image':
                    $fieldName = $config['field'] ?? $operation;
                    if (isset($data[$fieldName])) {
                        if (method_exists($this, 'handleImage')) {
                            $model->$fieldName = $this->handleImage($data[$fieldName]);
                        }
                    }
                    break;

                case 'file':
                    $fieldName = $config['field'] ?? 'file';
                    if (isset($data[$fieldName]) && is_file($data[$fieldName]) && method_exists($this, 'handleImage')) {
                        $model->$fieldName = $this->handleImage($data[$fieldName]);
                    }
                    break;

                case 'role':
                    if (isset($data['role']) && !isset($data['writers']) && method_exists($model, 'assignRole')) {
                        $model->assignRole($data['role']);
                    }
                    break;

                case 'tags':
                    if (isset($data['tags']) && method_exists($this, 'handleTags')) {
                        $this->handleTags($model, $data['tags']);
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
    /**
     * Helper method to handle image updates with fallback
     *
     * @param object $model
     * @param array $data
     * @param string $field
     * @param bool $requireFile
     * @return void
     */
    protected function handleImageUpdate($model, array $data, string $field, bool $requireFile = false)
    {
        if (isset($data[$field])) {
            if ($requireFile && is_file($data[$field]) && method_exists($this, 'handleImage')) {
                $model->$field = $this->handleImage($data[$field]);
            } elseif (!$requireFile && method_exists($this, 'handleImage')) {
                $model->$field = $this->handleImage($data[$field]);
            }
        }
        // If not set, keep existing value (no change needed)
    }
}
