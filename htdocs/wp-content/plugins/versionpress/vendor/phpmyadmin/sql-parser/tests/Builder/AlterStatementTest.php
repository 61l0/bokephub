<?php

namespace SqlParser\Tests\Builder;

use SqlParser\Parser;

use SqlParser\Tests\TestCase;

class AlterStatementTest extends TestCase
{

    public function testBuilder()
    {
        $query = 'ALTER TABLE `actor` ' .
            'ADD PRIMARY KEY (`actor_id`), ' .
            'ADD KEY `idx_actor_last_name` (`last_name`)';

        $parser = new Parser($query);
        $stmt = $parser->statements[0];

        $this->assertEquals($query, $stmt->build());
    }
}
