import mysql.connector
import pandas as pd
import tweepy as tw
from tqdm import tqdm
import sys
from transformers import AutoTokenizer
from transformers import AutoModelForSequenceClassification
from transformers import TFAutoModelForSequenceClassification
from scipy.special import softmax
import mysql.connector

consumer_api_key = 'api_key'
consumer_api_secret = 'api_scret_key'

auth = tw.OAuthHandler(consumer_api_key, consumer_api_secret)
api = tw.API(auth, wait_on_rate_limit=True)

product_name = sys.argv[1]
search_words = sys.argv[2]
tweets = tw.Cursor(api.search_tweets,
                   q=search_words + "-filter:retweets",
                   lang="en",
                   result_type="recent"
                   ).items(200)

mydb = mysql.connector.connect(host='localhost', user='root', passwd='', database='db_name')
curr = mydb.cursor()

# retrieve the tweets
tweets_copy = []
for tweet in tqdm(tweets):
        hashtags = []
        try:
            for hashtag in tweet.entities["hashtags"]:
                hashtags.append(hashtag["text"])
        except:
            pass
        index = 0

        #index = index + 1
        # user_name = str(tweet.user.name)
        user_location = str(tweet.user.location)
        user_description = str(tweet.user.description)
        user_created = str(tweet.user.created_at)
        user_followers = int(tweet.user.followers_count)
        user_friends = int(tweet.user.friends_count)
        user_favourites = int(tweet.user.favourites_count)
        user_verified = str(tweet.user.verified)
        tweet_date = str(tweet.created_at)
        text = str(tweet.text)
        hashtags = str([hashtags if hashtags else None])
        source = str(tweet.source)
        is_retweet = str(tweet.retweeted)
        Product_Name = product_name
        Positive = ''
        Negative = ''
        Neutral = ''
        Results = ''
        TweetId = ''
        curr.execute("""INSERT INTO `twitter_table` values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s ) """,
            (user_location, user_description, user_created, user_followers, user_friends,
             user_favourites, user_verified, tweet_date, text, hashtags, source, is_retweet, Product_Name, Positive,
             Negative, Neutral, Results, TweetId), multi=False)
        mydb.commit()


selectquery = "select text,Tweet_id from twitter_table where product_name = %(value)s"
params = {'value': product_name}
curr.execute(selectquery, params)
tweets = curr.fetchall()
negative = 0
neutral = 0
positive = 0

roberta = "cardiffnlp/twitter-roberta-base-sentiment"  
model = AutoModelForSequenceClassification.from_pretrained(roberta)
tokenizer = AutoTokenizer.from_pretrained(roberta)
labels = ['Negative', 'Neutral', 'Positive']

for tweet in tweets:
    tweet_words = []
    for word in str(tweet[0]).split(' '):

        if word.startswith('@') and len(
                word) > 1:  # is the word starts with @ and its leghth is greater than 1 it is an user
            word = '@user'  # converts the name to into @user string

        elif word.startswith('http'):
            word = "http"

        tweet_words.append(word)

    tweet_proc = " ".join(tweet_words)  # joining the words in the tweet list
    encoded_tweet = tokenizer(tweet_proc, return_tensors='pt',padding=True, truncation=True,max_length=50, add_special_tokens = True)  # encoding tweets into number
    output = model(**encoded_tweet)
    scores = output[0][0].detach().numpy()
    scores = softmax(scores)
    max_score = max(scores)
    print(max_score)
    if scores[0] == max_score:
        results = 'Negative'
    elif scores[1] == max_score:
        results = 'Neutral'
    elif scores[2] == max_score:
        results = 'Positive'
    curr.execute(
        """UPDATE twitter_table set Positive = %s, Negative = %s, Neutral = %s, Results = %s where Tweet_id = %s;""",
        (float(scores[2]), float(scores[0]), float(scores[1]), results, tweet[1],))
    mydb.commit()

    for i in range(len(scores)):
        l = labels[i]
        s = scores[i]
        print(l, s)
    a = max(scores)
    print(a)
    if scores[0] == a:
        negative = negative+1
    elif scores[1] == a:
        neutral = neutral + 1
    elif scores[2] == a:
        positive = positive+1






