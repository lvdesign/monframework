<?php
namespace Framework\Twig;

class FormExtension extends \Twig\Extension\AbstractExtension
{

    
    
    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('field', [$this,'field'], [
                'is_safe' => ['html'],
                'needs_context' => true
                ]),
        ];
    }

    /**
     * Génère le code HTML d'un champs
     * @param array $context Contexte de la vue Twig
     * @param string $key Clef du champs
     * @param mixed $value Valeur du champs
     * @param string|null $label Label à utiliser
     * @param array $options
     * @return string
     */
    public function field(array $context, string $key, $value, ?string $label = null, array $options = []) :string
    {
        //var_dump($context);
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $attributes = [
            'class' => trim('form-control ' . ($options['class'] ?? '')),
            'name' => $key,
            'id' => $key,
            
        ];

        if ($error) {
            $class .= ' has-danger';
            $attributes['class'] .= ' form-control-danger';
        }
// quel input
        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }
        
        return "<div class=\"" .$class. "\">
        <label for=\"name\">{$label}</label>
        {$input}
        {$error}
        </div>";
    }


    /**
     * Génère l'HTML en fonction des erreurs du contexte
     * @param $context
     * @param $key
     * @return string
     */
    private function getErrorHtml($context, $key)
    {
        $error = $context['errors'][$key] ?? false;
        if ($error) {
            return "<small class=\"form-text form-muted\">{$error}</small>";
        }
        return "";
    }
    
    /**
     * input
     *
     * @param  string $key
     * @param  null|string $value
     *
     * @return string
     */
    private function input(?string $value, array $attributes): string
    {
        return "<input type=\"text\" " . $this->getHtmlFromArray($attributes) ." value=\"{$value}\">";
    }

    /**
     * textarea
     *
     * @param  string $key
     * @param  null|string $value
     *
     * @return string
     */
    private function textarea(?string $value, array $attributes): string
    {
        return "<textarea " . $this->getHtmlFromArray($attributes) .">{$value}</textarea>";
    }


    /**
     * getHtmlToArray
     *
     * @param  array $attributes
     *
     * @return void
     */
    private function getHtmlFromArray(array $attributes)
    {
        return implode(' ', array_map(function ($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
    }


    private function convertValue($value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }
}
