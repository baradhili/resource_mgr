<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\HttpFoundation\Response;


class ReportModulesController extends Controller
{
    public function index()
    {
        Log::info("in reportmodulescontroller") ;
        try {

            $modules = collect(Module::allEnabled())
            ->sortBy(fn($module) => $module->get('priority', 0))
            ->values(); // Reset keys after sort
            $reportModules = [];

            foreach ($modules as $module) {
                // Skip non-Report modules
                if ($module->get('type') !== 'Report') continue;

                // Get route alias (prefer modules.json alias, fallback to lower name)
                $alias = $module->get('alias') ?? $module->getLowerName();
                $routeName = "{$alias}.index";

                // Validate route exists
                if (!Route::has($routeName)) {
                    Log::warning("Route [{$routeName}] missing for module [{$module->getName()}]. Skipping.");
                    continue;
                }

                //Get data directly from module path
                $configPath = $module->getPath() . '/Config/config.php';
                $moduleConfig = file_exists($configPath) ? require $configPath : [];

                // Get display name from module config (MUST exist)
                $configKey = "{$module->getLowerName()}.sidebar_name";
                $displayName = config($configKey);

                if (empty($displayName)) {
                    Log::warning("Module [{$module->getName()}] missing 'sidebar_name' in config. Skipping.");
                    continue;
                }

                $reportModules[] = [
                    'name' => $displayName,
                    'url' => route($routeName),
                ];

                Log::info("Added report module: {$displayName}");
            }

            // Generate clean HTML output
            $items = collect($reportModules)->map(function ($item) {
                $url = htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8');
                $name = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
                return "    <li class=\"sidebar-item\"><a class=\"sidebar-link\" href=\"{$url}\">{$name}</a></li>";
            })->implode(PHP_EOL);

            $html = <<<HTML
<ul id="reports" class="sidebar-dropdown list-unstyled collapse">
{$items}
</ul>
HTML;

            return response($html, Response::HTTP_OK)
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('X-Report-Count', count($reportModules));
        } catch (\Throwable $e) {
            Log::error('Report modules generation failed: ' . $e->getMessage());
            return response('<ul id="reports" class="sidebar-dropdown list-unstyled collapse"></ul>', Response::HTTP_OK)
                ->header('Content-Type', 'text/html; charset=utf-8');
        }
    }
}