<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Serializer;

use Liquetsoft\Fias\Component\Serializer\FiasNameConverter;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Преднастроенный объект сериализатора для ФИАС.
 */
final class FiasSerializer implements SerializerInterface
{
    private readonly SerializerInterface $nestedSerializer;

    /**
     * @param array<DenormalizerInterface|NormalizerInterface>|null $normalizers
     * @param array<DecoderInterface|EncoderInterface>|null         $encoders
     */
    public function __construct(?array $normalizers = null, ?array $encoders = null)
    {
        if ($normalizers === null) {
            $normalizers = [
                new CompiledEntitesDenormalizer(),
                new FiasUuidNormalizer(),
                new DateTimeNormalizer(),
                new ObjectNormalizer(
                    nameConverter: new FiasNameConverter(),
                    propertyTypeExtractor: new ReflectionExtractor(),
                    defaultContext: [
                        ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                    ]
                ),
            ];
        }

        $this->nestedSerializer = new \Liquetsoft\Fias\Component\Serializer\FiasSerializer($normalizers, $encoders);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->nestedSerializer->serialize($data, $format, $context);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedReturnStatement
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        return $this->nestedSerializer->deserialize($data, $type, $format, $context);
    }
}
