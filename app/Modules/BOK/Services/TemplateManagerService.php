<?php

namespace App\Modules\BOK\Services;

use App\Modules\BOK\Models\Template;

class TemplateManagerService
{
    /** Upload/register a new template version (stub). */
    public function register(string $name, string $type, string $path, int $version = 1, ?string $disk = null): Template
    {
        $tpl = new Template([
            'name' => $name,
            'type' => $type,
            'version' => $version,
            'disk' => $disk,
            'path' => $path,
            'is_active' => false,
        ]);
        $tpl->save();
        return $tpl;
    }

    /** Set a template as active and deactivate others of same type (stub). */
    public function activate(Template $template): void
    {
        Template::where('type', $template->type)->update(['is_active' => false]);
        $template->is_active = true;
        $template->save();
    }

    /** Basic variable validation placeholder. */
    public function validateVariables(Template $template, array $variables): bool
    {
        // TODO: parse docx/xlsx to verify placeholders
        return true;
    }
}

