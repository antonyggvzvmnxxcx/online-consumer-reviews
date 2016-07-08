ONLINE CONSUMER REVIEW - Sentiment Analysis in PHP
---------

ONLINE CONSUMER REVIEW is a sentiment classifier. It uses a dictionary of words that are 
categorised as positive, negative or neutral, and a naive bayes algorithm to
calculate sentiment. To improve accuracy, phpInsight removes 'noise' words. 

##IDEA
* source.ign.php - Ignore words. These are words that do not indicate the sentiment and shouldn't be used in the classification.

* source.prefix.php - Prefix works. These words when prefixed with a words that indicated sentiment changes the meaning. e.g not good

* source.pos.php - Positive words. These are words that indicate sentiment is positive

* source.neg.php - Negative words. These are words that indicate sentiment is negative

* source.neu.php - Neutral words. These are words that indicate sentiment is neutral
