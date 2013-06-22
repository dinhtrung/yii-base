<?php
class WebUser extends CWebUser {
	/**
	 * Performs access check for this user.
	 * @param string $operation the name of the operation that need access check.
	 * @param array $params name-value pairs that would be passed to business rules associated
	 * with the tasks and roles assigned to the user.
	 * @param boolean $allowCaching whether to allow caching the result of access check.
	 * @return boolean whether the operations can be performed by this user.
	 */
	public function checkAccess($operation, $params = array(), $allowCaching = true)
	{
		if ($this->id == 1) return true;
		return parent::checkAccess($operation, $params, $allowCaching);
	}
}