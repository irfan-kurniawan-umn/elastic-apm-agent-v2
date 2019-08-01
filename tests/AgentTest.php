<?php


namespace Elastic\Apm\PhpAgent\Tests;


use Elastic\Apm\PhpAgent\Agent;
use Elastic\Apm\PhpAgent\Config;
use Elastic\Apm\PhpAgent\Model\Context\DbContext;
use Elastic\Apm\PhpAgent\Model\Context\HttpContext;
use Elastic\Apm\PhpAgent\Model\Context\SpanContext;
use PHPUnit\Framework\TestCase;

class AgentTest extends TestCase
{

    public function testTest() {
        $config = new Config('UnitTest', '10.0', 'http://30.108.132.62:8200', 'apmtoken');
        /** @var \Elastic\Apm\PhpAgent\Agent $agent */
        $agent = new Agent($config);
        $agent->startTransaction('test3', 'test');

        $trace = $agent->startTrace('Query select', 'sql');
        $context = new SpanContext([
            'db' => new DbContext([
                'type' => 'query',
                'statement' => 'SELECT * FROM users'
            ]),
            'http' => new HttpContext([
                'url' => 'http://google.com',
                'method' => 'GET',
                'status_code' => 200
            ])
        ]);
        $agent->stopTrace($trace->getId(), $context);

        $agent->stopTransaction();
    }
}