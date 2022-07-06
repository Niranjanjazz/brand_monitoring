from transformers import AutoTokenizer
from transformers import AutoModelForSequenceClassification
from scipy.special import softmax
import mysql.connector
import sys
mydb = mysql.connector.connect(host='localhost', user='root', passwd='', database='db_name')
curr = mydb.cursor()

roberta = "cardiffnlp/twitter-roberta-base-sentiment"  
model = AutoModelForSequenceClassification.from_pretrained(roberta)
tokenizer = AutoTokenizer.from_pretrained(roberta)
labels = ['Negative', 'Neutral', 'Positive']

product_name = sys.argv[1]
# product_name = 'OnePlus 9 Pro_test'
selectquery = """select reviews,review_id from amazon_data where product_name = %(value)s """
params = {'value': product_name}
curr.execute(selectquery, params)
reviews = curr.fetchall()
negative = 0
neutral = 0
positive = 0

for review in reviews:

    encoded_review = tokenizer(review[0], return_tensors='pt',padding=True, truncation=True,max_length=50, add_special_tokens = True)  # encoding reviews into number
    print(encoded_review)
    output = model(**encoded_review)
    scores = output[0][0].detach().numpy()
    scores = softmax(scores)
    max_score = max(scores)
    print(max_score)
    if scores[0] == max_score:
        results = 'Negative'
    elif scores[1] ==  max_score:
        results = 'Neutral'
    elif scores[2] == max_score:
        results = 'Positive'

    curr.execute(
        """UPDATE amazon_data set positive = %s, negative = %s, neutral = %s, results = %s where review_id = %s;""",
        (float(scores[2]),float(scores[0]),float(scores[1]),results,review[1],))
    mydb.commit()

    for i in range(len(scores)):
        l = labels[i]
        s = scores[i]
        print(l, s)




