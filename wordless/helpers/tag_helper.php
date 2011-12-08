<?php
/**
 * TagHelper
 *
 * Provides methods to generate HTML tags programmatically when you can't use a Builder.
 * @package Wordless
 */
class TagHelper {

  private function tag_options($options, $prefix = "") {

    $attributes = array();

    if (is_array($options)) {

      foreach ($options as $option_key => $option_value) {

        if (is_array($option_value)){
          if($option_key == "data"){
            $attributes[] = $this->tag_options($option_value, $option_key . "-");
          } else {
            $html_content[] = $prefix . $option_key . "=" . "\"". addslashes(json_encode($option_value)) . "\"";
          }
        } else {
          if (is_null($option_value) || (empty($option_value)) || ($option_value == $option_key)) {
            $html_content[] = $prefix . $option_key;
          } elseif(is_bool($option_value) && ($option_value == true)) {
            $html_content[] = $prefix . $option_key . "=" . "\"". $prefix . $option_key . "\"";
          } else {
            $html_content[] = $prefix . $option_key . "=" . "\"". $option_value . "\"";
          }
        }
      }
    } else {
      //We have only a simple string and not an array
      $html_content[] = $options;
    }

    return join(" ", $html_content);
  }

  function content_tag($name, $content, $options = NULL, $escape = false) {

    if (is_null($content)){
      $html_content = "<" . $name;
      if(!is_null($options)){
        $html_content .= " " . $this->tag_options($options);
      }
      $html_content .= "/>";
    } else {
      $html_content = "<" . $name;
      if(!is_null($options)){
        $html_content .= " " . $this->tag_options($options);
      }
      $html_content .= ">";
      $html_content .= ((bool) $escape) ? htmlentities($content) : $content;
      $html_content .= "</" . $name . ">";
    }

    return $html_content;
  }

  function option_tag($text, $name, $value, $selected = NULL) {
    $options = array(
      "name"  => $name,
      "value" => $value
    );

    if ($selected) {
      $options["selected"] = true;
    }

    return $this->content_tag("option", $text, $options);
  }

  function link_to($text = '', $link = NULL, $attributes = NULL) {
    if (!is_string($link)) {
      $link = "#";
    }

    $options = array("href" => $link);
    if (is_array($attributes)){
      $options = array_merge($options, $attributes);
    }

    return $this->content_tag("a", $text, $options);
  }

}

Wordless::register_helper("TagHelper");
