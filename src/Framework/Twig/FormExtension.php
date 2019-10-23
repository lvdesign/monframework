<?php
namespace Framework\Twig;

class FormExtension extends \Twig\Extension\AbstractExtension
{

    
    // needs_context
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
        // var_dump($context);
        $type = $options['type'] ?? 'text';
        $error = $this->getErrorHtml($context, $key);
        $class = 'form-group';
        $value = $this->convertValue($value);
        $attributes = [
            'class' => trim('form-control ' . ($options['class'] ?? '')),
            'name' => $key,
            'id' => $key
        ];
        if ($error) {
            $class .= ' has-danger';
            $attributes['class'] .= ' form-control-danger';
            //$attributes['class'] .= ' form-control-danger is-';
        }
        // QUEL TYPE ?
        // ($error)?$attributes['class'] .= 'invalid':$attributes['class'] .= 'valid';
        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } elseif (array_key_exists('options', $options)) {
            $input = $this->select($value, $options['options'], $attributes);
        } else {
            $input = $this->input($value, $attributes);
        }
        
        return "<div class=\"" . $class . "\">
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
            return "<small class=\"form-text text-muted\">{$error}</small>";
            // return "<div class=\"invalid-feedback\">{$error}</div>";
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
     * Génère un <select>
     * @param null|string $value
     * @param array $options
     * @param array $attributes
     * @return string
     */
    private function select(?string $value, array $options, array $attributes)
    {
        $htmlOptions = array_reduce(array_keys($options), function (string $html, string $key) use ($options, $value) {
            $params = ['value' => $key, 'selected' => $key === $value];
            return $html . '<option ' . $this->getHtmlFromArray($params) . '>' . $options[$key] . '</option>';
        }, "");
        return "<select " . $this->getHtmlFromArray($attributes) .">$htmlOptions</select>";
    }


    /**
     * Transforme un tableau $clef => $valeur en attribut HTML
     * @param array $attributes
     * @return string
     */
    private function getHtmlFromArray(array $attributes)
    {
        $htmlParts = [];
        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $htmlParts[] = (string)$key;
            } elseif ($value !== false) {
                $htmlParts[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $htmlParts);
    }


    private function convertValue($value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }
}
