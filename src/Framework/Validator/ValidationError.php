<?php
namespace Framework\Validator;

class ValidationError
{

    /**
     * @var array
     */
    private $attributes;
   
    private $key;
    private $rule;
    
    private $messages = [
        'required' => 'Le champs %s est requis',
        'empty' => 'Le champs %s ne peut être vide',
        'slug' => 'Le champs %s n\'est pas un slug valide',
        'minLength' => 'Le champs %s doit contenir plus de %d caractères',
        'maxLength' => 'Le champs %s doit contenir moins de %d caractères',
        'betweenLength' => 'Le champs %s doit contenir entre %d et %d caractères',
        'datetime' => 'Le champs %s doit être une date valide (%s)',
        'exists' => 'Le champs %s n\'existe pas dans la table %s',
        'unique' => 'Le champs %s doit être unique dans la table',
        'filetype' => 'Le champs %s doit être au format valide (%s)',
        'uploaded' => 'Vous devez Uploader un fichier.'
    ];
  
    public function __constructor(string $key, string $rule, array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }

    
    public function __toString(): string
    {
        $params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);
        return (string)call_user_func_array('sprintf', $params);
    }
}
