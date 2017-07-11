<?php
/**
 * @file
 * Contains \Drupal\contactinfo\Form\ContactInfoSettingsForm
 */
namespace Drupal\contactinfo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configure contactinfo settings for this site.
 */
class ContactInfoSettingsForm extends ConfigFormBase {
  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contactinfo_admin_settings';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'contactinfo.settings',
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('contactinfo.settings');

 $form['contactinfo']['#tree'] = TRUE;
  $form['contactinfo']['type'] = array(
    '#type' => 'radios',
    '#title' => t('Contact information type'),
    '#description' => t('Is this for an individual or a business?'),
    '#options' => array(
      'personal' => t('Personal'),
      'business' => t('Organization/Business'),
    ),
    '#default_value' => $config->get('type'),
  );
  $form['contactinfo']['fn_n'] = array(
    '#type' => 'fieldset',
    '#title' => t('Full Name'),
    '#description' => t('If this site is your personal site, enter your full name here.'),
    '#states' => array(
      // Hide this fieldset if type is set to “business”.
      'invisible' => array(
        ':input[name="contactinfo[type]"]' => array('value' => 'business'),
      ),
    ),
    '#prefix' => '<div id="edit-hcard-fn-n-wrapper">',
    '#suffix' => '</div>',
  );
  $form['contactinfo']['fn_n']['given-name'] = array(
    '#type' => 'textfield',
    '#title' => t('First Name'),
    '#description' => t('Your first name.'),
    '#default_value' => $config->get('given-name'),
  );
  $form['contactinfo']['fn_n']['family-name'] = array(
    '#type' => 'textfield',
    '#title' => t('Last Name'),
    '#description' => t('Your last name.'),
    '#default_value' => $config->get('family-name'),
  );
  $form['contactinfo']['org'] = array(
    '#type' => 'textfield',
    '#title' => t('Organization/Business Name'),
    '#default_value' => $config->get('org'),
    '#description' => t('The name of your organization or business.'),
    '#prefix' => '<div class="contactinfo-org-wrapper clearfix">',
  );
  $form['contactinfo']['use_site_name'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use site name'),
    '#default_value' => $config->get('use_site_name'),
    '#suffix' => '</div>',
  );
  $form['contactinfo']['tagline'] = array(
    '#type' => 'textfield',
    '#title' => t('Tagline'),
    '#default_value' => $config->get('tagline'),
    '#description' => t('A short tagline.'),
    '#prefix' => '<div class="contactinfo-tagline-wrapper clearfix">',
  );
  $form['contactinfo']['use_site_slogan'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use site slogan'),
    '#default_value' => $config->get('use_site_slogan'),
    '#suffix' => '</div>',
  );
  $form['contactinfo']['adr'] = array(
    '#type' => 'fieldset',
    '#title' => t('Address'),
    '#description' => t('Enter the contact address for this website.'),
  );
  $form['contactinfo']['adr']['street-address'] = array(
    '#type' => 'textfield',
    '#title' => t('Street Address'),
    '#default_value' => $config->get('street-address'),
  );
  $form['contactinfo']['adr']['extended-address'] = array(
    '#type' => 'textfield',
    '#title' => t('Extended Address'),
    '#default_value' => $config->get('extended-address'),
  );
  $form['contactinfo']['adr']['locality'] = array(
    '#type' => 'textfield',
    '#title' => t('City'),
    '#default_value' => $config->get('locality'),
  );
  $form['contactinfo']['adr']['region'] = array(
    '#type' => 'textfield',
    '#title' => t('State/Province'),
    '#default_value' =>$config->get('region'),
    '#size' => 10,
  );
  $form['contactinfo']['adr']['postal-code'] = array(
    '#type' => 'textfield',
    '#title' => t('Postal Code'),
    '#default_value' => $config->get('postal-code'),
    '#size' => 10,
  );
  $form['contactinfo']['adr']['country-name'] = array(
    '#type' => 'textfield',
    '#title' => t('Country'),
    '#default_value' => $config->get('country-name'),
  );
  $form['contactinfo']['location'] = array(
    '#type' => 'fieldset',
    '#title' => t('Geographical Location'),
    '#description' => t('Enter your geographical coordinates to help people locate you.'),
  );
  $form['contactinfo']['location']['longitude'] = array(
    '#type' => 'textfield',
    '#title' => t('Longitude'),
    '#default_value' => $config->get('longitude'),
    '#description' => t('Longitude, in full decimal format (like -121.629562).'),
  );
  $form['contactinfo']['location']['latitude'] = array(
    '#type' => 'textfield',
    '#title' => t('Latitude'),
    '#default_value' => $config->get('latitude'),
    '#description' => t('Latitude, in full decimal format (like 38.827382).'),
  );
  $form['contactinfo']['phone'] = array(
    '#type' => 'fieldset',
    '#title' => t('Phones'),
    '#description' => t('Enter the numbers at which you would like to be reached.'),
  );
  $form['contactinfo']['phone']['voice'] = array(
    '#type' => 'textfield',
    '#title' => t('Voice Phone Number(s)'),
    '#default_value' => $config->get('voice'),
    '#description' => t('Voice phone numbers, separated by commas.'),
  );
  $form['contactinfo']['phone']['fax'] = array(
    '#type' => 'textfield',
    '#title' => t('Fax Number(s)'),
    '#default_value' =>$config->get('fax'),
    '#description' => t('Fax numbers, separated by commas.'),
  );
  $form['contactinfo']['email'] = array(
    '#type' => 'textfield',
    '#title' => t('Email'),
    '#default_value' => $config->get('email'),
    '#description' => t('Enter this site’s contact email address. This address will be displayed publicly, do not enter a private address.'),
    '#element_validate' => array('contactinfo_validate_email'),
  );
  $moduleHandler = \Drupal::service('module_handler');
  if ($moduleHandler->moduleExists('invismail')){
    $form['contactinfo']['email']['#description'] .= ' ' . t('This address will be obfuscated to protect it from spammers.');
  }
  else {
    $form['contactinfo']['email']['#description'] .= ' ' . t('Install the <a href="http://drupal.org/project/invisimail" target="_blank">Invisimail</a> module to protect this address from spammers.');
  }
  $drupalConfig = \Drupal::config('system.site');
  $form['#attached']['drupalSettings']['contactinfo']['sitename'] = $drupalConfig->get('name');
  $form['#attached']['drupalSettings']['contactinfo']['siteslogan'] = $drupalConfig->get('slogan');

  $form['#attached']['library'][] = 'contactinfo/contactinfoadmin';
    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $values              = $form_state->getValue('contactinfo');
    $this->config('contactinfo.settings')
      ->set('type', $values['type'])
      ->set('given-name', $values['fn_n']['given-name'])
      ->set('family-name', $values['fn_n']['family-name'])
      ->set('org',$values['org'])
      ->set('use_site_name',$values['use_site_name'])
      ->set('tagline',$values['tagline'])
      ->set('use_site_slogan', $values['use_site_slogan'])
      ->set('street-address', $values['adr']['street-address'])
      ->set('extended-address', $values['adr']['extended-address'])
      ->set('locality', $values['adr']['locality'])
      ->set('region', $values['adr']['region'])
      ->set('postal-code', $values['adr']['postal-code'])
      ->set('country-name', $values['adr']['country-name'])      
      ->set('latitude', $values['location']['latitude'])
      ->set('longitude', $values['location']['longitude'])
      ->set('voice', $values['phone']['voice'])
      ->set('fax', $values['phone']['fax'])
      ->set('email', $values['email'])
      ->save();

    parent::submitForm($form, $form_state);
  }
}

