<?php declare(strict_types=1);
namespace gossi\code\profiles;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlLoader extends FileLoader {
	public function load($resource, string $type = null): array {
		return Yaml::parse(file_get_contents($resource));
	}

	public function supports($resource, string $type = null): bool {
		return is_string($resource)
			&& in_array(pathinfo($resource, PATHINFO_EXTENSION), ['yml', 'yaml']);
	}
}
