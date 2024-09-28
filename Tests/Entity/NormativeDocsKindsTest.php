<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\NormativeDocsKinds;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения по видам нормативных документов'.
 *
 * @internal
 */
class NormativeDocsKindsTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity(): object
    {
        return new NormativeDocsKinds();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'name' => 'test string',
        ];
    }
}
