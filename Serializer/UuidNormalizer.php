<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer;

use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Throwable;

/**
 * Нормализатор для объектов uuid.
 */
class UuidNormalizer implements DenormalizerInterface, NormalizerInterface
{
    protected UuidFactory $uuidFactory;

    public function __construct()
    {
        $this->uuidFactory = new UuidFactory();
    }

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!($object instanceof UuidInterface)) {
            throw new InvalidArgumentException('The object must implement the "' . UuidInterface::class . '".');
        }

        return $object->toString();
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof UuidInterface;
    }

    /**
     * {@inheritDoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if ('' === $data || null === $data) {
            throw new NotNormalizableValueException(
                'The data is either an empty string or null, you should pass a string that can be parsed to uuid.'
            );
        }

        try {
            $uuid = $this->uuidFactory->fromString($data);
        } catch (Throwable $e) {
            throw new NotNormalizableValueException(
                'Error while converting string to uuid.',
                0,
                $e
            );
        }

        return $uuid;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        $uuidClass = trim(UuidInterface::class, '\\');
        $dataClass = trim($type, '\\');

        return $uuidClass === $dataClass || is_subclass_of($dataClass, $uuidClass);
    }
}
