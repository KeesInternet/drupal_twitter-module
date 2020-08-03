<?php

namespace Drupal\twitter_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Field formatter "twitter_field_default".
 *
 * @FieldFormatter(
 *   id = "twitter_field_default",
 *   label = @Translation("Twitter field default"),
 *   field_types = {
 *     "twitter_field",
 *   }
 * )
 */
class Twitter_fieldDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
    public function viewElements(FieldItemListInterface $items, $langcode)
    {
        $config = \Drupal::config('twitter_field.twitter_api');
        $settings = [];
        $settings['oauth_access_token'] = $config->get('oauth_access_token');
        $settings['oauth_access_token_secret'] = $config->get('oauth_access_token_secret');
        $settings['consumer_key'] = $config->get('consumer_key');
        $settings['consumer_secret'] = $config->get('consumer_secret');
        $output = [];
        $build["#theme"] = "twitter_field_formatter";
        foreach ($items as $delta => $item) {
            $filter = $item->value;
            $count = $item->count;
            if (isset($filter) && $filter != "") {
                $build["#filter"] = $filter;
                $twitter = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $settings['oauth_access_token'], $settings['oauth_access_token_secret']);                    
                if ($filter[0] == "@") {
                    $build["#type"] = "username";
                    try{
                        $tweets = $twitter->get("statuses/user_timeline", ["screen_name" => $filter, "count" => $count, "lang" => "nl"]);
                        if (is_array($tweets)) {
                            foreach ($tweets as $key => $tweet) {
                                $build["#tweets"][] = $tweet;
                            }
                        }
                    }catch(\Abraham\TwitterOAuth\TwitterOAuthException $exception) {
                        $build["error"] = "No tweets available.";
                    }
                    $output[$delta] = $build;
                } else {                    
                    $build["#type"] = "hashtag";
                    try{
                        $tweets = $twitter->get("search/tweets", ["q" => $filter, "count" => $count, "lang" => "nl"]);
                        if (is_array($tweets)) {
                            if (isset($tweets->statuses)) {
                                foreach ($tweets->statuses as $key => $tweet) {
                                    $build["#tweets"][] = $tweet;
                                }
                            }
                        }
                    } catch(\Abraham\TwitterOAuth\TwitterOAuthException $exception) {
                        $output[$delta]["error"] = "No tweets available.";
                    }                        
                    $output[$delta] = $build;
                }                   
            }
        }
        return $output;
    }
}