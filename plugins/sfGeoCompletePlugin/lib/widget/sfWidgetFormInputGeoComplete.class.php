<?php

/**
 * sfWidgetFormInputGeoComplete creates an address input field that provides an auto-complete dropdown
 * as the user types in a location. The suggestions are provided from the Google GeoCoder v3. Since the
 * geocoder runs on the user's browser, this implementation is very lightweight and also will not be
 * limited by the Google's per IP request limits. This widget requires the sf_prototype_web_dir to be defined.
 *
 * @package    sfGeoCompletePlugin
 * @subpackage widget
 * @author     Oz Basarir <oz@ezkode.com>
 * @version    SVN: $Id: sfWidgetFormInputGeoComplete.class.php 6 2009-07-14 17:51:46Z admin $
 */
class sfWidgetFormInputGeoComplete extends sfWidgetFormInput
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * type: The widget type (text by default)
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
  protected function configure($options = array(), $attributes = array())
  {
    $class = $this->getAttribute('class');
    $this->setAttribute('class', $class.' auto_complete');
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetFormInput
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $input = parent::render($name, $value, $attributes, $errors);

    $suggestions = $this->renderTag('div', array('id'     => 'geo_complete_suggestions', 
                                                 'class'  => 'auto_complete', 
                                                 'style'  => 'display:none'));

    $js = '<script type="text/javascript">location_input = "'.$this->generateId($name).'"</script>';

    return $input.$suggestions.$js;
  }
  
  public function getJavaScripts()
  {
    return array('http://maps.google.com/maps/api/js?sensor=false',
                 sfConfig::get('sf_prototype_web_dir').'/js/prototype', 
                 sfConfig::get('sf_prototype_web_dir').'/js/scriptaculous',
                 sfConfig::get('sf_prototype_web_dir').'/js/effects',
                 sfConfig::get('sf_prototype_web_dir').'/js/controls',
                 sfConfig::get('sf_geo_complete_web_dir').'/js/geocomplete.js');
  }

  public function getStylesheets()
  {
    return array(sfConfig::get('sf_prototype_web_dir').'/css/input_auto_complete_tag.css' => 'all');
  }


}
