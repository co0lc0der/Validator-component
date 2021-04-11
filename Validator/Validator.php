<?php

class Validator
{
	private $passed = false, $errors = [], $db = null;

	public function __construct($db = null) {
    $this->setDB($db);
	}

	public function check($source, $items = []) {
		foreach($items as $item => $rules) {
			foreach($rules as $rule => $rule_value) {

				$value = $source[$item];

				if($rule == 'required' && empty($value)) {
					$this->addError(ucfirst($item) . " is required");
				} else if(!empty($value)) {
					switch ($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError(ucfirst($item) . " must be a minimum of {$rule_value} characters.");
							}
						break;

						case 'max':
							if(strlen($value) > $rule_value) {
								$this->addError(ucfirst($item) . " must be a maximum of {$rule_value} characters.");
							}
						break;

						case 'matches':
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}");
							}
						break;

            case 'int':
							if(!is_numeric($value)) {
								$this->addError(ucfirst($item) . " must be a number.");
							}
						break;

            case 'min_value':
							if((int) $value < $rule_value) {
								$this->addError(ucfirst($item) . " must be a minimum of {$rule_value}.");
							}
						break;

						case 'max_value':
							if((int) $value > $rule_value) {
								$this->addError(ucfirst($item) . " must be a maximum of {$rule_value}.");
							}
						break;

						case 'unique':
							$check = $this->db->get($rule_value, [[$item, '=', $value]]);
							if($check->count()) {
								$this->addError(ucfirst($item) . " already exists.");
							}
						break;

						case 'email':
							if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
								$this->addError("{$item} is not an email");
							}
						break;

            case 'regex':
							if (!preg_match($rule_value, $value)) {
								$this->addError(ucfirst($item) . " is not match");
							}
						break;
					}
				}
			}
		}

		if(empty($this->errors)) {
			$this->passed = true;
		}

		return $this;
	}

  public function setDB($db) {
		$this->db = $db;
	}

	private function addError($error) {
		$this->errors[] = $error;
	}

	public function errors() {
		return $this->errors;
	}

	public function passed() {
		return $this->passed;
	}
}