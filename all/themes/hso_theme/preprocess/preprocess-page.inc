<?php
function hso_theme_alpha_preprocess_page(&$vars) {
  $contexts = context_active_contexts();
  if (array_key_exists('kv-college', $contexts) || array_key_exists('kv-college-home', $contexts)) {
    $vars['logo'] = str_replace('bvs_logo_all.png', 'bvs_berufswahl.png', $vars['logo']);
  }

  if (array_key_exists('hs-college', $contexts) || array_key_exists('hs-college-home', $contexts)) {
    $vars['logo'] = str_replace('bvs_logo_all.png', 'logo_bvs_mit_hso.png', $vars['logo']);
  }

  if (array_key_exists('ks-college', $contexts) || array_key_exists('ks-college-home', $contexts)) {
    $vars['logo'] = str_replace('bvs_logo_all.png', 'bvs_bildung.png', $vars['logo']);
  }


  if (array_key_exists('ex-college', $contexts) || array_key_exists('ex-college-home', $contexts)) {
    $vars['logo'] = str_replace('bvs_logo_all.png', 'logo_bvs_mit_hso.png', $vars['logo']);
  }

  if (array_key_exists('lang-college', $contexts) || array_key_exists('lang-college-home', $contexts)) {
    $vars['logo'] = str_replace('bvs_logo_all.png', 'bvs_sprachen.png', $vars['logo']);
  }
}
