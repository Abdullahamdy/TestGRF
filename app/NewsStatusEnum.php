<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum NewsStatusEnum: int
{
    case PUBLISHED = 1;
    case SCHEDULED = 2;
    case DRAFT = 3;
    case DISABLED = 0;

    /**
     * Get all cases as a collection for plucking
     *
     * @param string|null $locale
     * @return Collection
     */
    public static function toCollection(?string $locale = null): Collection
    {
        $locale = $locale ?? app()->getLocale();

        $items = collect(self::cases())->map(function ($status) use ($locale) {
            return [
                'id' => $status->value,
                'name' => self::getLocalizedName($status->value, $locale),
            ];
        });

        return $items;
    }

    /**
     * Pluck for dropdown (similar to Laravel's pluck)
     *
     * @param string|null $locale
     * @return array
     */
    public static function pluck(?string $locale = null): array
    {
        return self::toCollection($locale)
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Get localized name of the status
     *
     * @param int $id
     * @param string|null $locale
     * @return string
     */
    public static function getLocalizedName(int $id, ?string $locale): string
    {
        $translations = [
            'en' => [
                self::PUBLISHED->value => 'Published',
                self::SCHEDULED->value => 'Scheduled',
                self::DRAFT->value => 'Draft',
                self::DISABLED->value => 'Disabled',
            ],
            'ar' => [
                self::PUBLISHED->value => 'منشور',
                self::SCHEDULED->value => 'مجدول',
                self::DRAFT->value => 'مسودة',
                self::DISABLED->value => 'معطل',
            ],
        ];

        return $translations[$locale][$id] ?? $translations['en'][$id];
    }
}
