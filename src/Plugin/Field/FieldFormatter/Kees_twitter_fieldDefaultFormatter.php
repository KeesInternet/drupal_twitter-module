<?php

namespace Drupal\kees_twitter_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Field formatter "kees_twitter_field_default".
 *
 * @FieldFormatter(
 *   id = "kees_twitter_field_default",
 *   label = @Translation("Kees Twitter field default"),
 *   field_types = {
 *     "kees_twitter_field",
 *   }
 * )
 */
class Kees_twitter_fieldDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
    public function viewElements(FieldItemListInterface $items, $langcode) {
        $config = \Drupal::config('kees_twitter_field.twitter_api');
        $settings = [];
        $settings['oauth_access_token'] = $config->get('oauth_access_token');
        $settings['oauth_access_token_secret'] = $config->get('oauth_access_token_secret');
        $settings['consumer_key'] = $config->get('consumer_key');
        $settings['consumer_secret'] = $config->get('consumer_secret');

        $output = [];
        $build["#theme"] = "kees_twitter_field_formatter";

        foreach ($items as $delta => $item) {
        $filter = $item->value;
        $count = $item->count;
        if (isset($filter) && $filter != "") {
            $build["#filter"] = $filter;
            $twitter = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $settings['oauth_access_token'], $settings['oauth_access_token_secret']);
            if ($filter[0] == "@") {
                $build["#type"] = "username";
                $tweets = $twitter->get("statuses/user_timeline", ["screen_name" => $filter, "count" => $count, "lang" => "nl"]);
                foreach ($tweets as $key => $tweet) {
                    $build["#tweets"][] = $tweet;
                }
                $output[$delta] = $build;
            } else {
                $tweets = $twitter->get("search/tweets", ["q" => $filter, "count" => $count, "lang" => "nl"]);
                if(isset($tweets->statuses)){
                    foreach ($tweets->statuses as $key => $tweet) {
                        $build["#tweets"][] = $tweet;
                    }
                    $build["#type"] = "hashtag";
                    $output[$delta] = $build;
                }
            }
        }

        }
        return $output;

    }

}
