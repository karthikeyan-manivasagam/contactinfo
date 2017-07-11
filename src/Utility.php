<?php
/**
 * @file
 * Contains \Drupal\contactinfo\Utility
 */
namespace Drupal\contactinfo;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Component\Utility\Html;

class Utility {


 public static function getContactInfoVars() {
  $contactinfo =  \Drupal::config('contactinfo.settings');
  $drupalConfig = \Drupal::config('system.site');
  //print_r($contactinfo->get('type')); die;
  // Build $variables from scratch.
  $variables['#type'] = $contactinfo->get('type');
  $variables['#given_name'] = SafeMarkup::checkPlain($contactinfo->get('given-name'))->__toString();
  $variables['#family_name'] = SafeMarkup::checkPlain($contactinfo->get('family-name'))->__toString();
  $variables['#org'] = $contactinfo->get('use_site_name') ? SafeMarkup::checkPlain($drupalConfig->get('name'))->__toString() : SafeMarkup::checkPlain($contactinfo->get('org'))->__toString();
  $variables['#street_address'] = SafeMarkup::checkPlain($contactinfo->get('street-address'))->__toString();
  $variables['#extended_address'] = SafeMarkup::checkPlain($contactinfo->get('extended-address'))->__toString();
  $variables['#locality'] = SafeMarkup::checkPlain($contactinfo->get('locality'))->__toString();
  $variables['#region'] = SafeMarkup::checkPlain($contactinfo->get('region'))->__toString();
  $variables['#postal_code'] = SafeMarkup::checkPlain($contactinfo->get('postal-code'))->__toString();
  $variables['#country'] = SafeMarkup::checkPlain($contactinfo->get('country-name'))->__toString();
  $variables['#longitude'] = SafeMarkup::checkPlain($contactinfo->get('longitude'))->__toString();
  $variables['#latitude'] = SafeMarkup::checkPlain($contactinfo->get('latitude'))->__toString();
  $variables['#tagline'] = $contactinfo->get('use_site_slogan') ? SafeMarkup::checkPlain($drupalConfig->get('slogan'))->__toString() : SafeMarkup::checkPlain($contactinfo->get('tagline'))->__toString();
  $variables['#use_site_name'] = $contactinfo->get('use_site_name');
  $variables['#use_site_slogan'] = $contactinfo->get('use_site_slogan');
  // Generate formatted longitude and latitude.
  $variables['#longitude_formatted'] = self::coordConvert($contactinfo->get('longitude'), 'longitude');
  $variables['#latitude_formatted'] = self::coordConvert($contactinfo->get('latitude'), 'latitude');

  // Generates the output for the 'phones' variable.
  if ($contactinfo->get('voice')) {
    $phone_text = SafeMarkup::checkPlain($contactinfo->get('voice'))->__toString();
    $phones = explode(',', $phone_text);
    $variables['#phones'] = array_map('trim', $phones);
  }

  // Generates the output for the 'faxes' variable.
  if ($contactinfo->get('fax')) {
    $fax_text = SafeMarkup::checkPlain($contactinfo->get('fax'))->__toString();
    $faxes = explode(',', $fax_text);
    $variables['#faxes'] = array_map('trim', $faxes);
  }

  // Generate the output for the 'email' variable.
  if ($contactinfo->get('email')) {
    $email = SafeMarkup::checkPlain($contactinfo->get('email'))->__toString();
    // Use obfuscation provided by invisimail module.
    if (function_exists('invisimail_encode_html')) {
      $variables['#email'] = invisimail_encode_html($email);
      $variables['#email_url'] = INVISIMAIL_MAILTO_ASCII . $variables['email'];
    }
    else {
      $variables['#email'] = $email;
      $variables['#email_url'] = 'mailto:' . $email;
    }
  }

  // Generate ID.
  $id = 'contactinfo';
  if ($contactinfo->get('type') == 'personal') {
    $id .= !empty($contactinfo->get('given-name')) ? '-' . SafeMarkup::checkPlain($contactinfo->get('given-name'))->__toString() : '';
    $id .= !empty($contactinfo->get('family-name')) ? '-' . SafeMarkup::checkPlain($contactinfo->get('family-name'))->__toString() : '';
  }
  else {
    $id .= !empty($contactinfo->get('org')) ? '-' . SafeMarkup::checkPlain($contactinfo->get('org'))->__toString() : '';
  }
  $variables['#id'] = Html::getUniqueId($id);
   return $variables;

}

 /**
 * Helper function to convert longitude or latitude points.
 *
 * Convert a decimal-degree longitude or latitude point into degrees and
 * decimal minutes.
 *
 * @param float $decimal
 *   Decimal value for a longitude or latitude point.
 * @param string $direction
 *   Strings 'longitude' or 'latitude' are the only acceptable inputs.
 *
 * @return string
 *   String containing a single character for N, S, E, or W, the degrees as
 *   whole number, and minutes as a decimal value.
 */
public static function coordConvert($decimal, $direction) {
  $decimal = floatval($decimal);
  if (!$decimal) {
    return FALSE;
  }
  switch ($direction) {
    case 'longitude':
      $coord_direction = ($decimal < 0) ? 'W' : 'E';
      break;

    case 'latitude':
      $coord_direction = ($decimal < 0) ? 'S' : 'N';
      break;

    default:
      return FALSE;
  }

  $coord_degrees = intval($decimal);
  $coord_minutes = abs(fmod($decimal, 1) * 60);

  return $coord_direction . ' ' . $coord_degrees . '° ' . $coord_minutes . '"';
}

}