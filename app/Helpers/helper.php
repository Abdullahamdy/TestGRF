<?php


use Illuminate\Support\Facades\Log;


if (!function_exists('serverErrorMessage')) {
    function serverErrorMessage(): string
    {
        return 'Ops, something happened, please try again later';
    }
}

if (!function_exists('seeder_translations')) {
    function seeder_translations($data, array $fields)
    {
        $translations = ['ar' => [], 'en' => []];

        foreach ($fields as $field) {
            $translations['ar'][$field] = $data["{$field}_ar"] ?? null;
            $translations['en'][$field] = $data["{$field}_en"] ?? null;
        }

        return $translations;
    }


    if (!function_exists('prepare_translations')) {
        function prepare_translations($data, array $fields)
        {
            $translations = ['ar' => [], 'en' => []];

            foreach ($fields as $field) {
                $translations['ar'][$field] = $data["{$field}"] ?? null;
                $translations['en'][$field] = $data["{$field}"] ?? null;
            }


            return $translations;
        }
    }

    if (!function_exists('errorLog')) {
        /**
         * @throws Exception
         */
        function errorLog($file_path, $line_number, $error_message, $exception): void
        {
            $errorMsg = 'error in file: ' . $file_path . ' in line: ' . $line_number . ' and error message is: ' . $error_message;
            Log::channel('db')->error($errorMsg);
            if (env('APP_DEBUG') === true) {
                throw new \Exception($exception);
            }
        }
    }
    if (!function_exists('adminDbTablesPermissions')) {
        /**
         * @return mixed
         */

        function adminDbTablesPermissions()
        {
            $adminPermissions = [
                'analysis',
                'user',
                'role',
                'news_tag',
                'article_tag',
                'news_category',
                'article_category',
                'news',
                'settings',
                'market_value',
                'report',
                'albums',
                'media',
                'articles',
                'branch',
                'contact_us',
                'ads',
                'ads_space',
                'image_section',
                'menu',
                'menu_item',
                'storage',
                'maintenance',
                'page',
                'news_letter',
                'socail_media',
                'widget',
                'rss',
                'live_stream',
                'poll',
                'writer',
                'notification',
                'setting',

                //capital
                'research_evaluation',
                'financial_analyst',
                'sector',
                'owner',
                'arrow_owners',
                'owner_company',
                'members',
                'profit_casts',
                'company',
                'research_company',
                'news_capital',
                'project',
                'user_capital'
            ];
            return $adminPermissions;
        }
    }
    if (!function_exists('availableLanguages')) {
        function availableLanguages()
        {
            return ['ar', 'en'];
        }
    }
    if (!function_exists('generateCode')) {
        function generateCode()
        {
            return str_pad(random_int(1, 9999), 5, '0', STR_PAD_LEFT);
        }
    }
    if (!function_exists('gethost')) {

        function gethost()
        {
            return env('IsProduct') ?  request()->getSchemeAndHttpHost() . '/public' : request()->getSchemeAndHttpHost();
        }
    }

    if (!function_exists('useradmin')) {
        function useradmin()
        {
            return auth()->user()->hasRole('admin');
        }
    }

    if (!function_exists('setEnvValue')) {
        function setEnvValue($key, $value)
        {
            $envPath = base_path('.env');

            if (file_exists($envPath)) {
                $content = file_get_contents($envPath);

                $escapedValue = '"' . addslashes(trim($value)) . '"';

                if (strpos($content, "$key=") !== false) {
                    $content = preg_replace("/^$key=.*$/m", "$key=$escapedValue", $content);
                } else {
                    $content .= "\n$key=$escapedValue";
                }

                file_put_contents($envPath, $content);
            }
        }
    }
}
