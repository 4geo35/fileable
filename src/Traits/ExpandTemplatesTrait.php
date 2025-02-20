<?php

namespace GIS\Fileable\Traits;

trait ExpandTemplatesTrait
{
    protected function expandTemplates(array $config): void
    {
        if (empty($config['templates'])) return;
        $fa = app()->config["fileable"];
        $templates = $fa["templates"];
        foreach ($config["templates"] as $key => $template) {
            $templates[$key] = $template;
        }
        app()->config["fileable.templates"] = $templates;
    }
}
