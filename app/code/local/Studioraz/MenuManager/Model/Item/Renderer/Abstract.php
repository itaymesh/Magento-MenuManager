<?php
class Studioraz_MenuManager_Model_Item_Renderer_Abstract extends Varien_Object {

	/**
	 * Render and return the value
	 *
	 * @return mixed
	 */
	final public function render()
	{
			$this->_beforeRender();
			$this->_render();
			$this->_afterRender();

			return $this->getValue();
	}


	/**
	 * @return string
	 */
	protected function _render() {
		return $this;
	}

	/**
	 * Can be extended in child classes to make changes to value
	 * before self::_render()
	 *
	 * @return $this
	 */
	protected function _beforeRender()
	{
		return $this;
	}


	/**
	 * Can be extended in child classes to make changes to value
	 * after self::_render()
	 *
	 * @return $this
	 */
	protected function _afterRender()
	{
		return $this;
	}

}