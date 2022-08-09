<?php
/**
 * Class Validator
 */
class Validator
{
	/**
	 * @var bool
	 */
	private bool $passed = false;

	/**
	 * list of errors
	 * @var array
	 */
	private array $errors = [];

	/**
	 * @var null|QueryBuilder
	 */
	private $db = null;

	/**
	 * @param null|QueryBuilder $db
	 */
	public function __construct($db = null)
	{
    $this->setDB($db);
	}

	/**
	 * @param array $source
	 * @param array $items
	 * @return Validator
	 */
	public function check(array $source, array $items = []): Validator
	{
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				$value = $source[$item] ?? '';

				if ($rule == 'required' && empty($value)) {
					$this->addError(ucfirst($item) . " is required");
				} else if (!empty($value)) {
					switch ($rule) {
						case 'min':
							if (strlen($value) < $rule_value) {
								$this->addError(ucfirst($item) . " must be a minimum of {$rule_value} characters.");
							}
							break;

						case 'max':
							if (strlen($value) > $rule_value) {
								$this->addError(ucfirst($item) . " must be a maximum of {$rule_value} characters.");
							}
							break;

						case 'matches':
							if ($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}");
							}
							break;

            case 'int':
							if (!is_numeric($value)) {
								$this->addError(ucfirst($item) . " must be a number.");
							}
							break;

            case 'min_value':
							if ((int) $value < $rule_value) {
								$this->addError(ucfirst($item) . " must be a minimum of {$rule_value}.");
							}
							break;

						case 'max_value':
							if ((int) $value > $rule_value) {
								$this->addError(ucfirst($item) . " must be a maximum of {$rule_value}.");
							}
							break;

						case 'unique':
							$check = $this->db->select($rule_value)->where([[$item, '=', $value]])->one();
							if ($check->getCount()) {
								$this->addError(ucfirst($item) . " already exists.");
							}
							break;

						case 'email':
							if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
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

		if (empty($this->errors)) {
			$this->passed = true;
		}

		return $this;
	}

	/**
	 * @param $db
	 * @return void
	 */
  public function setDB($db)
  {
		$this->db = $db;
	}

	/**
	 * @param string $error
	 * @return void
	 */
	private function addError(string $error): void
	{
		$this->errors[] = $error;
	}

	/**
	 * @return array
	 */
	public function errors(): array
	{
		return $this->errors;
	}

	/**
	 * @return bool
	 */
	public function passed(): bool
	{
		return $this->passed;
	}
}
