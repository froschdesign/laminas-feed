<?php

/**
 * @see       https://github.com/laminas/laminas-feed for the canonical source repository
 * @copyright https://github.com/laminas/laminas-feed/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-feed/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Feed\Writer;

use DateTime;
use DateTimeImmutable;
use Laminas\Feed\Writer;
use Laminas\Feed\Writer\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group Laminas_Feed
 * @group Laminas_Feed_Writer
 */
class DeletedTest extends TestCase
{
    public function testSetsReference()
    {
        $entry = new Writer\Deleted();
        $entry->setReference('http://www.example.com/id');
        $this->assertEquals('http://www.example.com/id', $entry->getReference());
    }

    public function testSetReferenceThrowsExceptionOnInvalidParameter()
    {
        $entry = new Writer\Deleted();

        $this->expectException(Writer\Exception\ExceptionInterface::class);
        $entry->setReference('');
    }

    public function testGetReferenceReturnsNullIfNotSet()
    {
        $entry = new Writer\Deleted();
        $this->assertNull($entry->getReference());
    }

    public function testSetWhenDefaultsToCurrentTime()
    {
        $entry = new Writer\Deleted();
        $entry->setWhen();
        $dateNow = new DateTime();
        $this->assertLessThanOrEqual($dateNow, $entry->getWhen());
    }

    public function testSetWhenUsesGivenUnixTimestamp()
    {
        $entry = new Writer\Deleted();
        $entry->setWhen(1234567890);
        $myDate = new DateTime('@' . 1234567890);
        $this->assertEquals($myDate, $entry->getWhen());
    }

    /**
     * @group Laminas-12070
     */
    public function testSetWhenUsesGivenUnixTimestampWhenItIsLessThanTenDigits()
    {
        $entry = new Writer\Deleted();
        $entry->setWhen(123456789);
        $myDate = new DateTime('@' . 123456789);
        $this->assertEquals($myDate, $entry->getWhen());
    }

    /**
     * @group Laminas-11610
     */
    public function testSetWhenUsesGivenUnixTimestampWhenItIsAVerySmallInteger()
    {
        $entry = new Writer\Deleted();
        $entry->setWhen(123);
        $myDate = new DateTime('@' . 123);
        $this->assertEquals($myDate, $entry->getWhen());
    }

    public function testSetWhenUsesDateTimeObject()
    {
        $myDate = new DateTime('@' . 1234567890);
        $entry  = new Writer\Deleted();
        $entry->setWhen($myDate);
        $this->assertEquals($myDate, $entry->getWhen());
    }

    public function testSetWhenUsesDateTimeImmutableObject()
    {
        $myDate = new DateTimeImmutable('@' . 1234567890);
        $entry  = new Writer\Deleted();
        $entry->setWhen($myDate);
        $this->assertEquals($myDate, $entry->getWhen());
    }

    public function testSetWhenThrowsExceptionOnInvalidParameter()
    {
        $entry = new Writer\Deleted();

        $this->expectException(Writer\Exception\ExceptionInterface::class);
        $entry->setWhen('abc');
    }

    public function testGetWhenReturnsNullIfDateNotSet()
    {
        $entry = new Writer\Deleted();
        $this->assertNull($entry->getWhen());
    }

    public function testAddsByNameFromArray()
    {
        $entry = new Writer\Deleted();
        $entry->setBy(['name' => 'Joe']);
        $this->assertEquals(['name' => 'Joe'], $entry->getBy());
    }

    public function testAddsByEmailFromArray()
    {
        $entry = new Writer\Deleted();
        $entry->setBy(['name' => 'Joe', 'email' => 'joe@example.com']);
        $this->assertEquals(['name' => 'Joe', 'email' => 'joe@example.com'], $entry->getBy());
    }

    public function testAddsByUriFromArray()
    {
        $entry = new Writer\Deleted();
        $entry->setBy(['name' => 'Joe', 'uri' => 'http://www.example.com']);
        $this->assertEquals(['name' => 'Joe', 'uri' => 'http://www.example.com'], $entry->getBy());
    }

    public function testAddByThrowsExceptionOnInvalidNameFromArray()
    {
        $entry = new Writer\Deleted();

        $this->expectException(Writer\Exception\ExceptionInterface::class);
        $entry->setBy(['name' => '']);
    }

    public function testAddByThrowsExceptionOnInvalidEmailFromArray()
    {
        $entry = new Writer\Deleted();

        $this->expectException(Writer\Exception\ExceptionInterface::class);
        $entry->setBy(['name' => 'Joe', 'email' => '']);
    }

    public function testAddByThrowsExceptionOnInvalidUriFromArray()
    {
        $this->markTestIncomplete('Pending Laminas\URI fix for validation');

        $entry = new Writer\Deleted();

        $this->expectException(Writer\Exception\ExceptionInterface::class);
        $entry->setBy(['name' => 'Joe', 'uri' => 'notauri']);
    }

    public function testAddByThrowsExceptionIfNameOmittedFromArray()
    {
        $entry = new Writer\Deleted();

        $this->expectException(Writer\Exception\ExceptionInterface::class);
        $entry->setBy(['uri' => 'notauri']);
    }

    /**
     * @covers \Laminas\Feed\Writer\Deleted::getBy
     */
    public function testGetBy()
    {
        $entry = new Writer\Deleted();

        $by = $entry->getBy();
        $this->assertNull($by);

        $entry->setBy(['name' => 'Joe', 'email' => 'joe@example.com']);
        $this->assertEquals(['name' => 'Joe', 'email' => 'joe@example.com'], $entry->getBy());
    }

    public function testSetByException()
    {
        $entry = new Writer\Deleted();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid parameter: "uri" array value must be a non-empty string and valid URI/IRI'
        );
        $entry->setBy(['name' => 'joe', 'email' => 'joe@example.com', 'uri' => '']);
    }

    /**
     * @covers \Laminas\Feed\Writer\Deleted::getComment
     * @covers \Laminas\Feed\Writer\Deleted::setComment
     * @covers \Laminas\Feed\Writer\Deleted::remove
     */
    public function testCommentAndRemove()
    {
        $entry = new Writer\Deleted();

        $comment = $entry->getComment();
        $this->assertNull($comment);

        $entry->setComment('foo');
        $this->assertEquals('foo', $entry->getComment());

        $entry->remove('comment');
        $this->assertNull($entry->getComment());
    }

    /**
     * @covers \Laminas\Feed\Writer\Deleted::getEncoding
     * @covers \Laminas\Feed\Writer\Deleted::setEncoding
     */
    public function testEncoding()
    {
        $entry = new Writer\Deleted();

        $encoding = $entry->getEncoding();
        $this->assertEquals('UTF-8', $encoding);

        $entry->setEncoding('ISO-8859-1');
        $this->assertEquals('ISO-8859-1', $entry->getEncoding());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid parameter: parameter must be a non-empty string');
        $entry->setEncoding(null);
    }

    /**
     * @covers \Laminas\Feed\Writer\Deleted::getType
     * @covers \Laminas\Feed\Writer\Deleted::setType
     */
    public function testType()
    {
        $entry = new Writer\Deleted();

        $type = $entry->getType();
        $this->assertNull($type);

        $entry->setType('atom');
        $this->assertEquals('atom', $entry->getType());
    }

    public function testFluentInterface()
    {
        $entry = new Writer\Deleted();

        $result = $entry->setType('type')
            ->setBy(['name' => 'foo'])
            ->setComment('comment')
            ->setEncoding('utf-8')
            ->setReference('foo')
            ->setWhen(null);

        $this->assertSame($result, $entry);
    }
}
