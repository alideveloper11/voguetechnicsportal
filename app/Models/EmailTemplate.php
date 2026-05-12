<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as Audit;

class EmailTemplate extends Model implements Auditable
{
    use Audit;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'is_active'
    ];

    public function renderSubject(array $variables = []): string
    {
        return $this->renderTemplate($this->subject, $variables);
    }

    public function renderBody(array $variables = []): string
    {
        return $this->renderTemplate($this->body, $variables);
    }

    protected function renderTemplate(string $content, array $variables = []): string
    {
        $content = $this->normalizePlaceholderMarkup($content);

        return preg_replace_callback('/\{{1,2}\s*([a-zA-Z0-9_]+)\s*\}{1,2}/', function ($matches) use ($variables) {
            $key = $matches[1];

            return array_key_exists($key, $variables)
                ? (string) $variables[$key]
                : $matches[0];
        }, $content);
    }

    protected function normalizePlaceholderMarkup(string $content): string
    {
        return preg_replace_callback('/\{{1,2}.*?\}{1,2}/s', function ($matches) {
            return html_entity_decode(strip_tags($matches[0]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $content);
    }
}
