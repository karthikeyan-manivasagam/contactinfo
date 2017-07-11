<?php
/**
 * @file
 * Contains \Drupal\contactinfo\Plugin\Block\ContactInfoBlock.
 */
namespace Drupal\contactinfo\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\contactinfo\Utility;
/**
 * Provides a 'Contact Info' block.
 *
 * @Block(
 *   id = "contactinfo_contact_block",
 *   admin_label = @Translation("Contact Info Block")
 * )
 */
class ContactInfoBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {

    $render = ['#theme' => 'contactinfo_block'];
    $render = array_merge($render, Utility::getContactInfoVars());
    $render['#attached']['library'][] = 'contactinfo/contactinfo';
    return $render;
  }
}