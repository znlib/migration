<?php

namespace ZnLib\Migration\Domain\Scenarios\Input;

use ZnLib\Migration\Domain\Enums\GenerateActionEnum;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class ActionInputScenario extends BaseInputScenario
{

    public function choices()
    {
        return GenerateActionEnum::values();
    }

    protected function paramName()
    {
        return 'type';
    }

    protected function question(): Question
    {
        $question = new ChoiceQuestion(
            'Please select type',
            $this->choices()
        );
        return $question;
    }

}
