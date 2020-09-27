<?php

namespace ZnLib\Migration\Domain\Services;

use ZnCore\Domain\Base\BaseService;
use ZnLib\Migration\Domain\Interfaces\Repositories\GenerateRepositoryInterface;
use ZnLib\Migration\Domain\Interfaces\Services\GenerateServiceInterface;
use ZnLib\Migration\Domain\Scenarios\Render\CreateTableRender;
use ZnCore\Base\Helpers\ClassHelper;

class GenerateService extends BaseService implements GenerateServiceInterface
{

    public function __construct(GenerateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function generate(object $dto)
    {


        //if($dto->type == GenerateActionEnum::CREATE_TABLE) {
        $class = CreateTableRender::class;
        //}

        //dd($dto);
        $dto->attributes = [];

        $dto->attributes = [];

        $scenarioInstance = new $class;
        $scenarioParams = [
            'dto' => $dto,
        ];
        ClassHelper::configure($scenarioInstance, $scenarioParams);
        //$scenarioInstance->init();
        $scenarioInstance->run();

        //dd($dto);
    }

}

