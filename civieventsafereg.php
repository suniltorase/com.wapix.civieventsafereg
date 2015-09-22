<?php

require_once 'civieventsafereg.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function civieventsafereg_civicrm_config(&$config) {
  _civieventsafereg_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function civieventsafereg_civicrm_xmlMenu(&$files) {
  _civieventsafereg_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function civieventsafereg_civicrm_install() {
  $query = "CREATE TABLE IF NOT EXISTS `civicrm_safereg` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `is_allow_registrations` tinyint(4) NOT NULL,
    `entity_id` int(10) unsigned NOT NULL,
    `entity_table` varchar(255) NOT NULL,
     PRIMARY KEY (`id`),
     UNIQUE KEY `entity_index` (`entity_id`,`entity_table`)
     ) ENGINE=InnoDB";
   CRM_Core_DAO::executeQuery($query);
  return _civieventsafereg_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function civieventsafereg_civicrm_uninstall() {
  _civieventsafereg_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function civieventsafereg_civicrm_enable() {
  _civieventsafereg_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function civieventsafereg_civicrm_disable() {
  _civieventsafereg_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function civieventsafereg_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _civieventsafereg_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function civieventsafereg_civicrm_managed(&$entities) {
  _civieventsafereg_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function civieventsafereg_civicrm_caseTypes(&$caseTypes) {
  _civieventsafereg_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function civieventsafereg_civicrm_angularModules(&$angularModules) {
_civieventsafereg_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function civieventsafereg_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _civieventsafereg_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function civieventsafereg_civicrm_preProcess($formName, &$form) {

}

*/

/* Build form hook */

function civieventsafereg_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Registration') {
    $eventId = $form->_id;
    $form->add('checkbox', 'is_allow_registrations', ts('Allow other participants?'));
    $query = "SELECT * FROM `civicrm_safereg` WHERE `entity_id` = {$eventId} AND `entity_table` = 'civicrm_event'";
    $result = CRM_Core_DAO::executeQuery($query);
    $result->fetch();
    if ($result->N > 0) {
      $defaults['is_allow_registrations'] = $result->is_allow_registrations;
      $form->setDefaults($defaults);
    }
  }
}

/*Implements hook_civicrm_postProcess().*/

function civieventsafereg_civicrm_postProcess( $formName, &$form ) {
  $params = $form->exportValues();
  $params['id'] = $form->_id;
  $isAllowRegistration = 0;
  
  if (!empty($params['is_allow_registrations'])) {
    $isAllowRegistration = $params['is_allow_registrations'];
  }
  
  if ($formName == 'CRM_Event_Form_ManageEvent_Registration') {
    $query = "INSERT INTO `civicrm_safereg` (`is_allow_registrations`,`entity_id`,`entity_table`)
    VALUES ({$isAllowRegistration}, {$params['id']},'civicrm_event') ON DUPLICATE KEY UPDATE `entity_id` = {$params['id']}, `is_allow_registrations` = '{$isAllowRegistration}', `entity_table` = 'civicrm_event'";
    CRM_Core_DAO::executeQuery($query);
  }
}

/*Implements hook_civicrm_pageRun().*/

function civieventsafereg_civicrm_pageRun(&$page) {
  $pageName = $page->getVar('_name');
  $eventId = $page->getVar('_id');
  if ($pageName == 'CRM_Event_Page_EventInfo') {
    $query = "SELECT * FROM `civicrm_safereg` WHERE `entity_id` = {$eventId} AND `entity_table` = 'civicrm_event'";
    $result = CRM_Core_DAO::executeQuery($query);
    $result->fetch();
    if ($result->N > 0) {
      $is_allow_registrations = $result->is_allow_registrations;
      if (!empty($is_allow_registrations)) {
        CRM_Core_Resources::singleton()->addScriptFile('com.wapix.civieventsafereg', 'js/civisafereg.js');
        CRM_Core_Resources::singleton()->addStyleFile('com.wapix.civieventsafereg', 'css/style.css');
      }
    }
  }
  
}
