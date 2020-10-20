<?php

namespace Drupal\voi_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a custom footer block.
 *
 * @Block(
 *   id = "voi_footer",
 *   admin_label = @Translation("Custom Footer"),
 *   category = @Translation("Misc"),
 * )
 */
class VoiFooter extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $markup = '
      <div class="row text-center" id="footer-logos">
        <div class="col-lg-12">
          <a href="http://www.sshrc-crsh.gc.ca/" style="border: none;"><img alt="" class="img-responsive" height="50" width="483" src="/splash/sshrc.png"></a>
        </div>
        <div class="col-lg-4 mt-3">
          <a href="http://unb.ca" style="border: none;"><img alt="" class="img-responsive" height="50" width="311" src="/splash/unb.png"></a>
        </div>
        <div class="col-lg-4 mt-3">
          <a href="http://www.umoncton.ca" style="border: none;"><img alt="" class="img-responsive" height="50" width="201" src="/splash/udem.png"></a>
        </div>
        <div class="col-lg-4 mt-3">
           <a href="http://stu.ca" style="border: none;"><img alt="" class="img-responsive" height="50" width="288" src="/splash/stu.png"></a>
        </div>
      </div>
      <div class="row" id="footer-text">
        <div class="col-md-2">&copy; <a href="//lib.unb.ca">UNB Libraries</a></div>
      </div>
    ';

    return [
      '#markup' => $this->t($markup),
    ];
  }

}
