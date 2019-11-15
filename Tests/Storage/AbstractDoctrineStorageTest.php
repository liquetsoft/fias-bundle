<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\Storage;

use DateTime;
use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Storage\Storage;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\DoctrineTestCase;
use Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests\MockEntities\MockEntity;

/**
 * Тест для проверки записи данных в базу.
 */
abstract class AbstractDoctrineStorageTest extends DoctrineTestCase
{
    /**
     * Создает объект хранилища.
     *
     * @param EntityManager|null $em
     * @param int                $batch
     *
     * @return Storage
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    abstract protected function createStorage(?EntityManager $em = null, int $batch = 1): Storage;

    /**
     * Проверяет вставку записей в БД.
     */
    public function testInsert()
    {
        $zeroEntity = new MockEntity();
        $zeroEntity->setTestId(0);
        $zeroEntity->setTestName('test_0');
        $zeroEntity->setStartdate(new DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($zeroEntity);

        $entity = new MockEntity();
        $entity->setTestId(1);
        $entity->setTestName('test_1');
        $entity->setStartdate(new DateTime('2019-11-11 11:11:11'));

        $secondEntity = new MockEntity();
        $secondEntity->setTestId(2);
        $secondEntity->setTestName('test_2');
        $secondEntity->setStartdate(new DateTime('2019-11-11 11:11:11'));

        $storage = $this->createStorage();
        $storage->start();
        $storage->insert($entity);
        $storage->insert($secondEntity);
        $storage->stop();

        $this->assertDoctrineHasEntity($zeroEntity);
        $this->assertDoctrineHasEntity($entity);
        $this->assertDoctrineHasEntity($secondEntity);
    }

    /**
     * Проверяет удаление записей из БД.
     */
    public function testDelete()
    {
        $entity = new MockEntity();
        $entity->setTestId(3);
        $entity->setTestName('test_3');
        $entity->setStartdate(new DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($entity);

        $secondEntity = new MockEntity();
        $secondEntity->setTestId(4);
        $secondEntity->setTestName('test_4');
        $secondEntity->setStartdate(new DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($secondEntity);

        $storage = $this->createStorage();
        $storage->start();
        $storage->delete($entity);
        $storage->stop();

        $this->assertDoctrineHasNotEntity($entity);
        $this->assertDoctrineHasEntity($secondEntity);
    }

    /**
     * Проверяет обновление записей из БД.
     */
    public function testUpsert()
    {
        $entity = new MockEntity();
        $entity->setTestId(5);
        $entity->setTestName('test_5');
        $entity->setStartdate(new DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($entity);
        $entity->setTestName('test_5_updated');

        $secondEntity = new MockEntity();
        $secondEntity->setTestId(6);
        $secondEntity->setTestName('test_6');
        $secondEntity->setStartdate(new DateTime('2019-11-11 11:11:11'));

        $storage = $this->createStorage();
        $storage->start();
        $storage->upsert($entity);
        $storage->upsert($secondEntity);
        $storage->stop();

        $this->assertDoctrineHasEntity($entity);
        $this->assertDoctrineHasEntity($secondEntity);
    }

    /**
     * Проверяет, что хранилище очищаеи базу данных.
     */
    public function testTruncate()
    {
        $entity = new MockEntity();
        $entity->setTestId(7);
        $entity->setTestName('test_7');
        $entity->setStartdate(new DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($entity);

        $secondEntity = new MockEntity();
        $secondEntity->setTestId(8);
        $secondEntity->setTestName('test_8');
        $secondEntity->setStartdate(new DateTime('2019-11-11 11:11:11'));
        $this->persistEntity($secondEntity);

        $this->createStorage()->truncate(MockEntity::class);

        $this->assertDoctrineHasNotEntity($entity);
        $this->assertDoctrineHasNotEntity($secondEntity);
    }
}
