<?php

/**
 * StreamAction returns entries of an given wall
 *
 * @author Luke
 */
class NoteStreamAction extends StreamAction {

	/**
	 * Inject Question Specific SQL
	 */
	protected function prepareSQL() {
		$this->sqlWhere .= " AND object_model='Note'";
		parent::prepareSQL();
	}
	
	
	/**
	 * Handle Question Specific Filters
	 */
	protected function setupFilterSQL() {
		
		parent::setupFilterSQL();
		
		
	}
	
	
}

?>
