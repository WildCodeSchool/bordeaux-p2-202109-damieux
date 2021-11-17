<?php

namespace App\Service;

class ProposeValidator extends FormValidator
{
    public function __construct(array $posts)
    {
        parent::__construct($posts);
    }

    public function cleanProposes()
    {
        foreach ($this->posts['propose'] as $key => $propose) {
            $this->posts['propose'][$key] = ucfirst(trim($propose));
            if ($propose === '') {
                $this->errors[] = 'La proposition ' . ($key + 1) . ' ne peut être vide';
            }
            $this->checkLength($propose, 'La proposition', 2, 255);
        }
    }
}
