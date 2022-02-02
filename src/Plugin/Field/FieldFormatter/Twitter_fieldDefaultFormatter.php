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

    private $twitterConnection;

    public function __construct()
    {
        // Get the config values from the twitter_field module.
        $config = \Drupal::config('twitter_field.twitter_api');

        // Create a TwitterOAuth object with consumer/user tokens.
        $this->twitterConnection = new TwitterOAuth(
            $config->get('consumer_key'),
            $config->get('consumer_secret'),
            $config->get('oauth_access_token'),
            $config->get('oauth_access_token_secret')
        );
    }

  /**
   * {@inheritdoc}
   */
    public function viewElements(FieldItemListInterface $items, $langcode)
    {
        $output = [];
        $build['#theme'] = 'twitter_field_formatter';

        // Loop trough all the items.
        foreach ($items as $delta => $item) {
            $filter = $item->value;

            // Check if item or hashtag is set.
            if (isset($filter) && $filter != "") {
                $build['#filter'] = $filter[0];

                // Get tweets by filter.
                if ($filter[0] == "@") {
                    $build["type"] = "username";
                    $tweets = $this->getTweetsByUser($filter, $item->count);
                    if (is_array($tweets)) {
                        $build["tweets"] = $tweets;
                    } else {
                        $build["error"] = $tweets;
                    }
                } else if ($filter[0] == "#") {
                    $build["type"] = "hashtag";
                    $tweets = $this->getTweetsByHashtag($filter, $item->count);
                    if (isset($tweets) && is_object($tweets)) {
                        $build["tweets"] = $tweets;
                    } else {
                        $build["error"] = $tweets;
                    }
                }

                $output[$delta] = $build;
                
            }
        }

        return $output;
    }

    /**
     * Get the tweets from the Twitter API by username.
     */
    private function getTweetsByUser($username, $tweet_count = 0) {
        try {
            return $this->twitterConnection->get(
                'statuses/user_timeline',
                [
                    'screen_name' => $username,
                    'count' => $tweet_count,
                    'lang' => 'nl'
                ],
            );
        } catch(\Abraham\TwitterOAuth\TwitterOAuthException $exception) {
            return "No tweets available from this user.";
        }
    }

    /**
     * Get the tweets from the Twitter API by hashtag.
     */
    private function getTweetsByHashtag($hashtag, $tweet_count = 0) {
        try {
            return $this->twitterConnection->get(
                'search/tweets',
                [
                    'q' => $hashtag,
                    'count' => $tweet_count,
                    'lang' => 'nl'
                ],
            )->statuses;
        } catch(\Abraham\TwitterOAuth\TwitterOAuthException $exception) {
            return "No tweets available with this hashtag.";
        }
    }
}