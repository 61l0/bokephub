<?php

namespace SqlParser\Tests\Builder;

use SqlParser\Parser;

use SqlParser\Tests\TestCase;

class ExplainStatementTest extends TestCase
{

    public function testBuilderView()
    {
        $query = 'EXPLAIN SELECT * FROM test;';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals(
            ' EXPLAIN SELECT * FROM test',
            $stmt->build()
        );
    }
}
