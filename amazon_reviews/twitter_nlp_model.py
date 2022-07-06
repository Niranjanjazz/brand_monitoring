from transformers import AutoTokenizer
from transformers import AutoModelForSequenceClassification
from transformers import TFAutoModelForSequenceClassification
from scipy.special import softmax
import mysql.connector
mydb = mysql.connector.connect(host='localhost', user='root', passwd='', database='sentimental_analysisdb')
curr = mydb.cursor()
product_name = 'OnePlus 9 Pro'
curr.execute("""select text,Tweet_id from twitter_table """)
tweets = curr.fetchall()
negative = 0
neutral = 0
positive = 0

roberta = "cardiffnlp/twitter-roberta-base-sentiment"  # downloading the pre trained model from hugging face website
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
        """UPDATE twitter_table set Positive = %s, Negative = %s, Neutral = %s, results = %s where Tweet_id = %s;""",
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

print(neutral)
print(negative)
print(positive)




