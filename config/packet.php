<?php

return [
	// Set your vendor name here
	"vendor" => "",
	
	// Your name
	"name" => "",
	
	// Your repository username
	"username" => "",

	// The email to cantact you
	"email" => "",
	
	// Your role
	"role" => "Developer",
	
	// Your website	
	"homepage" => "",
	
	// Set the path to publish your packages
	"path" => "packages",
	
	//Dedault repository home page
	"repository" => "https://github.com",

	// Which license do you prefer
	"license" => "MIT",

	// Source directory structure
	"directories" => 
		[
			'src' => 'app',
			'tests' => 'tests',
			'config' => 'config',
			'database' => [
				'src' => 'database',
				'factories' => 'factories',
				'migrations' => 'migrations',
				'seeds' => 'seeds',
			],
			'public' => 'public',
			'resources' => [
				'src' => 'resources',
				'assets' => 'assets',
				'langs' => 'langs',
				'views' => 'views',
			]
		],

];