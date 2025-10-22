<?php
namespace inventor96\MakoMailer\interfaces;

use inventor96\MakoMailer\Envelope;

interface EmailSenderInterface {
	public function send(Envelope $envelope): bool;
}