<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Test\Helper;

use Acme\PhpExtension\Helper\JsonDecodingException;
use Acme\PhpExtension\Helper\JsonHelper;
use Acme\PhpExtension\Test\AbstractTestCase;

/**
 * @group unit
 * @small
 *
 * @internal
 */
final class JsonHelperTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_encode_an_array_to_json(): void
    {
        $data = ['a' => 'b', 'c' => 'd'];

        $json = JsonHelper::encode($data);

        self::assertSame(json_encode($data), $json);
    }

    /**
     * @test
     */
    public function can_decode_json_to_an_array(): void
    {
        $originalData = ['a' => 'b', 'c' => 'd'];
        $json = json_encode($originalData);

        $decodedData = JsonHelper::decode($json);

        self::assertSame($originalData, $decodedData);
    }

    /**
     * @test
     */
    public function will_throw_exception_when_given_invalid_json(): void
    {
        $this->expectException(JsonDecodingException::class);
        JsonHelper::decode('this is not json');
    }

    /**
     * @test
     * @dataProvider provideJson
     */
    public function it_detects_json(string $value, bool $expectedResult): void
    {
        self::assertEquals($expectedResult, JsonHelper::isJson($value));
    }

    public function provideJson()
    {
        return [
            ['123', false],
            ['this is not json', false],
            ['{this is not json}', false],
            ['[this is not json]', false],
            ['{"a": 1}', true],
        ];
    }
}
