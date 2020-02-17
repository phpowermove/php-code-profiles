<?php declare(strict_types=1);
namespace gossi\code\profiles\tests;

use gossi\code\profiles\Profile;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use PHPUnit\Framework\TestCase;

class PersonalProfileTest extends TestCase {
	/** @var vfsStreamDirectory */
	private $root;

	/** @var vfsStreamFile */
	private $configFile;

	public function setUp(): void {
		$this->root = vfsStream::setup();
		$this->configFile = vfsStream::newFile('my-profile.yaml')->at($this->root)->setContent('
whitespace:

  # global
  default:
    before_curly: true

    before_open: true
    after_open: false
    before_close: false
    after_close: false

    before_comma: false
    after_comma: true
    before_semicolon: false
    after_semicolon: true

    before_arrow: false
    after_arrow: false
    before_doublecolon: false
    after_doublecolon: false
    
  struct: ~
'
		);
	}

	public function testWhitespace(): void {
		$config = new Profile($this->configFile->url());

		$this->assertTrue($config->getWhitespace('before_curly'));
		$this->assertTrue($config->getWhitespace('before_curly', 'struct'));
		$this->assertFalse($config->getWhitespace('after_open'));
		$this->assertFalse($config->getWhitespace('wrong_key'));
	}
}
