<?php declare(strict_types=1);
namespace gossi\code\profiles;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;

class Profile {

	/** @var array */
	private $config;

	public function __construct(string $profile = 'default') {
		$profileDir = __DIR__ . '/../profiles';

		$locator = new FileLocator([$profileDir]);
		$loader = new YamlLoader($locator);
		$builtIns = $this->readProfiles($loader, $profileDir);

		$profiles = [];
		$isBuiltin = in_array($profile, $builtIns);

		if ($isBuiltin) {
			$profiles[] = $loader->load($locator->locate($profile . '.yml', null, true));
		} else {
			$profiles[] = $loader->load($locator->locate('default.yml', null, true));
		}

		if (!empty($profile) && !$isBuiltin && file_exists($profile)) {
			$profiles[] = $loader->load($profile);
		}

		$processor = new Processor();
		$definition = new ProfileDefinition();
		$this->config = $processor->processConfiguration($definition, $profiles);
	}

	private function readProfiles(YamlLoader $loader, string $profileDir): array {
		$profiles = [];
		foreach (new \DirectoryIterator($profileDir) as $file) {
			if ($file->isFile() && $loader->supports($file->getFilename())) {
				$profiles[] = $file->getBasename('.yml');
			}
		}

		return $profiles;
	}

	public function getConfig(): array {
		return $this->config;
	}

	/**
	 * @param string $key
	 *
	 * @return string|int|bool
	 */
	public function getIndentation(string $key) {
		return $this->config['indentation'][$key] ?? '';
	}

	public function getBraces(string $key): string {
		return $this->config['braces'][$key] ?? '';
	}

	public function getWhitespace(string $key, string $context = 'default'): bool {
		if (isset($this->config['whitespace'][$context][$key])) {
			$val = $this->config['whitespace'][$context][$key];

			if ($val === 'default' && $context !== 'default') {
				return $this->getWhitespace($key);
			}

			return (bool) $val;
		} elseif ($context !== 'default') { // workaround?
			return $this->getWhitespace($key);
		}

		return false;
	}

	public function getBlanks(string $key): int {
		return $this->config['blanks'][$key] ?? 0;
	}

	public function getNewline(string $key): bool {
		return $this->config['newlines'][$key] ?? false;
	}
}
