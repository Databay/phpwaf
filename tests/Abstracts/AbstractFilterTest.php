<?php

namespace App\Tests\Abstracts;

use App\Abstracts\AbstractFilter;
use App\Entity\Request;
use App\Tests\BaseTestCase;

class AbstractFilterTest extends BaseTestCase
{
    private AbstractFilter $abstractFilter;

    public function setUp(): void
    {
        parent::setUp();

        $this->abstractFilter = new class extends AbstractFilter {
            public function apply(Request $request): bool
            {
                return true;
            }

            public function isFilterActive(): bool
            {
                return true;
            }

            public function getBlockingType(): string
            {
                return self::BLOCKING_TYPE_WARNING;
            }
        };
    }

    public function testConstruct(): void
    {
        $this->assertInstanceOf(AbstractFilter::class, $this->abstractFilter);
        $this->assertTrue($this->abstractFilter->apply($this->createMock(Request::class)));
        $this->assertTrue($this->abstractFilter->isFilterActive());
        $this->assertEquals(AbstractFilter::BLOCKING_TYPE_WARNING, $this->abstractFilter->getBlockingType());
    }
}