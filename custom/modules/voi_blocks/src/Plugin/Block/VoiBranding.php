<?php

namespace Drupal\voi_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom block for site branding.
 *
 * @Block(
 *   id = "voi_branding",
 *   admin_label = @Translation("Custom Branding"),
 *   category = @Translation("Misc"),
 * )
 */
class VoiBranding extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $markup = '
      <div class="h1 text-center voi-branding">
        <a href="/">Vocabularies of Identity</a>
        <span>|</span>
        <a href="/fr">Vocabulaires Identitaires</a>
      </div>
    ';

    return [
      '#markup' => $this->t($markup),
    ];
  }

}
