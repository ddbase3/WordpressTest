<?php

namespace WordpressTest\Content;

use Base3\Api\IOutput;
use Base3\Api\ISystemService;
use Base3\Accesscontrol\Api\IAccesscontrol;
use Base3\Configuration\Api\IConfiguration;
use Base3\Database\Api\IDatabase;
use Base3\Logger\Api\ILogger;

class WpTest implements IOutput {

	public function __construct(
		private readonly ISystemService $systemservice,
		private readonly IAccesscontrol $accesscontrol,
		private readonly IConfiguration $configuration,
		private readonly ILogger $logger,
		private readonly IDatabase $database
	) {}

	public static function getName(): string {
		return 'wptest';
	}

	public function getOutput(string $out = 'html', bool $final = false): string {
		$html = '<p>This is a test.</p>';
		$html .= '<p>'
			. $this->systemservice->getHostSystemName()
			. ' '
			. $this->systemservice->getHostSystemVersion()
			. ' - '
			. $this->systemservice->getEmbeddedSystemName()
			. ' '
			. $this->systemservice->getEmbeddedSystemVersion()
			. '</p>';
		$html .= '<p>User: ' . $this->accesscontrol->getUserId() . '</p>';

		$this->logger->info("hello", ["scope"=>"test"]);

		$this->database->connect();
		$html .= '<p>db configuration: ' . json_encode($this->database->multiQuery("SELECT * from base3_configuration;")) . '</p>';
		$html .= '<p>db logger: ' . json_encode($this->database->multiQuery("SELECT * from base3_log;")) . '</p>';

		return $html;
	}

	public function getHelp() : string {
		return 'Help for WpTest';
	}
}
