<?php

/**
 * @see       https://github.com/laminas/laminas-feed for the canonical source repository
 * @copyright https://github.com/laminas/laminas-feed/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-feed/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Feed\Reader\Entry;

use Laminas\Feed\Reader;

/**
* @group Laminas_Feed
* @group Laminas_Feed_Reader
*/
class CommonTest extends \PHPUnit_Framework_TestCase
{
    protected $feedSamplePath = null;

    public function setup()
    {
        Reader\Reader::reset();
        $this->feedSamplePath = dirname(__FILE__) . '/_files/Common';
    }

    /**
     * Check DOM Retrieval and Information Methods
     */
    public function testGetsDomDocumentObject()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $this->assertInstanceOf('DOMDocument', $entry->getDomDocument());
    }

    public function testGetsDomXpathObject()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $this->assertInstanceOf('DOMXPath', $entry->getXpath());
    }

    public function testGetsXpathPrefixString()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('//atom:entry[1]', $entry->getXpathPrefix());
    }

    public function testGetsDomElementObject()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $this->assertInstanceOf('DOMElement', $entry->getElement());
    }

    public function testSaveXmlOutputsXmlStringForEntry()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $expected = file_get_contents($this->feedSamplePath.'/atom_rewrittenbydom.xml');
        $expected = str_replace("\r\n", "\n", $expected);
        $this->assertEquals($expected, $entry->saveXml());
    }

    public function testGetsNamedExtension()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $this->assertInstanceOf('Laminas\Feed\Reader\Extension\Atom\Entry', $entry->getExtension('Atom'));
    }

    public function testReturnsNullIfExtensionDoesNotExist()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $this->assertEquals(null, $entry->getExtension('Foo'));
    }

    /**
     * @group Laminas-8213
     */
    public function testReturnsEncodingOfFeed()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('UTF-8', $entry->getEncoding());
    }

    /**
     * @group Laminas-8213
     */
    public function testReturnsEncodingOfFeedAsUtf8IfUndefined()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom_noencodingdefined.xml')
        );
        $entry = $feed->current();
        $this->assertEquals('UTF-8', $entry->getEncoding());
    }

    /**
    * When not passing the optional argument type
    */
    public function testFeedEntryCanDetectFeedType()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $stub = $this->getMockForAbstractClass(
            'Laminas\Feed\Reader\Entry\AbstractEntry',
            array($entry->getElement(), $entry->getId())
        );
        $this->assertEquals($entry->getType(), $stub->getType());
    }

    /**
    * When passing a newly created DOMElement without any DOMDocument assigned
    */
    public function testFeedEntryCanSetAnyType()
    {
        $feed = Reader\Reader::importString(
            file_get_contents($this->feedSamplePath.'/atom.xml')
        );
        $entry = $feed->current();
        $domElement = new \DOMElement($entry->getElement()->tagName);
        $stub = $this->getMockForAbstractClass(
            'Laminas\Feed\Reader\Entry\AbstractEntry',
            array($domElement, $entry->getId())
        );
        $this->assertEquals($stub->getType(), Reader\Reader::TYPE_ANY);
    }
}
