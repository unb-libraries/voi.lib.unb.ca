<?php

namespace Drupal\voi_access\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller for non-admin VOI taxonomy term cannonical.
 */
class TermController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function searchByTerm(TermInterface $taxonomy_term) {
    // Get vocabulary and term.
    $vid = $taxonomy_term->bundle();
    $tid = $taxonomy_term->id();
    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

    // Get user and roles.
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    $admin = in_array('administrator', $roles);
    $record = in_array('record_editor', $roles);

    // Set up filters.
    switch ($vid) {
      case 'article_type':
        $filter = 'field_article_type';
        break;

      case 'language':
        $filter = 'field_language';
        break;

      case 'newspapers':
        $filter = 'field_newspaper';
        break;
    }

    // Redirect to vocabulary overview (admin) or search by term instead.
    if ($admin or $record) {
      return new RedirectResponse("/{$lang}/taxonomy/term/{$tid}/view");
    }
    else {
      return new RedirectResponse("/{$lang}/search?{$filter}={$tid}");
    }
  }

}
