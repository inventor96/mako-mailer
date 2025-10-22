<?php
namespace inventor96\MakoMailer\interfaces;

interface EmailUserInterface {
	public function getEmail(): string;
	public function getName(): ?string;
}