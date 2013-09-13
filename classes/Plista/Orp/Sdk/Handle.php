<?php
namespace Plista\Orp\Sdk;

interface Handle {

	/**
	 * @param $body
	 * @return bool
	 */
	public function validate($body);

	/**
	 * @param $body
	 * @return mixed
	 */
	public function handle($body);

}