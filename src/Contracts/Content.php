<?php

namespace Galahad\Medusa\Contracts;

interface Content
{
	public function getId();
	
	public function getContentType() : ContentType;
	
	public function setContentType(ContentType $content_type) : Content;
	
	public function getData() : array;
	
	public function setData(array $data) : Content;
	
	public function getDescription() : string;
	
	public function setDescription(string $description) : Content;
	
	public function getSlug() : string;
	
	public function setSlug(string $description) : Content;
	
	public function save(array $options = []) : bool;
}
