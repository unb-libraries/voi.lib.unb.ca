<?php

namespace Drupal\voi_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom block for site branding (homepage version).
 *
 * @Block(
 *   id = "voi_branding_home",
 *   admin_label = @Translation("Custom Branding (home)"),
 *   category = @Translation("Misc"),
 * )
 */
class VoiBrandingHome extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $markup = '
      <h1 class="text-center voi-branding">
        <a href="/">Vocabularies of Identity</a>
        <span>|</span>
        <a href="/fr">Vocabulaires identitaires</a>
      </h1>
    ';

    return [
      '#markup' => $this->t($markup),
    ];
  }

}
