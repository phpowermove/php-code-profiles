<?php declare(strict_types=1);
namespace gossi\code\profiles\tests;

use gossi\code\profiles\Profile;
use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase {
	public function testConfig(): void {
		$expected = [
			'indentation' => [
				'character' => 'tab',
				'size' => 1,
				'struct' => 1,
				'function' => 1,
				'blocks' => 1,
				'switch' => 1,
				'case' => 1,
				'break' => 1,
				'empty_lines' => false
			],

			'braces' => [
				'struct' => 'same',
				'function' => 'same',
				'blocks' => 'same'
			],

			'whitespace' => [
				'default' => [
					'before_curly' => true,
					'before_open' => true,
					'after_open' => false,
					'before_close' => false,
					'after_close' => false,
					'before_comma' => false,
					'after_comma' => true,
					'before_semicolon' => false,
					'after_semicolon' => true,
					'before_arrow' => false,
					'after_arrow' => false,
					'before_doublecolon' => false,
					'after_doublecolon' => false
				],

				'field_access' => [
					'before_arrow' => false,
					'after_arrow' => false,
					'before_doublecolon' => false,
					'after_doublecolon' => false
				],

				'function_invocation' => [
					'before_open' => false,
					'after_open' => false,
					'before_close' => false,
					'before_comma' => false,
					'after_comma' => true,
					'before_arrow' => false,
					'after_arrow' => false,
					'before_doublecolon' => false,
					'after_doublecolon' => false
				],

				'assignments' => [
					'before_assignment' => true,
					'after_assignment' => true
				],

				'operators' => [
					'before_binary' => true,
					'after_binary' => true,
					'before_unary' => true,
					'after_unary' => false,
					'before_prefix' => false,
					'after_prefix' => false,
					'before_postfix' => false,
					'after_postfix' => true
				],

				'conditionals' => [
					'before_questionmark' => true,
					'after_questionmark' => true,
					'before_colon' => true,
					'after_colon' => true
				],

				'grouping' => [
					'before_open' => false,
					'after_open' => false,
					'before_close' => false,
					'after_close' => false
				]
			],

			'newlines' => [
				'elseif_else' => false,
				'catch' => false,
				'finally' => false,
				'do_while' => false
			],

			'blanks' => [
				'before_namespace' => 0,
				'after_namespace' => 1,
				'after_use' => 1,
				'before_struct' => 1,
				'before_traits' => 1,
				'before_constants' => 1,
				'before_fields' => 1,
				'before_methods' => 1,
				'beginning_method' => 0,
				'end_method' => 0,
				'end_struct' => 1,
				'end_file' => 1
			]
		];

		$config = new Profile();

		$this->assertEquals($expected, $config->getConfig());
	}

	public function testIndentation(): void {
		$config = new Profile();

		$this->assertEquals('tab', $config->getIndentation('character'));
		$this->assertEquals(1, $config->getIndentation('size'));
		$this->assertEquals('', $config->getIndentation('wrong_key'));
	}

	public function testBraces(): void {
		$config = new Profile();

		$this->assertEquals('same', $config->getBraces('struct'));
		$this->assertEquals('', $config->getBraces('wrong_key'));
	}

	public function testWhitespace(): void {
		$config = new Profile();

		$this->assertTrue($config->getWhitespace('before_curly'));
		$this->assertTrue($config->getWhitespace('before_curly', 'struct'));
		$this->assertFalse($config->getWhitespace('after_open'));
		$this->assertTrue($config->getWhitespace('before_assignment', 'assignments'));
		$this->assertFalse($config->getWhitespace('wrong_key'));
	}

	public function testProfileLoader(): void {
		$config = new Profile('psr-2');
		$indentChar = $config->getIndentation('character');
		$this->assertEquals('space', $indentChar);
	}

	public function testGetBlanks(): void {
		$config = new Profile();

		$this->assertEquals(1, $config->getBlanks('before_methods'));
		$this->assertEquals(0, $config->getBlanks('non_existent_key'));
	}

	public function testNewLines(): void {
		$config = new Profile();

		$this->assertFalse($config->getNewline('catch'));
		$this->assertFalse($config->getNewline('non_existent_key'));
	}
}
