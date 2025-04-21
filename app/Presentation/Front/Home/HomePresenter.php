<?php

declare(strict_types=1);

namespace App\Presentation\Front\Home;

use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function actionDefault(): void
    {
        $validator = (new \League\OpenAPIValidation\PSR7\ValidatorBuilder())
            ->fromYamlFile(__DIR__ . '/../../Api/schema.yaml')
            ->getServerRequestValidator();

        // $this->getHttpRequest();
        //
        // $validator->validate();

        bdump($validator->getSchema());
    }
}
