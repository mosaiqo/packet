<?php namespace Mosaiqo\Packet\Helpers;

use Illuminate\Filesystem\Filesystem;
use Mustache_Engine as Mustache;

class TemplateHelper
{
	/**
	 * @var Filesystem
	 */
	private $file;

	/**
	 * @var Mustache_Engine
	 */
	private $mustache;

	public function __construct(Filesystem $file)
	{
		require __DIR__ . '/../../vendor/mustache/mustache/src/Mustache/Autoloader.php';
    \Mustache_Autoloader::register();

		$this->file = $file;
		$this->mustache = new Mustache;
	}

	public function render($input, $template, $destination)
	{
		$template = $this->file->get($template);

		$stub = $this->mustache->render($template, $input);

		$this->file->put($destination, $stub);
	}
}