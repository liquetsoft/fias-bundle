<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Entity;

use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Entity\ApartmentTypes;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\EntityCase;

/**
 * Тест для сущности 'Сведения по типам помещений'.
 *
 * @internal
 */
class ApartmentTypesTest extends EntityCase
{
    /**
     * {@inheritDoc}
     */
    protected function createEntity(): object
    {
        return new ApartmentTypes();
    }

    /**
     * {@inheritDoc}
     */
    protected function accessorsProvider(): array
    {
        return [
            'id' => 123321,
            'name' => 'test string',
            'shortname' => 'test string',
            'desc' => 'test string',
            'updatedate' => new \DateTimeImmutable(),
            'startdate' => new \DateTimeImmutable(),
            'enddate' => new \DateTimeImmutable(),
            'isactive' => 'test string',
        ];
    }
}
